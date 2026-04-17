<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MartialArtsClass;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())
            ->with(['martialArtsClass', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modules.user.subscriptions.list', compact('subscriptions'));
    }

    public function checkout($id)
    {
        $class = MartialArtsClass::where('status', 'active')->findOrFail($id);
        
        // Check if already subscribed
        $existing = Subscription::where('user_id', Auth::id())
            ->where('martial_arts_class_id', $class->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'You are already subscribed to this class.']);
        }

        return view('modules.user.subscriptions.checkout', compact('class'));
    }

    public function processCheckout(Request $request, $id)
    {
        $class = MartialArtsClass::where('status', 'active')->findOrFail($id);
        $user = Auth::user();

        try {
            $session = $this->stripeService->createCheckoutSession(
                $user,
                $class,
                route('user.subscription.success'),
                route('user.schedule-session-detail', $class->id)
            );

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('user.schedule-session-list')->with(['status' => 'failed', 'message' => 'Invalid session.']);
        }

        try {
            $session = $this->stripeService->getCheckoutSession($sessionId);
            $userId = $session->metadata->user_id;
            $classId = $session->metadata->martial_arts_class_id;
            $class = MartialArtsClass::find($classId);

            // Create Subscription record
            $subscription = Subscription::updateOrCreate(
                ['stripe_subscription_id' => $session->subscription->id],
                [
                    'user_id' => $userId,
                    'martial_arts_class_id' => $classId,
                    'stripe_customer_id' => $session->customer,
                    'status' => $session->subscription->status,
                    'next_payment_date' => Carbon::parse($session->subscription->current_period_end),
                ]
            );

            // Record Payment (Upfront 2 months)
            // The total amount is the first invoice total
            $amountTotal = $session->amount_total / 100;
            $invoice = $session->subscription->latest_invoice;

            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $amountTotal,
                'stripe_payment_id' => $session->payment_intent,
                'stripe_invoice_url' => $invoice->hosted_invoice_url,
                'status' => 'succeeded',
            ]);

            return redirect()->route('user.subscription.list')
                ->with(['status' => 'success', 'message' => 'Subscription successful! You paid for 2 months (1st month + security).']);
        } catch (\Exception $e) {
            return redirect()->route('user.schedule-session-list')->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function cancel($id)
    {
        $subscription = Subscription::where('user_id', Auth::id())->findOrFail($id);

        try {
            $this->stripeService->cancelSubscription($subscription->stripe_subscription_id);
            $subscription->update([
                'status' => 'canceled',
                'ends_at' => Carbon::now(),
            ]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Subscription canceled successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }
}

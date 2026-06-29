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

    public function index(Request $request)
    {
        $query = Subscription::where('user_id', Auth::id())
            ->with(['martialArtsClass', 'payments']);

        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }

        if ($request->filled('package')) {
            $query->where('package_type', $request->package);
        }

        if ($request->filled('date')) {
            $dates = explode(' to ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('modules.user.subscriptions.list', compact('subscriptions'));
    }

    public function checkout(Request $request, $id)
    {
        $class = MartialArtsClass::where('status', 'active')->findOrFail($id);
        $plan = null;

        if ($request->has('plan_id')) {
            $plan = \App\Models\PricingPlan::findOrFail($request->plan_id);
        }
        
        // Check if already subscribed
        $existing = Subscription::where('user_id', Auth::id())
            ->where('martial_arts_class_id', $class->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'You are already subscribed to this class.']);
        }

        // Tax Calculation (Schedule Sessions only apply GST, no PST)
        $gstSetting = \App\Models\SiteSetting::where('key', 'gst_percentage')->first();
        $gstRate = $gstSetting ? floatval($gstSetting->value) : 0;
        $pstRate = 0;
        $totalTaxRate = $gstRate / 100;

        $package_type = request('package_type', 'normal');
        
        $price = $class->price;
        if ($package_type === 'day_pass') {
            $price = $class->day_pass_price ?? 0;
        } else if ($package_type === 'weekly_pass') {
            $price = $class->weekly_pass_price ?? 0;
        }

        $is_tax_inclusive = $class->is_tax_inclusive;

        // Calculate breakdown for the currently selected package
        $subtotal = $is_tax_inclusive ? $price / (1 + $totalTaxRate) : $price;
        $gstAmount = $subtotal * ($gstRate / 100);
        $pstAmount = 0;
        $total = $is_tax_inclusive ? $price : $subtotal + $gstAmount;

        // Calculate breakdown for Unlimited Package specifically (for JS toggling)
        $unlimited_subtotal = 0;
        $unlimited_gst = 0;
        $unlimited_pst = 0;
        $unlimited_total = 0;
        if ($class->unlimited_price) {
            $u_price = $class->unlimited_price;
            $unlimited_subtotal = $is_tax_inclusive ? $u_price / (1 + $totalTaxRate) : $u_price;
            $unlimited_gst = $unlimited_subtotal * ($gstRate / 100);
            $unlimited_total = $is_tax_inclusive ? $u_price : $unlimited_subtotal + $unlimited_gst;
        }

        $taxDetails = [
            'subtotal' => round($subtotal, 2),
            'gst_rate' => $gstRate,
            'pst_rate' => $pstRate,
            'gst_amount' => round($gstAmount, 2),
            'pst_amount' => round($pstAmount, 2),
            'total' => round($total, 2),
            
            // Unlimited details
            'unlimited_subtotal' => round($unlimited_subtotal, 2),
            'unlimited_gst_amount' => round($unlimited_gst, 2),
            'unlimited_pst_amount' => round($unlimited_pst, 2),
            'unlimited_total' => round($unlimited_total, 2),
            
            'is_inclusive' => $is_tax_inclusive
        ];

        // Get rooms for this class for location selection
        $classRooms = is_array($class->room) ? $class->room : ($class->room ? [$class->room] : []);

        return view('modules.user.subscriptions.checkout', compact('class', 'taxDetails', 'classRooms'));
    }

    public function processCheckout(Request $request, $id)
    {
        $class = MartialArtsClass::where('status', 'active')->findOrFail($id);
        $user = Auth::user();
        
        $package_type = $request->get('package_type', 'normal');
        $selected_location = $request->get('selected_location');

        $discountCoupon = null;
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\DiscountCoupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid()) {
                $discountCoupon = $coupon;
            } else {
                return redirect()->back()->with(['status' => 'failed', 'message' => 'Invalid or expired coupon code.']);
            }
        }

        $gstSetting = \App\Models\SiteSetting::where('key', 'gst_percentage')->first();
        $gstRate = $gstSetting ? floatval($gstSetting->value) : 0;
        $pstRate = 0; // No PST for schedule-sessions
        $totalTaxRate = $gstRate / 100;
        
        $price = $class->price;
        if ($package_type === 'unlimited') {
            $price = $class->unlimited_price ?? $class->price;
        } else if ($package_type === 'day_pass') {
            $price = $class->day_pass_price ?? 0;
        } else if ($package_type === 'weekly_pass') {
            $price = $class->weekly_pass_price ?? 0;
        }

        $is_tax_inclusive = $class->is_tax_inclusive;

        if ($is_tax_inclusive) {
            $totalAmount = $price;
        } else {
            $subtotal = $price;
            $gstAmount = $subtotal * ($gstRate / 100);
            $pstAmount = 0;
            $totalAmount = $subtotal + $gstAmount;
        }

        try {
            $session = $this->stripeService->createCheckoutSession(
                $user,
                $class,
                $totalAmount,
                route('user.subscription.success'),
                route('user.schedule-session-detail', $class->id),
                $discountCoupon,
                $package_type,
                $selected_location
            );

            // Store coupon code in session for success callback tracking if needed
            if ($discountCoupon) {
                session(['pending_subscription_coupon' => $discountCoupon->id]);
            }

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
            $package_type = $session->metadata->package_type ?? 'normal';
            $selected_location = $session->metadata->selected_location ?? null;
            $isOneTime = isset($session->metadata->is_one_time) && $session->metadata->is_one_time == 'true';
            
            $class = MartialArtsClass::find($classId);

            if ($isOneTime) {
                // One-time payment (Passes)
                $subscription = Subscription::create([
                    'user_id' => $userId,
                    'martial_arts_class_id' => $classId,
                    'package_type' => $package_type,
                    'selected_location' => $selected_location,
                    'stripe_customer_id' => $session->customer ?? 'one_time',
                    'stripe_subscription_id' => 'payment_' . $session->payment_intent,
                    'status' => 'active',
                    'next_payment_date' => null,
                ]);
                $invoiceUrl = null; // No hosted invoice URL for standard payment intents usually
            } else {
                // Recurring Subscription
                $subscription = Subscription::updateOrCreate(
                    ['stripe_subscription_id' => $session->subscription->id],
                    [
                        'user_id' => $userId,
                        'martial_arts_class_id' => $classId,
                        'package_type' => $package_type,
                        'selected_location' => $selected_location,
                        'stripe_customer_id' => $session->customer,
                        'status' => $session->subscription->status,
                        'next_payment_date' => Carbon::parse($session->subscription->current_period_end),
                    ]
                );
                $invoiceUrl = $session->subscription->latest_invoice->hosted_invoice_url ?? null;
            }

            // Record Payment
            $amountTotal = $session->amount_total / 100;
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $amountTotal,
                'stripe_payment_id' => $session->payment_intent ?? ($session->subscription->latest_invoice->payment_intent ?? null),
                'stripe_invoice_url' => $invoiceUrl,
                'status' => 'succeeded',
            ]);

            // Increment coupon usage
            if (session()->has('pending_subscription_coupon')) {
                \App\Models\DiscountCoupon::where('id', session('pending_subscription_coupon'))->increment('used_count');
                session()->forget('pending_subscription_coupon');
            }

            return redirect()->route('user.subscription.list')
                ->with(['status' => 'success', 'message' => 'Subscription successful!']);
        } catch (\Exception $e) {
            return redirect()->route('user.schedule-session-list')->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function cancel(Request $request, $id)
    {
        $subscription = Subscription::where('user_id', Auth::id())->findOrFail($id);

        try {
            // Check if request already exists
            $existing = \App\Models\CancellationRequest::where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                return redirect()->back()->with(['status' => 'failed', 'message' => 'Cancellation request is already pending.']);
            }

            \App\Models\CancellationRequest::create([
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'status' => 'pending',
                'requested_at' => now(),
                'notes' => $request->notes,
            ]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Cancellation request submitted. Our team will review it shortly.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function downloadInvoice($id)
    {
        $payment = \App\Models\SubscriptionPayment::with(['subscription.martialArtsClass', 'subscription.user'])->findOrFail($id);
        
        // Ensure user owns this payment
        if ($payment->subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $class = $payment->subscription->martialArtsClass;
        
        $gstSetting = \App\Models\SiteSetting::where('key', 'gst_percentage')->first();
        $pstSetting = \App\Models\SiteSetting::where('key', 'pst_percentage')->first();
        $gstRate = $gstSetting ? floatval($gstSetting->value) : 0;
        $pstRate = $pstSetting ? floatval($pstSetting->value) : 0;
        $totalTaxRate = ($gstRate + $pstRate) / 100;
        
        // Total amount actually paid
        $totalPaid = $payment->amount;

        // Work backwards to find subtotal if tax inclusive, 
        // Or if it was exclusive, totalAmount was already subtotal + tax
        // Since we passed $totalAmount to Stripe, $payment->amount is exactly $totalAmount we calculated.
        $subtotal = $totalPaid / (1 + $totalTaxRate);
        $gstAmount = $subtotal * ($gstRate / 100);
        $pstAmount = $subtotal * ($pstRate / 100);

        $taxDetails = [
            'subtotal' => round($subtotal, 2),
            'gst_rate' => $gstRate,
            'pst_rate' => $pstRate,
            'gst_amount' => round($gstAmount, 2),
            'pst_amount' => round($pstAmount, 2),
            'total' => round($totalPaid, 2),
            'is_inclusive' => $class->is_tax_inclusive
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('modules.user.subscriptions.invoice_pdf', compact('payment', 'class', 'taxDetails'));
        return $pdf->download('Invoice_' . $payment->id . '.pdf');
    }
}

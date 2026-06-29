<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MartialArtsClass;
use App\Models\Subscription;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSubscriptionController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'martialArtsClass', 'payments']);

        // Filter by Status
        if ($request->filled('status')) {
            $query->filterStatus($request->status);
        }

        // Filter by Package Type
        if ($request->filled('package')) {
            $query->where('package_type', $request->package);
        }

        // Filter by Date
        if ($request->filled('date')) {
            $dates = explode(' to ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        // Filter by Schedule (MartialArtsClass)
        if ($request->class_id) {
            $query->where('martial_arts_class_id', $request->class_id);
        }

        // Search by User Name
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(20);
        $classes = MartialArtsClass::orderBy('name')->get();

        return view('modules.admin.subscriptions.index', compact('subscriptions', 'classes'));
    }

    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);

        try {
            $this->stripeService->cancelSubscription($subscription->stripe_subscription_id);
            $subscription->update([
                'status' => 'canceled',
                'ends_at' => Carbon::now(),
            ]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Subscription canceled successfully by Admin.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }
}

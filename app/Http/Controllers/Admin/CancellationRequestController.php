<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellationRequest;
use Illuminate\Http\Request;
use App\Services\Payments\StripeService;

class CancellationRequestController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index(Request $request)
    {
        $query = CancellationRequest::with(['user', 'subscription.martialArtsClass']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('modules.admin.cancellation-requests.list', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,rejected',
            'admin_notes' => 'nullable|string',
            'effective_cancellation_date' => 'nullable|date',
        ]);

        $cancelReq = CancellationRequest::findOrFail($id);

        try {
            if ($request->status === 'completed' && $cancelReq->status !== 'completed') {
                // If it's being approved, cancel in Stripe
                $subscription = $cancelReq->subscription;
                
                if ($subscription && $subscription->stripe_subscription_id) {
                    try {
                        $this->stripeService->cancelSubscription($subscription->stripe_subscription_id);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Stripe cancellation failed: ' . $e->getMessage());
                    }
                    $subscription->update([
                        'status' => 'canceled',
                        'ends_at' => \Carbon\Carbon::now(),
                    ]);
                }
            }

            $cancelReq->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'effective_cancellation_date' => $request->effective_cancellation_date,
            ]);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Cancellation request updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}

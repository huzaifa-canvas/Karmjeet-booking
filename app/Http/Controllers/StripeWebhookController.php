<?php

namespace App\Http\Controllers;

use App\Models\MartialArtsClass;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook events.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        // If webhook secret is configured, verify the signature
        if ($webhookSecret) {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                Log::error('Stripe Webhook Signature Verification Failed: ' . $e->getMessage());
                return response('Invalid signature', 400);
            } catch (\Exception $e) {
                Log::error('Stripe Webhook Error: ' . $e->getMessage());
                return response('Webhook error', 400);
            }
        } else {
            // No webhook secret configured, parse the event from payload directly
            // (Not recommended for production, but works for initial setup)
            $event = json_decode($payload);
            Log::warning('Stripe Webhook: No STRIPE_WEBHOOK_SECRET configured. Signature verification skipped.');
        }

        Log::info('Stripe Webhook Received: ' . $event->type, ['event_id' => $event->id ?? null]);

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            default:
                Log::info('Stripe Webhook: Unhandled event type - ' . $event->type);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Handle checkout.session.completed event.
     * This is the main event that creates/updates subscriptions when user pays.
     */
    protected function handleCheckoutSessionCompleted($session)
    {
        try {
            $metadata = $session->metadata ?? null;

            if (!$metadata || !isset($metadata->user_id) || !isset($metadata->martial_arts_class_id)) {
                Log::warning('Stripe Webhook: checkout.session.completed missing required metadata', [
                    'session_id' => $session->id,
                ]);
                return;
            }

            $userId = $metadata->user_id;
            $classId = $metadata->martial_arts_class_id;
            $package_type = $metadata->package_type ?? 'normal';
            $selected_location = $metadata->selected_location ?? null;
            $isOneTime = isset($metadata->is_one_time) && $metadata->is_one_time == 'true';

            $class = MartialArtsClass::find($classId);
            if (!$class) {
                Log::error('Stripe Webhook: MartialArtsClass not found', ['class_id' => $classId]);
                return;
            }

            if ($isOneTime) {
                // One-time payment (Day Pass / Weekly Pass)
                $stripeId = 'payment_' . ($session->payment_intent ?? $session->id);

                // Duplicate protection: check if already exists
                $existing = Subscription::where('stripe_subscription_id', $stripeId)->first();
                if ($existing) {
                    Log::info('Stripe Webhook: Subscription already exists (one-time)', [
                        'stripe_id' => $stripeId,
                        'subscription_id' => $existing->id,
                    ]);
                    return;
                }

                $subscription = Subscription::create([
                    'user_id' => $userId,
                    'martial_arts_class_id' => $classId,
                    'package_type' => $package_type,
                    'selected_location' => $selected_location,
                    'stripe_customer_id' => $session->customer ?? 'one_time',
                    'stripe_subscription_id' => $stripeId,
                    'status' => 'active',
                    'next_payment_date' => null,
                ]);

                $invoiceUrl = null;
            } else {
                // Recurring Subscription
                $stripeSubId = $session->subscription ?? null;

                if (!$stripeSubId) {
                    Log::error('Stripe Webhook: No subscription ID in checkout session for recurring', [
                        'session_id' => $session->id,
                    ]);
                    return;
                }

                // Fetch full subscription data from Stripe
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $stripeSub = $stripe->subscriptions->retrieve($stripeSubId, ['expand' => ['latest_invoice']]);

                // Duplicate protection: updateOrCreate
                $subscription = Subscription::updateOrCreate(
                    ['stripe_subscription_id' => $stripeSubId],
                    [
                        'user_id' => $userId,
                        'martial_arts_class_id' => $classId,
                        'package_type' => $package_type,
                        'selected_location' => $selected_location,
                        'stripe_customer_id' => $session->customer,
                        'status' => $stripeSub->status,
                        'next_payment_date' => Carbon::createFromTimestamp($stripeSub->current_period_end),
                    ]
                );

                $invoiceUrl = $stripeSub->latest_invoice->hosted_invoice_url ?? null;
            }

            // Record Payment (with duplicate protection)
            $amountTotal = ($session->amount_total ?? 0) / 100;
            $paymentIntentId = $session->payment_intent ?? null;

            // For recurring, payment_intent is on the invoice
            if (!$paymentIntentId && !$isOneTime && isset($stripeSub)) {
                $paymentIntentId = $stripeSub->latest_invoice->payment_intent ?? null;
            }

            // Check if payment record already exists
            $existingPayment = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->where('stripe_payment_id', $paymentIntentId)
                ->first();

            if (!$existingPayment && $amountTotal > 0) {
                SubscriptionPayment::create([
                    'subscription_id' => $subscription->id,
                    'amount' => $amountTotal,
                    'stripe_payment_id' => $paymentIntentId,
                    'stripe_invoice_url' => $invoiceUrl ?? null,
                    'status' => 'succeeded',
                ]);
            }

            // Send Subscription Confirmation Emails
            try {
                \App\Services\MailNotificationService::sendSubscriptionConfirmation($subscription->id);
            } catch (\Exception $e) {
                Log::error('Stripe Webhook: Failed to send confirmation email', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('Stripe Webhook: Subscription created/updated successfully', [
                'subscription_id' => $subscription->id,
                'user_id' => $userId,
                'class_id' => $classId,
                'package_type' => $package_type,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook: Error handling checkout.session.completed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Handle invoice.payment_succeeded for recurring subscription renewals.
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        try {
            $stripeSubId = $invoice->subscription ?? null;

            if (!$stripeSubId) {
                return; // Not a subscription invoice, skip
            }

            $subscription = Subscription::where('stripe_subscription_id', $stripeSubId)->first();

            if (!$subscription) {
                Log::info('Stripe Webhook: invoice.payment_succeeded - No local subscription found', [
                    'stripe_subscription_id' => $stripeSubId,
                ]);
                return;
            }

            // Record payment (duplicate check)
            $paymentIntentId = $invoice->payment_intent ?? null;
            $existingPayment = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->where('stripe_payment_id', $paymentIntentId)
                ->first();

            if (!$existingPayment) {
                $amountPaid = ($invoice->amount_paid ?? 0) / 100;

                SubscriptionPayment::create([
                    'subscription_id' => $subscription->id,
                    'amount' => $amountPaid,
                    'stripe_payment_id' => $paymentIntentId,
                    'stripe_invoice_url' => $invoice->hosted_invoice_url ?? null,
                    'status' => 'succeeded',
                ]);

                Log::info('Stripe Webhook: Recurring payment recorded', [
                    'subscription_id' => $subscription->id,
                    'amount' => $amountPaid,
                ]);
            }

            // Update next_payment_date from Stripe subscription
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $stripeSub = $stripe->subscriptions->retrieve($stripeSubId);

            $subscription->update([
                'status' => $stripeSub->status,
                'next_payment_date' => Carbon::createFromTimestamp($stripeSub->current_period_end),
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook: Error handling invoice.payment_succeeded', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle customer.subscription.updated (status changes, etc.)
     */
    protected function handleSubscriptionUpdated($stripeSubscription)
    {
        try {
            $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

            if (!$subscription) {
                return;
            }

            $subscription->update([
                'status' => $stripeSubscription->status,
                'next_payment_date' => $stripeSubscription->current_period_end
                    ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                    : null,
            ]);

            Log::info('Stripe Webhook: Subscription updated', [
                'subscription_id' => $subscription->id,
                'new_status' => $stripeSubscription->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook: Error handling subscription.updated', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle customer.subscription.deleted (cancellation)
     */
    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        try {
            $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

            if (!$subscription) {
                return;
            }

            $subscription->update([
                'status' => 'canceled',
                'ends_at' => $stripeSubscription->current_period_end
                    ? Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                    : now(),
            ]);

            Log::info('Stripe Webhook: Subscription canceled', [
                'subscription_id' => $subscription->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook: Error handling subscription.deleted', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

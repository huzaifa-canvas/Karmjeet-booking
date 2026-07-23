<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailNotificationService
{
    /**
     * Get Admin Notification Email setting or fallback to default admin email.
     */
    public static function getAdminEmail()
    {
        try {
            $setting = SiteSetting::where('key', 'admin_notification_email')->first();
            if ($setting && !empty(trim($setting->value))) {
                return trim($setting->value);
            }

            $adminUser = User::where('user_role', 'admin')->first();
            if ($adminUser && !empty($adminUser->email)) {
                return $adminUser->email;
            }

            return config('mail.from.address');
        } catch (\Throwable $e) {
            Log::error('Error fetching admin email setting: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send Order Confirmation email to Customer and Admin notification.
     */
    public static function sendOrderConfirmation($orderId)
    {
        try {
            if (!$orderId) {
                return;
            }

            $order = Order::with(['items', 'user'])->find($orderId);
            if (!$order) {
                return;
            }

            $customerEmail = $order->user->email ?? $order->guest_email ?? null;
            $adminEmail = self::getAdminEmail();

            // 1. Send Customer Order Confirmation Email
            if ($customerEmail) {
                try {
                    Mail::send('emails.order_confirmation', ['order' => $order, 'recipientType' => 'customer'], function ($message) use ($customerEmail, $order) {
                        $message->to($customerEmail)
                                ->subject('Order Confirmation - Order #' . $order->order_number);
                    });
                } catch (\Throwable $e) {
                    Log::warning('SMTP/Mail issue sending customer order email (Order #' . $order->order_number . '): ' . $e->getMessage());
                }
            }

            // 2. Send Admin New Order Notification Email
            if ($adminEmail) {
                try {
                    Mail::send('emails.order_confirmation', ['order' => $order, 'recipientType' => 'admin'], function ($message) use ($adminEmail, $order) {
                        $message->to($adminEmail)
                                ->subject('[New Order Alert] Order #' . $order->order_number);
                    });
                } catch (\Throwable $e) {
                    Log::warning('SMTP/Mail issue sending admin order email (Order #' . $order->order_number . '): ' . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('General error in sendOrderConfirmation: ' . $e->getMessage());
        }
    }

    /**
     * Send Subscription Confirmation email to Customer and Admin notification.
     */
    public static function sendSubscriptionConfirmation($subscriptionId)
    {
        try {
            if (!$subscriptionId) {
                return;
            }

            $subscription = Subscription::with(['user', 'martialArtsClass', 'payments'])->find($subscriptionId);
            if (!$subscription) {
                return;
            }

            $customerEmail = $subscription->user->email ?? null;
            $adminEmail = self::getAdminEmail();
            $className = $subscription->martialArtsClass->name ?? 'Membership Plan';

            // 1. Send Customer Subscription Confirmation Email
            if ($customerEmail) {
                try {
                    Mail::send('emails.subscription_confirmation', ['subscription' => $subscription, 'recipientType' => 'customer'], function ($message) use ($customerEmail, $className) {
                        $message->to($customerEmail)
                                ->subject('Subscription Confirmation - ' . $className);
                    });
                } catch (\Throwable $e) {
                    Log::warning('SMTP/Mail issue sending customer subscription email: ' . $e->getMessage());
                }
            }

            // 2. Send Admin New Subscription Notification Email
            if ($adminEmail) {
                try {
                    Mail::send('emails.subscription_confirmation', ['subscription' => $subscription, 'recipientType' => 'admin'], function ($message) use ($adminEmail, $subscription, $className) {
                        $customerName = $subscription->user->name ?? 'Customer';
                        $message->to($adminEmail)
                                ->subject('[New Subscription Alert] ' . $customerName . ' - ' . $className);
                    });
                } catch (\Throwable $e) {
                    Log::warning('SMTP/Mail issue sending admin subscription email: ' . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('General error in sendSubscriptionConfirmation: ' . $e->getMessage());
        }
    }
}

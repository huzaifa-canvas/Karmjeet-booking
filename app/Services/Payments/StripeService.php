<?php

namespace App\Services\Payments;

use App\Models\Session;
use App\Models\SessionPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\PaymentFailed;
use App\Models\Coaching;
use Illuminate\Support\Facades\Mail;

use Stripe;

class StripeService
{
    public $stripe;

    public function __construct() {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCustomer($request)
    {
        $customer = $this->stripe->customers->create([
            'name'      => $request->name,
            'email'     => $request->email,
        ]);

        return $customer;
    }

    public function createCheckoutSession($user, $class, $totalAmount, $successUrl, $cancelUrl, $discountCoupon = null, $package_type = 'normal')
    {
        // 1. Ensure Product exists
        if (!$class->stripe_product_id) {
            $product = $this->stripe->products->create(['name' => $class->name]);
            $class->update(['stripe_product_id' => $product->id]);
        }

        $unitAmount = (int)(round($totalAmount, 2) * 100);

        // Check plan interval
        $isOneTime = ($package_type === 'day_pass' || $package_type === 'weekly_pass');

        // 2. Handle Price ID (Recurring vs One-time)
        $priceId = null;

        if ($isOneTime) {
            // For one-time payments, we can just create a price on the fly or use line_item.price_data
            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $unitAmount,
                            'product' => $class->stripe_product_id,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'customer_email' => $user->email,
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'user_id' => $user->id,
                    'martial_arts_class_id' => $class->id,
                    'package_type' => $package_type,
                    'is_one_time' => 'true'
                ],
            ];
        } else {
            // Recurring Subscriptions
            $interval = 'month';
            
            $newPrice = $this->stripe->prices->create([
                'currency' => 'usd',
                'unit_amount' => $unitAmount,
                'recurring' => ['interval' => $interval],
                'product' => $class->stripe_product_id,
            ]);
            $recurringPriceId = $newPrice->id;

            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => $recurringPriceId,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'customer_email' => $user->email,
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'user_id' => $user->id,
                    'martial_arts_class_id' => $class->id,
                    'package_type' => $package_type,
                ],
            ];
        }

        // Apply Discount Coupon if provided
        if ($discountCoupon && $discountCoupon->isValid()) {
            try {
                // Check if coupon exists in Stripe by code
                $stripeCouponId = 'COUPON_' . $discountCoupon->id;
                try {
                    $this->stripe->coupons->retrieve($stripeCouponId);
                } catch (\Exception $e) {
                    // Create coupon in Stripe
                    $couponData = [
                        'id' => $stripeCouponId,
                        'name' => $discountCoupon->name ?: $discountCoupon->code,
                        'duration' => 'forever',
                    ];
                    if ($discountCoupon->type === 'percentage') {
                        $couponData['percent_off'] = $discountCoupon->value;
                    } else {
                        $couponData['amount_off'] = (int)($discountCoupon->value * 100);
                        $couponData['currency'] = 'usd';
                    }
                    $this->stripe->coupons->create($couponData);
                }

                $sessionData['discounts'] = [
                    ['coupon' => $stripeCouponId],
                ];
            } catch (\Exception $e) {
                // Proceed without discount if Stripe fails
                \Log::error('Stripe Coupon Error: ' . $e->getMessage());
            }
        }

        return $this->stripe->checkout->sessions->create($sessionData);
    }

    public function getCheckoutSession($sessionId)
    {
        return $this->stripe->checkout->sessions->retrieve($sessionId, ['expand' => ['subscription.latest_invoice']]);
    }

    public function cancelSubscription($subscriptionId)
    {
        return $this->stripe->subscriptions->cancel($subscriptionId);
    }

    public function getInvoice($invoiceId)
    {
        return $this->stripe->invoices->retrieve($invoiceId);
    }

    public function sessionCharge($amount, $token, $session)
    {
        return $this->stripe->charges->create([
            'amount' => $amount*100,
            'currency' => 'usd',
            'source' => $token,
            'description' => "Booking for ". $session,
        ]);
    }

    public function chargeCustomer($amount, $customerID, $description = "", $session)
    {
        $charges =  $this->stripe->charges->create([
            'amount' => $amount*100,
            'currency' => 'usd',
            'customer' => $customerID,
            'description' => $description,
        ]);

        if($charges->status != "succeeded"){
            SessionPayment::create([
                'user_id'          => $session->user_id,
                'session_id'       => $session->id,
                'card_holder_name' => $session->card_holder_name,
                'amount'           => $amount,
                'payment_method'   => "Stripe",
                'status'           => "succeeded",
            ]);
        } else{
            SessionPayment::create([
                'user_id'          => $session->user_id,
                'session_id'       => $session->id,
                'card_holder_name' => $session->card_holder_name,
                'amount'           => $amount,
                'payment_method'   => "Stripe",
                'status'           => "failed",
            ]);

            $coaching = Coaching::findOrFail($session->session_id);
            $link     = $this->createPaymentLink($coaching->price_id , route('payment-charge', $this->encryptData($session->id) ));
            Mail::to($session->email)->send(new PaymentFailed($session->card_holder_name, $link));
        }

        return $charges;
    }

    public function createProductOrPriceId($amount,$productName)
    {
        $productId = $this->stripe->products->create(['name' => $productName,]);
        $priceId = $this->stripe->prices->create(['currency' => 'usd', 'unit_amount' => $amount*100, 'product' => $productId->id,]);
        return ["productId" => $productId->id, "priceId" => $priceId->id];
    }

    public function updateProductOrPriceId($productId, $priceId, $productName, $amount)
    {
        if($productName){
            $product = $this->stripe->products->update($productId, ['name' => $productName,]);
            $productId = $product->id;
        }
        if($amount){
            $priceId = $this->stripe->prices->create(['currency' => 'usd', 'unit_amount' => $amount*100, 'product' => $productId,]);
            $priceId = $priceId->id;
        }
        return ["productId" => $productId, "priceId" => $priceId];
    }

    public function createPaymentLink($priceId,$redirectUrl)
    {
        $link = $this->stripe->paymentLinks->create([
                'line_items' => [['price' => $priceId, 'quantity' => 1]],
                'after_completion' => ['type' => 'redirect','redirect' => ['url' => $redirectUrl],],
            ]);

        return $link->url;
    }

    /**
     * Create a Stripe Checkout Session for one-time product payment.
     */
    public function createProductPaymentSession($user, $cartItems, $orderId, $successUrl, $cancelUrl)
    {
        $lineItems = [];
        foreach ($cartItems as $productId => $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => (int)($item['price'] * 100),
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        return $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'customer_email'       => $user->email,
            'success_url'          => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => $cancelUrl,
            'metadata'             => [
                'user_id'  => $user->id,
                'order_id' => $orderId,
            ],
        ]);
    }

    public function encryptData($data)
    {

        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';

        // Store the encryption key
        $encryption_key = "encryptionForNearyCoaching";

        // Use openssl_encrypt() function to encrypt the data
        return openssl_encrypt($data, $ciphering, $encryption_key, $options, $encryption_iv);
    }
}

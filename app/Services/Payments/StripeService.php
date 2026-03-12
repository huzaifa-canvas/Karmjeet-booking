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
        $this->stripe  = new \Stripe\StripeClient('sk_test_51MAZe4HnBjRuAp0i6FEIZsanltRn0GwMCtNOexCzqQaheGz1xuhw1iCShIywkSa3QNMtSuaWwiJqBTqb4u2JjnOb00Jm3Uhkj1');
    }

    public function createCustomer($request)
    {

        Stripe\Stripe::setApiKey('sk_test_51MAZe4HnBjRuAp0i6FEIZsanltRn0GwMCtNOexCzqQaheGz1xuhw1iCShIywkSa3QNMtSuaWwiJqBTqb4u2JjnOb00Jm3Uhkj1');
        $customer = Stripe\Customer::create(array(
            'name'      => $request->name,
            'email'     => $request->email,
            'source'    => $request->token,
          ));

        return $customer;
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

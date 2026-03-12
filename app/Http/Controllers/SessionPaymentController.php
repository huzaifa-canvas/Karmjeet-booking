<?php

namespace App\Http\Controllers;

use App\Models\SessionBooking;
use App\Models\SessionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $payments = SessionPayment::where('user_id', $userId)->get();
        // $payments = [];
        return view('modules.admin.payment.list', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $id = $this->decryptData($id);
        $session = SessionBooking::findOrFail($id);

        SessionBooking::where('id', $id)->update([
            "payment_status" => "success",
            "session_status" => "done"
        ]);

        SessionPayment::create([
            'user_id'          => $session->user_id,
            'session_id'       => $session->id,
            'card_holder_name' => $session->card_holder_name,
            'amount'           => $session->price_per_session,
            'payment_method'   => "Stripe",
            'status'           => "succeeded",
        ]);
            return redirect()->route('payment-list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SessionPayment  $sessionPayment
     * @return \Illuminate\Http\Response
     */
    public function show(SessionPayment $sessionPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionPayment  $sessionPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(SessionPayment $sessionPayment)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionPayment  $sessionPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SessionPayment $sessionPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionPayment  $sessionPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionPayment $sessionPayment)
    {
        //
    }

    public function decryptData($data)
    {
         $ciphering = "AES-128-CTR";
         // Non-NULL Initialization Vector for decryption
         $decryption_iv = '1234567891011121';
         $options = 0;
         // Store the decryption key
         $decryption_key = "encryptionForNearyCoaching";

         // Use openssl_decrypt() function to decrypt the data
        return openssl_decrypt ($data, $ciphering,$decryption_key, $options, $decryption_iv);
    }
}

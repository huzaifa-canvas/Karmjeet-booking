<?php

namespace App\Http\Controllers\ScheduleSession;
use App\Http\Controllers\Controller;

use App\Mail\ConfirmationEmail;
use App\Mail\EmailReminder;
use App\Models\ScheduleSession;
use App\Models\SessionOrder;
use App\Models\Slot;
use App\Services\Payments\StripeService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Termwind\Components\Dd;
use Stripe;

class SessionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null)
    {
        $user = Auth::user();
        $sessions = SessionOrder::select('*');

        if ($id) {
            $sessions = SessionOrder::where('user_id',$id);
        }
        elseif ($request->status) {
            $sessions = SessionOrder::where('session_status',$request->status);
        }
        elseif ($request->today == true) {
            $sessions = SessionOrder::where('date',date("Y/m/d"));
        }

        if ($user->user_role == 'coach') {
            $sessions = $sessions->where('coach_id', $user->id);
        }

        if ($user->user_role == 'user') {
            $sessions = $sessions->where('user_id', $user->id);
        }

        $sessions =  $sessions->get();
        $sessions =  [];

        return view('modules.admin.SessionBooking.list', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.SessionBooking.forms', compact('isEdit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, $slug)
    {
        try {

            $userId = Auth::user()->id;
            if(Auth::user()->user_role == "admin"){
                if ($request->user_id != "") {
                    $userId = $request->user_id;
                } else{
                    return ['status' => false, 'message' => 'Please select user first'];
                }
            }

            if(empty($request->booking_date_time)){
                return ['status' => false, 'message' => "Please select Date and slot"];
            }
            $session = ScheduleSession::where('id', $slug)->first();
            $customerId = 0;
            if($session->price_per_session != 0){
                $stripeService = new StripeService;
                $customerId = $stripeService->createCustomer($request)->id;
            }

            $temp = [];
            foreach ($request->booking_date_time as $key => $value) {

                $dateTime   = explode("=", $value);
                $times      =  explode("-", $dateTime[1]);
                $date       = $dateTime[0];
                $day        = date_format(date_create($date),"l");
                $duration   = $session->getslots->where('days', $day)->first()->duration;
                $startTime  = $times[0];
                $endTime    = $times[1];

                $data = SessionOrder::create([
                            'user_id'           => $userId,
                            'coach_id'          => $session->user_id,
                            'session_id'        => $session->id,
                            'coach_name'        => $session->coach_name,
                            'date'              => $date,
                            'start_time'        => $startTime,
                            'end_time'          => $endTime,
                            'start_end_time'    => $dateTime[1],
                            'duration'          => $duration,

                            'full_name'         => $request->name,
                            'email'             => $request->email,
                            'phone'             => $request->phone,
                            'objective'         => $request->objective,
                            'card_holder_name'  => $request->card_holder_name,
                            'price_per_session' => $session->price_per_session,
                            'payment_method'    => 'stripe',
                            'customer_id'       => $customerId,

                            'payment_status'    => ($customerId) ? 'pending' : 'free',
                            'session_status'    => 'pending',
                        ]);

                $url = createZoomMettingUrl($data)->join_url;
                addToGoogleCalendar($data);
                $temp[$key]['url'] = $url;
                $temp[$key]['date'] = $date;
                $temp[$key]['time'] = $dateTime[1];
            }


            Mail::to($request->email)->send(new ConfirmationEmail($temp, $request->name, $session->coach_name));

            return ['status' => true, 'message' => 'Sessions Booking successfully'];

        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SessionOrder  $sessionBooking
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Session = SessionOrder::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.SessionBooking.forms', compact('id', 'Session', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionOrder  $sessionBooking
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $session    = SessionOrder::where('id', $id)->where('session_status','pending')->first();
        if (!$session) {
            abort(404);
        }

        $coaching   = ScheduleSession::findOrFail($session->session_id)->getslots->where('days', $session->date->format("l"))->first();
        $slots      = $coaching->session;
        $booked     = SessionOrder::where('session_id',$coaching->id)->where('date', $session->date)->pluck('start_end_time')->toArray();
        $diff_mins = $this->getTimeDiffer($session->date, $session->start_time);
        $remainLessThan24h = ($diff_mins <= 1440) ? true : false;

        $isEdit = true;
        return view('modules.admin.SessionBooking.forms', compact('id', 'session', 'slots', 'booked', 'isEdit', 'remainLessThan24h'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionOrder  $sessionBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => ['required'],
            'slot' => ['required'],
        ]);

        $times      =   explode("-", $request->slot);
        $date       =   $request->date;
        $startTime  =   $times[0];
        $endTime    =   $times[1];
        $session    =  SessionOrder::where('id', $id)->first();
        SessionOrder::where('id', $id)->update([
            'date'              => $date,
            'start_time'        => $startTime,
            'end_time'          => $endTime,
            'start_end_time'    => $request->slot
        ]);

        $diff_mins = $this->getTimeDiffer($session->date, $session->start_time);

        if($diff_mins <= 1435){
            // $StripeService = new StripeService();
            // $charge = $StripeService->chargeCustomer(75, $session->customer_id, '',$session);
        }
        return redirect()->back()->with(['status' => 'success', 'message' => "Session reschedule successfully" ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionOrder  $sessionBooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionOrder $sessionBooking)
    {
        //
    }

    public function paymentCharge(Request $request)
    {
        try {

            $session = SessionOrder::where('id', $request->id)->first();

            if (Auth::user()->user_role == "admin") {
                SessionOrder::where('id', $request->id)->update([
                    "payment_status" => $request->status,
                    "session_status" => $request->status
                ]);
                return ["status" => true , "message" => "Session done successfully"];
            } else{
                return ["status" => false , "message" => "Something Went Wrong"];
            }

            return true;

            $stripeService = new StripeService;

            if ($request->status == "done" && Auth::user()->user_role == "admin") {
                if($session->price_per_session && $session->customer_id) {

                    $charge = $stripeService->chargeCustomer($session->price_per_session, $session->customer_id,'', $session);
                    if ($charge->status == "succeeded") {
                        SessionOrder::where('id', $request->id)->update([
                            "payment_status" => "success",
                            "session_status" => "done"
                        ]);
                        return ["status" => true , "message" => "Payment charge successfully"];
                    }
                    else {
                        SessionOrder::where('id', $request->id)->update([
                            "payment_status" => "failed",
                        ]);
                        return ["status" => false , "message" => $charge->failure_message];
                    }

                } else{
                    SessionOrder::where('id', $request->id)->update([
                        "payment_status" => "free",
                        "session_status" => "done"
                    ]);
                    return ["status" => true , "message" => "Session done successfully"];
                }
            }

            else if($session->customer_id && $request->status == "no-show" && Auth::user()->user_role == "admin") {

                $charge = $stripeService->chargeCustomer(100, $session->customer_id,'', $session);
                if ($charge->status == "succeeded") {
                    SessionOrder::where('id', $request->id)->update([
                        "session_status" => "no-show"
                    ]);
                    return ["status" => true , "message" => "No Show Penalty $100 charge successfully"];
                } else{
                    return ["status" => false , "message" => $charge->failure_message];
                }
            }

            else if($session->customer_id && $request->status == "canceled") {

                $diff_mins = $this->getTimeDiffer($session->date, $session->start_time);

                if($diff_mins <= 1435){

                    $charge = $stripeService->chargeCustomer(75, $session->customer_id,'', $session);
                    if ($charge->status == "succeeded") {
                        SessionOrder::where('id', $request->id)->update([
                            "session_status" => "canceled"
                        ]);
                        return ["status" => true , "message" => "Session canceled 24 hours in advance Penalty $75 charge successfully"];
                    } else {
                        return ["status" => false , "message" => $charge->failure_message];
                    }
                }
                else{
                    SessionOrder::where('id', $request->id)->update([
                        "session_status" => "canceled"
                    ]);
                    return ["status" => true , "message" => "Session canceled successfully"];
                }
            }
            else{
                return ["status" => false , "message" => "Something Went Wrong"];
            }

        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
        // status: "succeeded"
        // failure_message: null
    }

    public function getSlots(Request $request)
    {
        try {

            $slots  = Slot::where('coaching_id', $request->id)->where('days', $request->weekday)->first();
            $booked = SessionOrder::where('session_id',$slots->coaching_id)->where('date', $request->date)->pluck('start_end_time');
            return ["slots" => $slots->session, "booked" => $booked];

        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function getTimeDiffer($date, $time)
    {
        $date = date('Y-m-d', strtotime($date))." ".$time;

        $start_date = new DateTime();
        $end_date = new DateTime($date);
        return round(abs($end_date->getTimestamp() - $start_date->getTimestamp()) / 60);
    }
}

<?php

namespace App\Http\Controllers\OnDemandSession;

use App\Http\Controllers\Controller;
use App\Models\Coaching;
use App\Models\ScheduleSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\SessionBooking;
use App\Models\Sessionclass;
use Illuminate\Support\Facades\Auth;
use App\Models\Slot;
use DateTime;
use App\Services\Payments\StripeService;

class UserScreenController extends Controller
{


    function class() {
        $classes = Sessionclass::whereStatus(1)->get();
        return view('modules.user.on-demand-session.class', compact('classes'));
    }

    function subjects($slug) {
        $sessionClass = Sessionclass::whereSlug($slug)->firstOrFail();
        $subject_id = Coaching::whereStatus(1)->whereClass_id($sessionClass->id)->pluck('subject_id');
        $subjects = Subject::whereStatus(1)->whereIn('id',$subject_id)->get();
        // dd($subjects);
        return view('modules.user.on-demand-session.subjects', compact('subjects'));
    }

    function courses($slug) {
        $subject = Subject::whereSlug($slug)->firstOrFail();
        $courses = Coaching::whereStatus(1)->whereSubject_id($subject->id)->get();
        return view('modules.user.on-demand-session.course', compact('courses', 'subject'));
    }

    function courseDetail($slug) {
        $course = Coaching::whereSlug($slug)->firstOrFail();
        $randomCourses = Coaching::whereStatus(1)->where('id', "!=", $course->id)->whereSubject_id($course->getsubject?->id)->inRandomOrder()->limit(4)->get();
        $subjects = Subject::whereStatus(1)->inRandomOrder()->limit(4)->get();
        return view('modules.user.on-demand-session.detail', compact( 'course', 'randomCourses', 'subjects'));
    }

    public function getSlots(Request $request)
    {
        // return $request->all();
        try {
            $slots  = [];
            $booked = [];
            if ($request->session_type == 'on-demand') {
                $session = ScheduleSession::whereId($request->id)->whereStatus(1)->first();
                $slots  = $this->getServiceScheduleSlots($session->duration, $session->start_time, $session->end_time);
                $booked = SessionBooking::where('session_type','on-demand')->where('coach_id', $request->teacher)->where('session_id',$request->id)->where('date', $request->date)->pluck('start_end_time');

            } else if($request->session_type == 'regular'){
                $slots  = Slot::where('coaching_id', $request->id)->where('days', $request->weekday)->first();
                $booked = SessionBooking::where('session_type','regular')->where('session_id',$slots->coaching_id)->where('date', $request->date)->pluck('start_end_time');
                $slots  = $slots->session;
            }
            return ["slots" => $slots, "booked" => $booked];

        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    function getServiceScheduleSlots($duration, $start,$end) {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $start_time = $start->format('H:i');
        $end_time = $end->format('H:i');
        $i=0;
        while(strtotime($start_time) <= strtotime($end_time)){
            $start = $start_time;
            $end = date('H:i',strtotime('+'.$duration.' minutes',strtotime($start_time)));
            $start_time = date('H:i',strtotime('+'.$duration.' minutes',strtotime($start_time)));
            $i++;
            if(strtotime($start_time) <= strtotime($end_time)){
                $time[$i] = $start.'-'.$end;
                // $time[$i]['start'] = $start;
                // $time[$i]['end'] = $end;
                // $time[$i]['isBooked'] = 'false';

            }
        }
        return $time;
    }

    public function courseBooking(Request $request, $slug)
    {
        try {

            $userId = Auth::user()->id;

            if(empty($request->booking_date_time)){
                return ['status' => false, 'message' => "Please select Date and slot"];
            }

            // return $request->all();
            $stripeService = new StripeService;

            if ($request->session_type == 'on-demand') {
                $session = ScheduleSession::where('id', $slug)->first();
                $totalPrice = $session->plan_price;
                $charges = $stripeService->sessionCharge($session->plan_price, $request->stripeToken, $session->title);

                if($charges->status != "succeeded"){
                    return ['status' => false, 'message' => "Payment failed please try again"];
                }

                $customerId = 0;
                $temp = [];
                foreach ($request->booking_date_time as $key => $value) {

                    $dateTime   = explode("=", $value);
                    $times      =  explode("-", $dateTime[1]);
                    $date       = $dateTime[0];
                    $day        = date_format(date_create($date),"l");
                    $duration   = $session->duration;
                    $startTime  = $times[0];
                    $endTime    = $times[1];

                    $data = SessionBooking::create([
                        'user_id'           => $userId,
                        'coach_id'          => $request->teacher[$key],
                        'session_id'        => $session?->id,
                        'coach_name'        => $session?->getUser($request->teacher[$key])?->name,
                        'session_type'      => $request->session_type,

                        'date'              => $date,
                        'start_time'        => $startTime,
                        'end_time'          => $endTime,
                        'start_end_time'    => $dateTime[1],
                        'duration'          => $duration,

                        'full_name'         => $request->name,
                        'email'             => $request->email,
                        'phone'             => $request->phone,
                        'objective'         => $request->note,
                        'card_holder_name'  => $request?->card_holder_name,
                        'price_per_session' => $totalPrice,
                        'payment_method'    => 'stripe',
                        'customer_id'       => $customerId,

                        'payment_status'    => ($customerId) ? 'pending' : 'free',
                        'session_status'    => 'pending',
                    ]);
                }

            } else {
                $session = Coaching::where('slug', $slug)->first();
                $totalPrice = $session->price_per_session * count($request->booking_date_time);
                $charges = $stripeService->sessionCharge($totalPrice, $request->stripeToken, $session->title);

                if($charges->status != "succeeded"){
                    return ['status' => false, 'message' => "Payment failed please try again"];
                }

                // if($session->price_per_session != 0){
                    //     $stripeService = new StripeService;
                    //     $customerId = $stripeService->createCustomer($request)->id;
                 // }

                $customerId = 0;
                $temp = [];
                foreach ($request->booking_date_time as $key => $value) {

                    $dateTime   = explode("=", $value);
                    $times      =  explode("-", $dateTime[1]);
                    $date       = $dateTime[0];
                    $day        = date_format(date_create($date),"l");
                    $duration   = $session->getslots->where('days', $day)->first()->duration;
                    $startTime  = $times[0];
                    $endTime    = $times[1];

                    $data = SessionBooking::create([
                        'user_id'           => $userId,
                        'coach_id'          => $session->user_id,
                        'session_id'        => $session->id,
                        'coach_name'        => $session->coach_name,
                        'session_type'      => $request->session_type,

                        'date'              => $date,
                        'start_time'        => $startTime,
                        'end_time'          => $endTime,
                        'start_end_time'    => $dateTime[1],
                        'duration'          => $duration,

                        'full_name'         => $request->name,
                        'email'             => $request->email,
                        'phone'             => $request->phone,
                        'objective'         => $request->note,
                        'card_holder_name'  => $request->card_holder_name,
                        'price_per_session' => $session->price_per_session,
                        'payment_method'    => 'stripe',
                        'customer_id'       => $customerId,

                        'payment_status'    => ($customerId) ? 'pending' : 'free',
                        'session_status'    => 'pending',
                    ]);
                }
            }

            return ['status' => true, 'message' => 'Sessions Booking successfully'];

                //code...
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function regularList(Request $request, $id = null)
    {
        $sessions =  $this->orderList($request, $id, 'regular');
        return view('modules.admin.SessionBooking.list', compact('sessions'));
    }
    public function ondemindList(Request $request, $id = null)
    {
        $sessions =  $this->orderList($request, $id, 'on-demand');
        return view('modules.admin.SessionBooking.list', compact('sessions'));
    }

    function orderList($request, $id = null, $type) {
        $user = Auth::user();
        $sessions = SessionBooking::select('*');

        if ($id) {
            $sessions = SessionBooking::where('user_id',$id);
        }
        elseif ($request->status) {
            $sessions = SessionBooking::where('session_status',$request->status);
        }
        elseif ($request->today == true) {
            $sessions = SessionBooking::where('date',date("Y/m/d"));
        }

        if ($user->user_role == 'coach') {
            $sessions = $sessions->where('coach_id', $user->id);
        }

        if ($user->user_role == 'user') {
            $sessions = $sessions->where('user_id', $user->id);
        }

        if ($request->session != "" && is_numeric($request->session)) {
            $sessions = $sessions->where('session_id', $request->session);
        }

        return $sessions->where('session_type', $type)->get();
    }


}

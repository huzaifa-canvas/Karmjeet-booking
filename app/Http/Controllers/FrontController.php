<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Coaching;
use App\Models\Courses;
use App\Models\PagesContent;
use App\Models\Poadcast;
use App\Models\SessionBooking;
use App\Models\SessionPayment;
use App\Models\User;
use App\Services\Payments\StripeService;
use Google\Service\Classroom\Course;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;

class FrontController extends Controller
{

    public function home()
    {
        $sessions = Coaching::where('status',1)->inRandomOrder()->limit(6)->get();
        return view('modules.front.home', compact('sessions',));
    }

    public function pages($slug)
    {
        $id = PagesContent::where('slug', $slug)->first()->id;
        $content = PagesContent::findOrFail($id);
        return view('modules.front.pages', compact('content'));
    }

    public function coaching()
    {
        $sessions = Coaching::where('status',1)->get();
        return view('modules.front.coaching', compact('sessions'));
    }

    public function sessionDetail($slug)
    {
        $id = Coaching::where('slug', $slug)->first();
        $id = $id->id;
        $session = Coaching::findOrFail($id);
        $sessions = Coaching::where('status',1)->where('id', "!=", $id)->get();

        return view('modules.front.session-detail', compact('session', 'sessions'));
    }

    public function sessionBooking($slug)
    {
        $id = Coaching::where('slug', $slug)->first();
        $id = $id->id;
        $session = Coaching::findOrFail($id);
        $users = (Auth::user()->user_role == "admin") ? User::where('user_role', 'user')->get() : [];
        return view('modules.front.session-booking', compact('session', 'users'));
    }

    public function sessionCreate(Request $request, $slug)
    {
        try {

            $userId = Auth::user()->id;
            $session = Coaching::where('slug', $slug)->first();
            $customerId = 0;
            if($session->price_per_session != 0){
                $customerId = StripeService::createCustomer($request)->id;
            }


            foreach ($request->booking_date_time as $key => $value) {

                $dateTime   = explode("=", $value);
                $times      =  explode("-", $dateTime[1]);
                $date       = $dateTime[0];
                // $date       = date_create($date);
                // $date       = date_format($date,"Y/m/d H:i:s");
                $startTime  = $times[0];
                $endTime    = $times[1];

                SessionBooking::create([
                    'user_id'           => $userId,
                    'session_id'        => $session->id,
                    'date'              => $date,
                    'start_time'        => $startTime,
                    'end_time'          => $endTime,
                    'start_end_time'    => $dateTime[1],
                    'duration'          => $session->duration,

                    'full_name'         => $request->name,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'objective'         => $request->objective,
                    'card_holder_name'  => $request->card_holder_name,
                    'price_per_session' => $session->price_per_session,
                    'payment_method'    => 'stripe',
                    'customer_id'       => $customerId,

                    'payment_status'    => 'pending' ,
                    'session_status'    => 'pending' ,
                ]);
            }

            return ['status' => true, 'message' => 'Sessions Booking successfully'];

                //code...
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function getSlots(Request $request)
    {
        $slots  = Coaching::findOrFail($request->id);
        $booked = SessionBooking::select(['start_end_time'])->where('session_id',$slots->id)->where('date', $request->date)->pluck('start_end_time');
        return ["slots" => json_decode($slots->session), "booked" => $booked];
    }

    public function blog()
    {
        $blogs = Blog::where('status',1)->get();
        return view('modules.front.blog.index', compact('blogs'));
    }

    public function blogDetail($slug)
    {
        $id     = Blog::where('slug', $slug)->first()->id;
        $blog   = Blog::findOrFail($id);
        $blogs  = Blog::where('status',1)->where('id', "!=", $id)->get();

        return view('modules.front.blog.detail', compact('blog','blogs'));
    }

    public function poadcast()
    {
        $poadcasts = Poadcast::where('status',1)->get();
        return view('modules.front.poadcast.index', compact('poadcasts'));
    }

    public function poadcastDetail($slug)
    {
        $id         = Poadcast::where('slug', $slug)->first()->id;
        $poadcast   = Poadcast::findOrFail($id);
        $poadcasts  = Poadcast::where('status',1)->where('id', "!=", $id)->get();

        return view('modules.front.poadcast.detail', compact('poadcast', 'poadcasts'));
    }

    public function course()
    {
        $courses = Courses::where('status',1)->get();
        return view('modules.front.courses.index', compact('courses'));
    }

    public function courseDetail($slug)
    {
        $id         = Courses::where('slug', $slug)->first()->id;
        $course    = Courses::findOrFail($id);
        $courses   = Courses::where('status',1)->where('id', "!=", $id)->get();

        return view('modules.front.courses.detail', compact('course', 'courses'));
    }


    public function about()
    {
       return view('modules.front.about');
    }

    public function testimonial()
    {
       return view('modules.front.testimonial_new');
    }
    public function thankyou()
    {
       return view('modules.front.thankyou');
    }
}

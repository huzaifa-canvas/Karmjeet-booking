<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Coaching;
use App\Models\Courses;
use App\Models\Poadcast;
use App\Models\ScheduleSession;
use App\Models\SessionBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $user = Auth::user();
        if($user->user_role == "admin"){
            $user = User::where('user_role','user')->count();
            $teacher = User::where('user_role','coach')->count();
            $ondemand = ScheduleSession::count();
            $blog = Blog::count();
            $poadcast = Poadcast::count();
            $session = Coaching::count();
            $sessionBooking = SessionBooking::count();
            $totalearning = SessionBooking::sum('price_per_session');
            // $totalearning = SessionBooking::where('payment_status','success')->sum('price_per_session');

            $bookingCountPerMonth = SessionBooking::all()->groupBy(function($val) {
                return Carbon::parse($val->created_at)->format('YM');
            });

            $montsName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $currentYear = date('Y');

            foreach ($montsName as $key => $value) {
            $bookingChartData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->count() : 0);
            $revenueChatData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->sum('price_per_session') : 0);
            }

            // $curentMonthEarning = SessionBooking::where('payment_status','success')->whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            // $lastMonthEarning = SessionBooking::where('payment_status','success')->whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');
            $curentMonthEarning = SessionBooking::whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            $lastMonthEarning = SessionBooking::whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');


            // dd($lastMonthEarning);
            return view('modules.admin.dashboard.index',compact('user', 'teacher', 'ondemand', 'poadcast', 'session', 'sessionBooking', 'totalearning', 'bookingChartData', 'revenueChatData', 'curentMonthEarning', 'lastMonthEarning'));
        }

        if($user->user_role == "coach"){

            $session = Coaching::where('user_id', $user->id)->count();
            $sessionBooking = SessionBooking::where('coach_id', $user->id)->count();
            $totalearning = SessionBooking::where('coach_id', $user->id)->sum('price_per_session');
            // $totalearning = SessionBooking::where('coach_id', $user->id)->where('payment_status','success')->sum('price_per_session');

            $bookingCountPerMonth = SessionBooking::where('coach_id', $user->id)->get()->groupBy(function($val) {
                return Carbon::parse($val->created_at)->format('YM');
            });

            $montsName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $currentYear = date('Y');

            foreach ($montsName as $key => $value) {
            $bookingChartData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->count() : 0);
            $revenueChatData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->sum('price_per_session') : 0);
            }

            $curentMonthEarning = SessionBooking::where('coach_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            $lastMonthEarning = SessionBooking::where('coach_id', $user->id)->whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');
            // $curentMonthEarning = SessionBooking::where('coach_id', $user->id)->where('payment_status','success')->whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            // $lastMonthEarning = SessionBooking::where('coach_id', $user->id)->where('payment_status','success')->whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');
            // dd($lastMonthEarning);
            return view('modules.admin.dashboard.index',compact('session', 'sessionBooking', 'totalearning', 'bookingChartData', 'revenueChatData', 'curentMonthEarning', 'lastMonthEarning'));
        }

        if($user->user_role == "user"){

            $session = SessionBooking::where('user_id', $user->id)->count();
            $sessionBooking = SessionBooking::where('user_id', $user->id)->count();

            $bookingCountPerMonth = SessionBooking::where('user_id', $user->id)->get()->groupBy(function($val) {
                return Carbon::parse($val->created_at)->format('YM');
            });

            $montsName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $currentYear = date('Y');

            foreach ($montsName as $key => $value) {
            $bookingChartData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->count() : 0);
            $revenueChatData[] = (isset($bookingCountPerMonth[$currentYear.$value]) ? $bookingCountPerMonth[$currentYear.$value]->sum('price_per_session') : 0);
            }

            // $totalPayment = SessionBooking::where('user_id', $user->id)->where('payment_status','success')->sum('price_per_session');
            $totalPayment = SessionBooking::where('user_id', $user->id)->sum('price_per_session');
            $curentMonthPayment = SessionBooking::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            // $curentMonthPayment = SessionBooking::where('user_id', $user->id)->where('payment_status','success')->whereMonth('created_at', Carbon::now()->month)->get()->sum('price_per_session');
            // $lastMonthPayment = SessionBooking::where('user_id', $user->id)->where('payment_status','success')->whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');
            $lastMonthPayment = SessionBooking::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->subMonth()->month)->get()->sum('price_per_session');


            // dd($lastMonthpPayment);
            return view('modules.admin.dashboard.index',compact('sessionBooking', 'totalPayment', 'bookingChartData', 'revenueChatData', 'curentMonthPayment', 'lastMonthPayment', 'session', 'sessionBooking'));
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

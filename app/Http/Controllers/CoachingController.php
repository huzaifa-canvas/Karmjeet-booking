<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\Sessionclass;
use App\Models\Slot;
use App\Models\Subject;
use App\Models\User;
use App\Services\Payments\StripeService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoachingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $coachings = Coaching::all();
        if ($user->user_role != 'admin') {
            $coachings = Coaching::where('user_id', $user->id)->get();
        }

        return view('modules.admin.coaching.list', compact('coachings',));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        $subjects = Subject::whereStatus(1)->get();
        $classes = Sessionclass::whereStatus(1)->get();
        $teachers = User::where('user_role', 'coach')->get();

        return view('modules.admin.coaching.forms', compact('isEdit', 'subjects', 'classes', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'coach_name'        => ['required', 'string', 'max:255'],
            'subject_id'        => ['required', 'string'],
            'class_id'          => ['required', 'string'],
            'start_time'        => ['required', 'array'],
            'end_time'          => ['required', 'array'],
            'metting_link'      => ['required', 'string'],
            'duration'          => ['required', 'array'],
            'price_per_session' => ['sometimes', 'integer'],
            'session_limit'     => ['required', 'integer'],
            'month_limit'       => ['required', 'integer'],
            'days'              => ['required', 'array'],
            'session'           => ['required', 'array'],
            'image'             => 'mimes:jpeg,png,jpg'

        ]);

        // dd($request->all());


        try {
            // if($request->price_per_session) {
            //     $stripe                 = new StripeService;
            //     $ids                    = $stripe->createProductOrPriceId($request->price_per_session, $request->title);
            //     $request['product_id']  = $ids['productId'];
            //     $request['price_id']    = $ids['priceId'];
            // }


            $userId               = Auth::user()->id;
            if (Auth::user()->user_role == "admin"){
                $userId               = $request['coach_name'];
                $request['coach_name']  = User::where('id', $userId)->first()->name;
            }

            $request['slug']      = slugGenerator($request->title, Coaching::class, 'slug');
            $request['image_id']  = "/assets/images/no-preview.png";
            $request['user_id']   = $userId;

            if (!empty($request->file('image'))) {
                $path = $request->file('image')->store('public/coaching');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_id']  = $attachment_url;
            }

            $coachingId = Coaching::create($request->except('_token', 'image','duration','days','session','start_time', 'end_time'));

            if ($coachingId->id) {
                foreach ($request->days as $keys => $value) {
                    Slot::create([
                    'user_id'       => $userId,
                    'coaching_id'   => $coachingId->id,
                    'days'          => $value,
                    'start_time'    => $request->start_time[$keys],
                    'end_time'      => $request->end_time[$keys],
                    'duration'      => $request->duration[$keys],
                    'session'       => $request->session[$keys],
                ]);
                }
            }

            return redirect()->route('coaching-list')->with(['status' => 'success', 'message' => "Sessions add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coaching  $coaching
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coaching = Coaching::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.coaching.forms', compact('id', 'coaching', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coaching  $coaching
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coaching = Coaching::findOrFail($id);
        $isEdit = true;
        $subjects = Subject::whereStatus(1)->get();
        $classes = Sessionclass::whereStatus(1)->get();
        $teachers = User::where('user_role', 'coach')->get();


        return view('modules.admin.coaching.forms', compact('id', 'coaching', 'isEdit', 'subjects', 'classes', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coaching  $coaching
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'coach_name'        => ['required', 'string', 'max:255'],
            'subject_id'        => ['required', 'string'],
            'class_id'          => ['required', 'string'],
            'start_time'        => ['required', 'string'],
            'end_time'          => ['required', 'string'],
            'metting_link'      => ['required', 'string'],
            'duration'          => ['required', 'array'],
            'price_per_session' => ['sometimes', 'integer'],
            'session_limit'     => ['required', 'integer'],
            'month_limit'       => ['required', 'integer'],
            'days'              => ['required', 'array'],
            'session'           => ['required', 'array'],
            'image'             => 'mimes:jpeg,png,jpg'
        ]);

        $coaching  = Coaching::findOrFail($id);

        try {

            $userId    = Auth::user()->id;
            if (Auth::user()->user_role == "admin"){
                $userId               = $request['coach_name'];
                $request['coach_name']  = User::where('id', $userId)->first()->name;
            }

            // if($request->price_per_session) {
            //     $stripe                 = new StripeService;
            //     if($coaching->product_id != '' && $coaching->price_id != ''){
            //         $title                  = ($request->title == $coaching->title) ? "" : $request->title;
            //         $price                  = ($request->price_per_session == $coaching->price_per_session) ? "" : $request->price_per_session;
            //         $ids                    = $stripe->updateProductOrPriceId($coaching->product_id, $coaching->price_id, $title, $price);
            //     } else{
            //         $ids                = $stripe->createProductOrPriceId($request->price_per_session, $request->title);
            //     }
            //     $request['product_id']  = $ids['productId'];
            //     $request['price_id']    = $ids['priceId'];
            // }

            $request['user_id']   = $userId;
            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $coaching->image_id);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/coaching');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_id']  = $attachment_url;
            }

            $isUpdate = Coaching::where('id', $id)->update($request->except('_token', 'image','duration','days','session',));

            if ($isUpdate) {
                Slot::where('coaching_id', $coaching->id)->delete();
                foreach ($request->days as $keys => $value) {
                    Slot::create([
                        'user_id'       => $userId,
                        'coaching_id'   => $coaching->id,
                        'days'          => $value,
                        'start_time'    => $request->start_time[$keys],
                        'end_time'      => $request->end_time[$keys],
                        'duration'      => $request->duration[$keys],
                        'session'       => $request->session[$keys],
                    ]);
                }
            }

            return redirect()->route('coaching-list')->with(['status' => 'success', 'message' => "Session updated successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('coaching-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coaching  $coaching
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coaching = Coaching::findOrFail($id);
        $isUpdate = Coaching::where('id', $id)->update(['is_active' => 0]);

        // $old_url = str_replace("storage", "public", "/" . $coaching->image_id);
        // Storage::delete($old_url);

        // Coaching::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Sessions Delete successfully"]);
    }

    public function createSlot(Request $request)
    {
        try {
            $slots = $this->getServiceScheduleSlots($request->duration, $request->start_time, $request->end_time);
            return['status' => true, 'slots' => $slots, 'message' => "" ];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => $e->getMessage() ];
        }
    }

    function slots($duration,$start,$end,$break) {

        if (!empty($break)) {
        foreach ($break as $key1 => $value1) {

            $slopt_1 = $this->getServiceScheduleSlots($duration, $start, $value1[0]);
            $slopt_2 = $this->getServiceScheduleSlots($duration, $value1[1], $end);
            $slopt = array_merge($slopt_1,$slopt_2);
        }
        }
        else{
            $slopt = $this->getServiceScheduleSlots($duration, $start, $end);
        }

        return $slopt;
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
                $time[$i]['start'] = $start;
                $time[$i]['end'] = $end;
                $time[$i]['isBooked'] = 'false';

            }
        }
        return $time;
    }

}

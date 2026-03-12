<?php

namespace App\Http\Controllers\ScheduleSession;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Models\SessionGrade;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = ScheduleSession::all();
        return view('modules.admin.schedule-session.list', compact('sessions',));
    }

    public function listForfront()
    {
        $sessions = ScheduleSession::whereStatus(1)->get();
        $grades = SessionGrade::whereStatus(1)->get();
        return response()->json(['sessions' => $sessions, 'grades' => $grades ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        $grades = SessionGrade::whereStatus(1)->get();
        $teachers = User::where('user_role', 'coach')->get();
        return view('modules.admin.schedule-session.forms', compact('isEdit', 'grades', 'teachers'));
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
            'title'             => ['required', 'string'],
            'status'            => ['required', 'string'],
            'plan_price'        => ['required', 'string'],
            'plan_hours'        => ['required', 'string'],
            'unique_color'      => ['required', 'string'],
            'month_limit'       => ['required', 'string'],
            'start_time'        => ['required', 'string'],
            'end_time'          => ['required', 'string'],
            'duration'          => ['required', 'string'],
            'days'              => ['required', 'array'],
            'teachers'          => ['required', 'array'],
            // 'grade_id'          => ['required', 'string'],
            // 'customize_hour'    => ['required', 'string'],
        ]);

        try {
            ScheduleSession::create($request->except('_token'));
            return redirect()->route('schedule-session-list')->with(['status' => 'success', 'message' => "Grade add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScheduleSession  $ScheduleSession
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sessions = ScheduleSession::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.schedule-session.forms', compact('id', 'sessions', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScheduleSession  ScheduleSession
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $session = ScheduleSession::findOrFail($id);
        $grades = SessionGrade::whereStatus(1)->get();
        $teachers = User::where('user_role', 'coach')->get();
        $isEdit = true;
        return view('modules.admin.schedule-session.forms', compact('id', 'grades', 'session', 'isEdit', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleSession  $ScheduleSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
        ]);

        $ScheduleSession = ScheduleSession::findOrFail($id);

        try {

            ScheduleSession::where('id', $id)->update($request->except('_token'));
            return redirect()->route('schedule-session-list')->with(['status' => 'success', 'message' => "Grade update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('ScheduleSession-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScheduleSession  $ScheduleSession
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ScheduleSession = ScheduleSession::findOrFail($id);
        ScheduleSession::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Grade Delete successfully"]);
    }
}

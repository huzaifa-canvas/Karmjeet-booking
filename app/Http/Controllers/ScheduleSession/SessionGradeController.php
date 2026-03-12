<?php

namespace App\Http\Controllers\ScheduleSession;

use App\Http\Controllers\Controller;
use App\Models\SessionGrade;
use Illuminate\Http\Request;

class SessionGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grades = SessionGrade::all();
        return view('modules.admin.session-grade.list', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.session-grade.forms', compact('isEdit'));
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
            'name' => ['required', 'string'],
        ]);

        try {
            SessionGrade::create($request->except('_token'));
            return redirect()->route('grade-list')->with(['status' => 'success', 'message' => "Grade add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SessionGrade  $SessionGrade
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grade = SessionGrade::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.session-grade.forms', compact('id', 'grade', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionGrade  SessionGrade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grade = SessionGrade::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.session-grade.forms', compact('id', 'grade', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionGrade  $SessionGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
        ]);

        $SessionGrade = SessionGrade::findOrFail($id);

        try {

            SessionGrade::where('id', $id)->update($request->except('_token'));
            return redirect()->route('grade-list')->with(['status' => 'success', 'message' => "Grade update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('SessionGrade-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionGrade  $SessionGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $SessionGrade = SessionGrade::findOrFail($id);
        SessionGrade::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Grade Delete successfully"]);
    }
}

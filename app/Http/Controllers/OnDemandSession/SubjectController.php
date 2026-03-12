<?php

namespace App\Http\Controllers\OnDemandSession;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('modules.admin.subject.list', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.subject.forms', compact('isEdit'));
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
            'name' => ['sometimes', 'string'],
            'image' => 'mimes:jpeg,png,jpg'
        ]);

        try {

            $slug                   = slugGenerator($request->name, Subject::class, 'slug');
            $request['slug']        = $slug;
            $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {


                $path = $request->file('image')->store('public/subject');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Subject::create($request->except('_token', 'image'));
            return redirect()->route('subject-list')->with(['status' => 'success', 'message' => "Subject add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $Subject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.subject.forms', compact('id', 'subject', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  Subject
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.subject.forms', compact('id', 'subject', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $Subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['sometimes', 'string'],
            'image' => 'mimes:jpeg,png,jpg'

        ]);

        $subject = Subject::findOrFail($id);

        try {

            if(!$subject->image_url) $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $subject->image_url);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/subject');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Subject::where('id', $id)->update($request->except('_token', 'image'));
            return redirect()->route('subject-list')->with(['status' => 'success', 'message' => "Subject update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('subject-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $Subject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        $old_url = str_replace("storage", "public", "/" . $subject->image_url);
        Storage::delete($old_url);

        Subject::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Subject Delete successfully"]);
    }
}

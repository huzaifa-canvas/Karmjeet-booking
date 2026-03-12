<?php

namespace App\Http\Controllers\OnDemandSession;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Sessionclass;
use Illuminate\Http\Request;

class SessionclassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Sessionclass::all();
        return view('modules.admin.class.list', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.class.forms', compact('isEdit'));
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

            $slug                   = slugGenerator($request->name, Sessionclass::class, 'slug');
            $request['slug']        = $slug;
            $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {


                $path = $request->file('image')->store('public/class');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Sessionclass::create($request->except('_token', 'image'));
            return redirect()->route('class-list')->with(['status' => 'success', 'message' => "Class add successfully"]);

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
        $subject = Sessionclass::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.class.forms', compact('id', 'subject', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  Subject
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = Sessionclass::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.class.forms', compact('id', 'subject', 'isEdit'));
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

        $subject = Sessionclass::findOrFail($id);

        try {

            if(!$subject->image_url) $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $subject->image_url);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/class');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Sessionclass::where('id', $id)->update($request->except('_token', 'image'));
            return redirect()->route('class-list')->with(['status' => 'success', 'message' => "Class update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('class-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
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
        $subject = Sessionclass::findOrFail($id);

        $old_url = str_replace("storage", "public", "/" . $subject->image_url);
        Storage::delete($old_url);

        Sessionclass::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Class Delete successfully"]);
    }
}

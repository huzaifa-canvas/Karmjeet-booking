<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Courses::all();
        return view('modules.admin.courses.list', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.courses.forms', compact('isEdit'));
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
            'title'         => ['required', 'string', 'max:255'],
            'status'        => ['required'],
            'image'         => 'mimes:jpeg,png,jpg',
            'pdf_document'  => 'mimes:pdf'

        ]);

        try {

            if (!empty($request->file('pdf_document'))) {

                $path = $request->file('pdf_document')->store('public/courses');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['pdf_url']  = $attachment_url;
            }

            $request['image_id']   = 'assets/images/no-preview.png';
            if (!empty($request->file('image'))) {

                $path = $request->file('image')->store('public/courses');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_id']  = $attachment_url;
            }

            $request['slug'] = slugGenerator($request->title, Courses::class, 'slug');

            Courses::create($request->except('_token', 'image', 'pdf_document'));
            return redirect()->route('courses-list')->with(['status' => 'success', 'message' => "Courses add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Courses::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.courses.forms', compact('id', 'course', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Courses  Courses
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Courses::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.courses.forms', compact('id', 'course', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courses  $Courses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'status'        => ['required'],
            'image'         => 'mimes:jpeg,png,jpg',
            'pdf_document'  => 'mimes:pdf'

        ]);

        $course = Courses::findOrFail($id);

        try {

            if (!isset($request->nocpdf) && $course->pdf_url != "") {

                $old_url = str_replace("storage", "public", "/" . $course->pdf_url);
                Storage::delete($old_url);
                $request['pdf_url']  = '';
            }

            if (!empty($request->file('pdf_document'))) {
                $old_url = str_replace("storage", "public", "/" . $course->pdf_url);
                Storage::delete($old_url);

                $path = $request->file('pdf_document')->store('public/courses');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['pdf_url']  = $attachment_url;
            }

            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $course->image_id);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/courses');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_id']  = $attachment_url;
            }

            Courses::where('id', $id)->update($request->except('_token', 'image', 'pdf_document', 'nocpdf'));
            return redirect()->route('courses-list')->with(['status' => 'success', 'message' => "Course updated successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('courses-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Courses  $Courses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Courses::findOrFail($id);
        Courses::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Courses Delete successfully"]);
    }
}

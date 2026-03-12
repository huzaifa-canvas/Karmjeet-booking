<?php

namespace App\Http\Controllers;

use App\Models\PagesContent;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = PagesContent::all();
        return view('modules.admin.pages-content.list', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.pages-content.forms', compact('isEdit'));
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
            'title' => ['sometimes', 'string'],
        ]);

        // dd($request->all());
        try {
            $slug               = slugGenerator($request->title, PagesContent::class, 'slug');
            $request['slug']    = $slug;

            $id = PagesContent::create([
                'title'     => $request->title,
                'slug'     => $request->slug,
                'status'   => $request->status,
            ]);

            $this->addSection($request, $id->id);
            return redirect()->route('page-list')->with(['status' => 'success', 'message' => "Page Added Successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\page  $content
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page   = PagesContent::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.pages-content.forms', compact('id', 'page', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\page  page
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page   = PagesContent::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.pages-content.forms', compact('id', 'page', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\page  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['sometimes', 'string'],
        ]);

        try {
            PagesContent::where('id', $id)->update([
                'title'     => $request->title,
                'status'   => $request->status,
            ]);
            $this->addSection($request, $id);
            return redirect()->route('page-edit',$id)->with(['status' => 'success', 'message' => "Page Updated Successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('page-edit',$id)->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\page  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = PagesContent::findOrFail($id);

        PageSection::where('page_id', $id)->delete();
        PagesContent::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "page Delete successfully"]);
    }

    public function addSection($request,$id)
    {
        PageSection::where('page_id', $id)->delete();
        foreach ($request->content as $key => $value) {

            PageSection::create([
                "page_id"         => $id,
                "content"         => $value,
                "section_colour"  => $request->section_colour[$key],
                "order_priority"  => $request->section_order[$key],
                "status"          => $request->section_status[$key],
            ]);
        }

        return;
    }
}


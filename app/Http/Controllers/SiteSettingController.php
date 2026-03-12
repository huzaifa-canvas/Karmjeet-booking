<?php

namespace App\Http\Controllers;

use App\Models\PagesContent;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit()
    {
        $isEdit = false;
        $pages = [
            "Home" => "home" ,
            "About" => "about" ,
            "Podcast" => "poadcast" ,
            "Coaching" => "coaching" ,
            "Courses & Training" => "course" ,
            "Blog" => "blog" ,
        ];

        $dynamicPages = PagesContent::all()->pluck('slug', 'title')->toArray();
        $pages = array_merge($pages, $dynamicPages);
        // dd();
        $settingData = SiteSetting::all()->groupBy('type');

        return view('modules.admin.site-setting.pages-colours', compact('isEdit','pages','settingData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach ($request->except('_token','page') as $page => $setting) {

            foreach ($setting as $key => $value) {
                DB::table('site_settings')->updateOrInsert(
                    ['type' => $page, 'key' => $key ],
                    ['value' => $value, 'created_at' => date('Y-m-d H:i:s')]
                );
            }
        }

        return redirect()->back()->with(['status' => 'success', 'message' => "Settings Updated Successfully"]);
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

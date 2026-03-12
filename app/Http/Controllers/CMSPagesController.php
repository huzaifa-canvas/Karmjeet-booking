<?php

namespace App\Http\Controllers;

use App\Models\AppUser\User;
use App\Models\Club\Club;
use App\Models\Club\Registry;
use App\Models\Helpers\Country;
use App\Models\Kennel\Kennel;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CMSPagesController extends Controller
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
    public function edit($type)
    {
        $isEdit = false;
        return view('modules.admin.cms-pages.'.$type, compact('isEdit', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type)
    {

        foreach ($request->except('_token') as $key => $value) {
            if(gettype($value) == 'object'){


                $path = $value->store('public/cms-pages');
                $value = str_replace("public", "storage", "/" . $path);
            }

            DB::table('cms_pages')->updateOrInsert(
                ['page_type' => $type, 'data_key' => $key ],
                ['data_value' => $value],
            );
        }

        return redirect()->back()->with(['status' => 'success', 'message' => "Content updated successfully"]);;
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

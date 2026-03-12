<?php

namespace App\Http\Controllers;

use App\Models\Testimnonials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimnonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimnonials = Testimnonials::all();
        return view('modules.admin.cms-pages.testimnonial_list', compact('testimnonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.cms-pages.testimnonial', compact('isEdit'));
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
            'name'         => ['sometimes', 'string'],
            'user_image'         => 'mimes:jpeg,png,jpg'
        ]);

        try {

            $request['image']   = 'assets/images/no-preview.png';

            if (!empty($request->file('user_image'))) {
                $path = $request->file('user_image')->store('public/testimnonial');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image']  = $attachment_url;
            }

            Testimnonials::create($request->except('_token',));
            return redirect()->route('testimnonial-list')->with(['status' => 'success', 'message' => "Testimnonial add successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('testimnonial-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $testimnonial = Testimnonials::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.cms-pages.testimnonial',compact('id', 'testimnonial', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $testimnonial = Testimnonials::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.cms-pages.testimnonial',compact('id', 'testimnonial', 'isEdit'));
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
        $request->validate([
            'name'         => ['sometimes', 'string'],
            'user_image'         => 'mimes:jpeg,png,jpg'

        ]);

        $testimnonials = Testimnonials::findOrFail($id);

        try {
            if (!empty($request->file('user_image'))) {
                $old_url = str_replace("storage", "public", "/" . $testimnonials->image_url);
                Storage::delete($old_url);

                $path = $request->file('user_image')->store('public/testimnonial');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image']  = $attachment_url;
            }

            Testimnonials::where('id', $id)->update($request->except('_token', 'user_image'));
            return redirect()->back()->with(['status' => 'success', 'message' => "Testimnonials updated successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poadcast = Testimnonials::findOrFail($id);

        $old_url = str_replace("storage", "public", "/" . $poadcast->image);
        Storage::delete($old_url);

        Testimnonials::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Testimnonial Delete successfully"]);
    }
}

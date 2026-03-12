<?php

namespace App\Http\Controllers;

use App\Models\Poadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PoadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poadcasts = Poadcast::all();
        return view('modules.admin.poadcast.list', compact('poadcasts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.poadcast.forms', compact('isEdit'));
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
            'title'         => ['sometimes', 'string'],
            'image'         => 'mimes:jpeg,png,jpg'

        ]);

        try {

            $slug                   = slugGenerator($request->title, Poadcast::class, 'slug');
            $request['slug']        = $slug;
            $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {
                $path = $request->file('image')->store('public/poadcast');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = '$attachment_url';
            }

            if (!empty($request->file('audio'))) {

                $path = $request->file('audio')->store('public/poadcast/audio');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['audio_url']  = $attachment_url;
            }

            Poadcast::create($request->except('_token', 'audio'));
            return redirect()->route('poadcast-list')->with(['status' => 'success', 'message' => "Poadcast add successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('poadcast-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\poadcast  $poadcast
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poadcast = Poadcast::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.poadcast.forms', compact('id', 'poadcast', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\poadcast  poadcast
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poadcast = Poadcast::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.poadcast.forms', compact('id', 'poadcast', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\poadcast  $poadcast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => ['sometimes', 'string'],
            'image'         => 'mimes:jpeg,png,jpg'

        ]);

        $poadcast = Poadcast::findOrFail($id);

        try {
            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $poadcast->image_url);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/poadcast');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Poadcast::where('id', $id)->update($request->except('_token', 'image'));
            return redirect()->back()->with(['status' => 'success', 'message' => "Poadcast updated successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\poadcast  $poadcast
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poadcast = Poadcast::findOrFail($id);

        $old_url = str_replace("storage", "public", "/" . $poadcast->image_url);
        Storage::delete($old_url);

        Poadcast::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Poadcast Delete successfully"]);
    }
}

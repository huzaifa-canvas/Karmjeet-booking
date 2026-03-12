<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $quers = Query::where("type", $type)->get();
        return view('modules.admin.query.list', compact('quers'));
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
    public function store(Request $request, $type)
    {
        try {

            $request->validate([
                'name'      => ['required'],
                'email'     => ['required'],
                'message'   => ['required'],
            ]);

            // $ifExist = Query::where('email', $request->email)->count();
            // if(!$ifExist)
            $data = [
                'name'      => $request->name,
                'email'     => $request->email,
                'message'   => $request->message,
                'type'      => $type,
            ];

            Query::create($data);
            Mail::to(env("MAIL_CONTACT_TO"))->send(new ContactEmail($data));
            // return redirect()->back()->with(['status' => 'success', 'message' => "Contacted Successfully!"]);
            return ['status' => true, 'message' => 'Contacted Successfully!'];
        } catch (\Throwable $e) {
            // return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Something Went Wrong!'];
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

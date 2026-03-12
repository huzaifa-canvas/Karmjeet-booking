<?php

namespace App\Http\Controllers;

use App\Models\Coaching;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role)
    {
        $users = User::where('user_role', $role)->get()->except(Auth::user()->id);
        return view('modules.admin.users.list', compact('users'));
    }

    public function indexApi()
    {
        return response()->json(User::all());
    }


    public function addSession($id)
    {
        $sessions = Coaching::where('status',1)->get();
        $isEdit = false;
        return view('modules.admin.users.bookingforms', compact('isEdit', 'sessions', 'id'));
    }

    public function coachingDetail(Request $request)
    {
        $coaching = Coaching::findOrFail($request->id);
        $days = $coaching->getslots->pluck('days')->toArray();
        $block_date = explode(",",str_replace(" ", "",$coaching?->first()?->blackout_dates));
        return ['session' =>  $coaching, 'block_date' => $block_date, 'days'=> $days ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.users.forms', compact('isEdit'));
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
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        try {

            $request['password']  = Hash::make($request->password);

            User::create(['name' => $request->name, 'email' => $request->email, 'password' => $request->password,
            'phone' => $request->phone, 'metting_link' => $request->metting_link, 'user_role' => $request->user_role]);
            // User_metas::create($request->except('_token','name','email','password'));

            return redirect()->route('user-list')->with(['status' => 'success', 'message' => "User add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
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
        $isEdit = true;
        $user = User::findOrFail($id);
        return view('modules.admin.users.forms', compact('id','user','isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isEdit = true;
        $user = (Auth::user()->user_role == 'admin') ? User::findOrFail($id) : Auth::user();
        return view('modules.admin.users.forms', compact('id', 'user', 'isEdit'));
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
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'password'  => ['sometimes', 'nullable', Rules\Password::defaults()],
        ]);

        try {
            if (!empty($request->password)) {
                $request['password']  = Hash::make($request->password);
            }

            $requestFiltered = collect([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'metting_link' => $request->metting_link,
                'user_role' => $request->user_role
            ])->filter()->all();

            User::where('id', $id)->update($requestFiltered);
            // User_metas::where('id', $id)->update($request->except('_token','name','email','password'));


            return redirect()->route('user-edit', $id)->with(['status' => 'success', 'message' => "User update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('user-edit', $id)->with(['status' => 'failed', 'message' => $e->getMessage() ]);
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
        User::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "User Delete successfully"]);
    }
}

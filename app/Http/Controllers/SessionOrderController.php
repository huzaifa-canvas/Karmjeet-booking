<?php

namespace App\Http\Controllers;

use App\Models\SessionOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SessionOrderController extends Controller
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
        return $this->userStore($request);
    }


    public function userStore($request)
    {
        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ];

        // Validate the request
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {

            $request['password']  = Hash::make($request->password);

            User::create(['name' => $request->name, 'email' => $request->email, 'password' => $request->password, 'user_role' => 'user']);
            // User_metas::create($request->except('_token','name','email','password'));

            return response()->json([
                'status' => true,
                'errors' => "User add successfully"
            ], 200); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SessionOrder  $sessionOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SessionOrder $sessionOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionOrder  $sessionOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SessionOrder $sessionOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionOrder  $sessionOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SessionOrder $sessionOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionOrder  $sessionOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionOrder $sessionOrder)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    /**
     * Display list of all member profiles.
     */
    public function index()
    {
        $users = User::where('user_role', 'user')
            ->with('profile')
            ->get();

        return view('modules.admin.member-profiles.list', compact('users'));
    }

    /**
     * Display a single member's full profile/application.
     */
    public function show($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('modules.admin.member-profiles.show', compact('user'));
    }

    /**
     * Show the edit form for a member's profile.
     */
    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('modules.admin.member-profiles.edit', compact('user'));
    }

    /**
     * Update a member's profile.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Profile not found for this user.']);
        }

        $validated = $request->validate([
            'registration_type' => 'required|in:adult,minor',
            'minor_full_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:0',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'how_heard' => 'nullable|string|max:255',
            'prior_experience' => 'required|boolean',
            'experience_details' => 'nullable|string',
            'signature' => 'nullable|string|max:255',
            'form_date' => 'nullable|date',
        ]);

        $profile->update($validated);

        return redirect()->route('admin.member-profiles.show', $id)
            ->with(['status' => 'success', 'message' => 'Member profile updated successfully.']);
    }
}

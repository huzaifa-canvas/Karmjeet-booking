<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    public function show()
    {
        if (Auth::user()->profile_completed) {
            return redirect()->route('dashboard')->with('success', 'Your profile is already completed.');
        }
        return view('profile-completion');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_type' => 'required|in:adult,minor',
            'minor_full_name' => 'required_if:registration_type,minor|nullable|string|max:255',
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
            'how_heard' => 'required|array',
            'how_heard.*' => 'string',
            'how_heard_other' => 'nullable|string|max:255',
            'prior_experience' => 'required|boolean',
            'experience_details' => 'nullable|string',
            'goals' => 'required|array',
            'goals.*' => 'string',
            'goals_other' => 'nullable|string|max:255',
            'physical_readiness' => 'required|array',
            'physical_readiness.*.answer' => 'required|in:yes,no',
            'physical_readiness.*.explanation' => 'nullable|string',
            'consent_background_check' => 'required|boolean',
            'media_release_consent' => 'required|accepted',
            'media_release_no_filming' => 'required|accepted',
            'non_compete_agreement' => 'required|accepted',
            'non_compete_no_solicit' => 'required|accepted',
            'non_compete_initials' => 'required|string',
            'criminal_record_agreement' => 'required|accepted',
            'criminal_initials' => 'required|string',
            'waiver_items' => 'required|array|min:7',
            'waiver_items.*' => 'string',
            'waiver_initials' => 'required|string',
            'form_date' => 'required|date',
            'signature' => 'required|string|max:255',
            'signature_age' => 'nullable|integer',
            'signature_type' => 'required|in:adult,guardian',
            'signature_text' => 'required|string|max:255',
            'signature_date' => 'required|date',
        ]);

        $user = Auth::user();

        // Combine how_heard array with other if provided
        $howHeard = $validated['how_heard'];
        if (!empty($validated['how_heard_other'])) {
            $howHeard[] = $validated['how_heard_other'];
        }

        // Combine goals with other if provided
        $goals = $validated['goals'];
        if (!empty($validated['goals_other'])) {
            $goals[] = $validated['goals_other'];
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'registration_type' => $validated['registration_type'],
                'minor_full_name' => $validated['minor_full_name'] ?? null,
                'date_of_birth' => $validated['date_of_birth'],
                'age' => $validated['age'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'how_heard' => implode(', ', $howHeard),
                'prior_experience' => $validated['prior_experience'],
                'experience_details' => $validated['experience_details'] ?? null,
                'goals' => $goals,
                'physical_readiness' => $validated['physical_readiness'],
                'consent_background_check' => $validated['consent_background_check'],
                'media_release_consent' => true,
                'non_compete_agreement' => true,
                'criminal_record_agreement' => true,
                'waiver_agreement' => true,
                'signature' => $validated['signature_text'],
                'form_date' => $validated['form_date'],
            ]
        );

        $user->update(['profile_completed' => true]);

        return redirect()->route('dashboard')->with('success', 'Profile completed successfully!');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_type',
        'minor_full_name',
        'guardian_name',
        'date_of_birth',
        'age',
        'occupation',
        'gender',
        'phone_number',
        'secondary_phone',
        'address',
        'city',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'physician_name',
        'physician_phone',
        'how_heard',
        'prior_experience',
        'experience_details',
        'goals',
        'physical_readiness',
        'medical_conditions',
        'allergies',
        'consent_background_check',
        'media_release_consent',
        'non_compete_agreement',
        'criminal_record_agreement',
        'waiver_agreement',
        'emergency_treatment_consent',
        'signature',
        'form_date',
    ];

    protected $casts = [
        'prior_experience' => 'boolean',
        'goals' => 'array',
        'physical_readiness' => 'array',
        'consent_background_check' => 'boolean',
        'media_release_consent' => 'boolean',
        'non_compete_agreement' => 'boolean',
        'criminal_record_agreement' => 'boolean',
        'waiver_agreement' => 'boolean',
        'emergency_treatment_consent' => 'boolean',
        'date_of_birth' => 'date',
        'form_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

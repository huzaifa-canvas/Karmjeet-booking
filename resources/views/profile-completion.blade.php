@extends('layouts.master')

@section('style')
<style>
    .hidden { display: none !important; }
    .section-title {
        font-weight: bold;
        border-bottom: 2px solid #118CFF;
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <x-application-logo style="height: 40px; width: auto; margin-right: 15px;" />
                            <h2 class="content-header-title mb-0">Kaiten Mixed Martial Arts Academy and Fitness (Kaiten MMA)</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title text-primary">Application/Terms of Agreement - 2026</h4>
                </div>
                <div class="card-body pt-2">

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile-completion.store') }}">
                        @csrf

                        {{-- ============================================ --}}
                        {{-- Registration Information --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Registration Information</h5>
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="registration_type" value="adult" id="type_adult" class="form-check-input" required {{ old('registration_type', 'adult') == 'adult' ? 'checked' : '' }} onclick="toggleMinorField(false)">
                                        <label class="form-check-label" for="type_adult">Adult /Parent Name</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="registration_type" value="minor" id="type_minor" class="form-check-input" required {{ old('registration_type') == 'minor' ? 'checked' : '' }} onclick="toggleMinorField(true)">
                                        <label class="form-check-label" for="type_minor">Minor</label>
                                    </div>
                                </div>
                                <div id="minor_field_container" class="col-md-6 mb-1 {{ old('registration_type') == 'minor' ? '' : 'hidden' }}">
                                    <label class="form-label">Minor Full Name:</label>
                                    <input type="text" name="minor_full_name" class="form-control" value="{{ old('minor_full_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Date of Birth:</label>
                                    <input type="date" name="date_of_birth" class="form-control" required value="{{ old('date_of_birth') }}">
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label class="form-label">Age:</label>
                                    <input type="number" name="age" class="form-control" required value="{{ old('age') }}">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" name="phone_number" class="form-control" required value="{{ old('phone_number') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Address --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Address</h5>
                            <div class="row mt-1">
                                <div class="col-12 mb-1">
                                    <label class="form-label">Address:</label>
                                    <input type="text" name="address" class="form-control" required value="{{ old('address') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">City:</label>
                                    <input type="text" name="city" class="form-control" required value="{{ old('city') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Province:</label>
                                    <input type="text" name="province" class="form-control" required value="{{ old('province') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Postal Code:</label>
                                    <input type="text" name="postal_code" class="form-control" required value="{{ old('postal_code') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Emergency Contact --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Emergency Contact</h5>
                            <div class="row mt-1">
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Emergency Contact Name:</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" required value="{{ old('emergency_contact_name') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Relationship:</label>
                                    <input type="text" name="emergency_contact_relationship" class="form-control" required value="{{ old('emergency_contact_relationship') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control" required value="{{ old('emergency_contact_phone') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- How Did You Hear About Us? --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">How Did You Hear About Us?</h5>
                            <div class="row mt-1">
                                <div class="col-12">
                                    @foreach(['Friend', 'Facebook/Instagram', 'Google/Website', 'Booth/Workshop'] as $source)
                                        <div class="form-check form-check-inline mb-1">
                                            <input type="checkbox" name="how_heard[]" value="{{ $source }}" id="heard_{{ $loop->index }}" class="form-check-input" {{ is_array(old('how_heard')) && in_array($source, old('how_heard')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="heard_{{ $loop->index }}">{{ $source }}</label>
                                        </div>
                                    @endforeach
                                    <div class="form-check form-check-inline mb-1">
                                        <input type="checkbox" name="how_heard[]" value="Other" id="heard_other" class="form-check-input" {{ is_array(old('how_heard')) && in_array('Other', old('how_heard')) ? 'checked' : '' }} onclick="toggleOtherHeard(this.checked)">
                                        <label class="form-check-label" for="heard_other">Other:</label>
                                    </div>
                                    <input type="text" name="how_heard_other" id="how_heard_other" class="form-control form-control-sm mt-1 {{ is_array(old('how_heard')) && in_array('Other', old('how_heard')) ? '' : 'hidden' }}" style="max-width:300px; display:inline-block;" placeholder="Please specify..." value="{{ old('how_heard_other') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Prior Martial Arts Experience --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Prior Martial Arts Experience?</h5>
                            <div class="row mt-1">
                                <div class="col-12">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="prior_experience" value="1" id="exp_yes" class="form-check-input" required {{ old('prior_experience') == '1' ? 'checked' : '' }} onclick="toggleExpField(true)">
                                        <label class="form-check-label" for="exp_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="prior_experience" value="0" id="exp_no" class="form-check-input" required {{ old('prior_experience', '0') == '0' ? 'checked' : '' }} onclick="toggleExpField(false)">
                                        <label class="form-check-label" for="exp_no">No</label>
                                    </div>
                                    <div id="exp_details_container" class="mt-1 {{ old('prior_experience') == '1' ? '' : 'hidden' }}">
                                        <label class="form-label">Style/School:</label>
                                        <input type="text" name="experience_details" class="form-control" style="max-width:400px;" value="{{ old('experience_details') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Goals for Training --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Goals for Training (Check all that apply)</h5>
                            <div class="row mt-1">
                                @foreach(['Fitness', 'Self-Defense', 'Competition', 'Weight Loss', 'Self-Confidence', 'Mental Focus', 'Stress Relief', 'Professional MMA'] as $goal)
                                    <div class="col-md-3 col-6 mb-1">
                                        <div class="form-check">
                                            <input type="checkbox" name="goals[]" value="{{ $goal }}" id="goal_{{ $loop->index }}" class="form-check-input" {{ is_array(old('goals')) && in_array($goal, old('goals')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="goal_{{ $loop->index }}">{{ $goal }}</label>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-3 col-6 mb-1">
                                    <div class="form-check">
                                        <input type="checkbox" name="goals[]" value="Other" id="goal_other" class="form-check-input" {{ is_array(old('goals')) && in_array('Other', old('goals')) ? 'checked' : '' }} onclick="toggleOtherGoal(this.checked)">
                                        <label class="form-check-label" for="goal_other">Other:</label>
                                    </div>
                                    <input type="text" name="goals_other" id="goals_other_input" class="form-control form-control-sm mt-50 {{ is_array(old('goals')) && in_array('Other', old('goals')) ? '' : 'hidden' }}" placeholder="Specify..." value="{{ old('goals_other') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Physical Activity Readiness --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Physical Activity Readiness (Check Yes or No; explain if Yes)</h5>
                            @php
                                $questions = [
                                    1 => 'Has a doctor advised you to only do supervised physical activity?',
                                    2 => 'Chest pain during or outside activity?',
                                    3 => 'Diagnosed/concussed in past 6 months, frequent headaches?',
                                    4 => 'Dizziness/loss of consciousness?',
                                    5 => 'Current conditions/infections/bone or joint issues?',
                                    6 => 'Recent surgery or pregnancy (past 6 months)?',
                                    7 => 'Using any medication/substances affecting performance?',
                                ];
                            @endphp
                            <div class="mt-1">
                                @foreach($questions as $id => $question)
                                    @php
                                        $oldAnswer = old("physical_readiness.{$id}.answer", 'no');
                                        $oldExplanation = old("physical_readiness.{$id}.explanation");
                                    @endphp
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fw-bold">{{ $id }}.</span>
                                            <span class="me-3">{{ $question }}</span>
                                            <div class="form-check form-check-inline ms-auto">
                                                <input type="radio" name="physical_readiness[{{ $id }}][answer]" id="q_{{ $id }}_yes" value="yes" class="form-check-input" required {{ $oldAnswer == 'yes' ? 'checked' : '' }} onclick="toggleExplainField({{ $id }}, true)">
                                                <label class="form-check-label" for="q_{{ $id }}_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="physical_readiness[{{ $id }}][answer]" id="q_{{ $id }}_no" value="no" class="form-check-input" required {{ $oldAnswer == 'no' ? 'checked' : '' }} onclick="toggleExplainField({{ $id }}, false)">
                                                <label class="form-check-label" for="q_{{ $id }}_no">No</label>
                                            </div>
                                        </div>
                                        <input type="text" id="explain_{{ $id }}" name="physical_readiness[{{ $id }}][explanation]" class="form-control form-control-sm mt-50 {{ $oldAnswer == 'yes' ? '' : 'hidden' }}" placeholder="Please explain..." value="{{ $oldExplanation }}">
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <label class="form-label fw-bold">Consent to background check if assisting classes (18+)?</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="consent_background_check" value="1" id="bg_yes" class="form-check-input" required {{ old('consent_background_check') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bg_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="consent_background_check" value="0" id="bg_no" class="form-check-input" required {{ old('consent_background_check', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bg_no">No</label>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Media Release Consent --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Media Release Consent</h5>
                            <div class="mt-1">
                                <div class="form-check mb-1">
                                    <input type="checkbox" name="media_release_consent" value="1" id="consent_media" class="form-check-input" required {{ old('media_release_consent') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_media">I grant Kaiten MMA permission to use my name, photo, or video for marketing/promotional purposes.</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input type="checkbox" name="media_release_no_filming" value="1" id="consent_no_filming" class="form-check-input" required {{ old('media_release_no_filming') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_no_filming">I understand no third-party is permitted to film/record without prior written approval from management.</label>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- SECTION 4: Non-Compete Agreement --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">SECTION 4: Non-Compete Agreement (12-Month Term)</h5>
                            <div class="mt-1">
                                <div class="form-check mb-1">
                                    <input type="checkbox" name="non_compete_agreement" value="1" id="consent_compete1" class="form-check-input" required {{ old('non_compete_agreement') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_compete1">I agree that I will not share Kaiten MMA proprietary curriculum, or teach/operate a similar martial arts or fitness school or class within 25 km of this location for 12 months post-membership.</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input type="checkbox" name="non_compete_no_solicit" value="1" id="consent_compete2" class="form-check-input" required {{ old('non_compete_no_solicit') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_compete2">I agree not to solicit or recruit Kaiten MMA members or staff for another combat sports program.</label>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label">Initials:</label>
                                        <input type="text" name="non_compete_initials" class="form-control" required style="max-width:150px;" value="{{ old('non_compete_initials') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Criminal Record Check --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <div class="form-check mb-1">
                                <input type="checkbox" name="criminal_record_agreement" value="1" id="consent_criminal" class="form-check-input" required {{ old('criminal_record_agreement') ? 'checked' : '' }}>
                                <label class="form-check-label" for="consent_criminal">Agree to a criminal record check if requested, we reserve the right to refuse service and or to protect our clients ask for a valid criminal record check</label>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label class="form-label">Initials:</label>
                                    <input type="text" name="criminal_initials" class="form-control" required style="max-width:150px;" value="{{ old('criminal_initials') }}">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Waiver of Liability & Assumption of Risk --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Waiver of Liability & Assumption of Risk</h5>
                            <div class="mt-1">
                                @php
                                    $waiverValues = ['inherent_risks', 'release_liability', 'accept_risks', 'physical_contact', 'safety_rules', 'gear_responsibility', 'no_claims'];
                                    $waiverLabels = [
                                        'I acknowledge that martial arts activities include inherent risks.',
                                        'I release Kaiten MMA, instructors, and staff from any liability for injury, illness (including COVID-19), loss, theft, or death.',
                                        'I understand and accept these risks voluntarily.',
                                        'I consent to physical contact as part of learning.',
                                        'I will follow all posted and verbal safety and hygiene rules (e.g., gear cleanliness, illness policy, proper conduct).',
                                        'I am responsible for the condition of my training gear. Equipment provided "as-is."',
                                        'I will not bring any claims against Kaiten MMA arising from participation.',
                                    ];
                                @endphp
                                @foreach($waiverValues as $i => $val)
                                    <div class="form-check mb-1">
                                        <input type="checkbox" name="waiver_items[]" value="{{ $val }}" class="form-check-input" required {{ is_array(old('waiver_items')) && in_array($val, old('waiver_items')) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $waiverLabels[$i] }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Initials:</label>
                                    <input type="text" name="waiver_initials" class="form-control" required style="max-width:150px;" value="{{ old('waiver_initials') }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Today's Date:</label>
                                    <input type="date" name="form_date" value="{{ old('form_date', date('Y-m-d')) }}" class="form-control" required readonly>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Signature & Final Acceptance --}}
                        {{-- ============================================ --}}
                        <div class="mb-3 border-top pt-2">
                            <h5 class="section-title">Signature & Final Acceptance</h5>
                            <p class="text-muted">By signing below, I confirm I have read and fully understand all the terms in this Registration & Waiver Agreement. I certify all information provided is accurate.</p>
                            <div class="row mt-1">
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Name of Participant:</label>
                                    <input type="text" name="signature" class="form-control" required value="{{ old('signature') }}">
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label class="form-label">Age:</label>
                                    <input type="number" name="signature_age" class="form-control" value="{{ old('signature_age') }}">
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="form-check form-check-inline mt-2">
                                        <input type="radio" name="signature_type" value="adult" id="sig_adult" class="form-check-input" {{ old('signature_type', 'adult') == 'adult' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sig_adult">Adult Participant</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input type="radio" name="signature_type" value="guardian" id="sig_guardian" class="form-check-input" {{ old('signature_type') == 'guardian' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sig_guardian">Parent/Guardian Signature (if under 18)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label fw-bold">Signature:</label>
                                    <input type="text" name="signature_text" class="form-control" required placeholder="Type Full Name as Signature" value="{{ old('signature_text') }}">
                                    <small class="text-muted">By typing your name, you are providing a legal electronic signature.</small>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Date:</label>
                                    <input type="date" name="signature_date" value="{{ old('signature_date', date('Y-m-d')) }}" class="form-control" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2 text-center">
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleMinorField(show) {
        const container = document.getElementById('minor_field_container');
        container.classList.toggle('hidden', !show);
        const inputs = container.querySelectorAll('input');
        inputs.forEach(input => input.required = show);
    }

    function toggleExpField(show) {
        document.getElementById('exp_details_container').classList.toggle('hidden', !show);
    }

    function toggleExplainField(id, show) {
        const field = document.getElementById('explain_' + id);
        field.classList.toggle('hidden', !show);
        field.required = show;
    }

    function toggleOtherHeard(show) {
        document.getElementById('how_heard_other').classList.toggle('hidden', !show);
    }

    function toggleOtherGoal(show) {
        document.getElementById('goals_other_input').classList.toggle('hidden', !show);
    }
</script>
@endsection

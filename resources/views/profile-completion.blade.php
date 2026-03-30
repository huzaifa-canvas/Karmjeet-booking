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
                        {{-- Full Waiver, Release & Indemnification Agreement --}}
                        {{-- ============================================ --}}
                        <div class="mb-3">
                            <h5 class="section-title">Waiver, Release of Liability, Assumption of Risk & Indemnification Agreement</h5>
                            <div class="mt-1 p-2 border rounded" style=" overflow-y: auto; background-color: #f9f9f9;">
                                <p class="fw-bold text-center">Kaiten Mixed Martial Arts Academy and Fitness Ltd.</p>
                                <p class="fw-bold text-center">WAIVER, RELEASE OF LIABILITY, ASSUMPTION OF RISK, AND INDEMNIFICATION AGREEMENT</p>
                                <p class="text-muted small">This Agreement is effective upon execution and governs all participation on site and off site — in any activity sponsored, organized, supervised, hosted, promoted, or otherwise associated with Kaiten Mixed Martial Arts Academy and Fitness Ltd.</p>

                                <p class="fw-bold mt-2">PARTIES</p>
                                <p><strong>1. "Participant"</strong> — The individual signing below who engages in any activity, class, seminar, competition, workshop, fitness session, training session, sparring match, tournament, event, or any other martial arts related endeavour conducted by, for, or in association with Kaiten MMA, whether occurring at 185 Dominion Street, Prince George, BC, or at any other location.</p>
                                <p><strong>2. "Released Parties"</strong> — Kaiten Mixed Martial Arts Academy and Fitness Ltd., its owners, shareholders, directors, officers, employees, instructors, coaches, trainers, volunteers, agents, contractors, independent contractors, successors, assigns, affiliates, sponsors, licensees, parents, heirs, executors, administrators, insurers, and any other person or entity acting on behalf of or in connection with Kaiten MMA — in their personal and representative capacities.</p>
                                <p><strong>3. "Parent/Guardian"</strong> — If the Participant is under the age of 18 years, the individual who is the legal parent or court appointed guardian of the Participant.</p>

                                <p class="mt-2">A. Participant desires to engage in martial arts related activities, fitness training, mixed martial arts ("MMA"), Brazilian Jiu Jitsu, Muay Thai, Kickboxing, Taekwondo, Judo, Wrestling, Krav Maga, self defence drills, sparring (light, medium, or full contact), competition preparation, conditioning classes, yoga/fitness integration, pad work, drilling, partner drills, tournament participation, and any other activity, event, or programming offered by Kaiten MMA — whether conducted on Kaiten's premises at 185 Dominion Street, Prince George, BC, or at any off site location (collectively, known as any "Activities").</p>
                                <p>B. Participant acknowledges that the Activities involve inherent, unavoidable, and serious risks of personal injury, permanent disability, paralysis, brain injury, spinal injury, loss of limbs, concussion, death, property damage, emotional distress, and economic loss — risks that <strong>cannot be eliminated</strong> even with the utmost care, supervision, or safety equipment.</p>
                                <p>C. Participant voluntarily chooses to participate in the Activities with full knowledge of these risks.</p>
                                <p>D. This Agreement is intended to provide the broadest possible legal protection to the Released Parties permissible under the laws of British Columbia and Canada.</p>

                                <p class="fw-bold mt-2">1. ASSUMPTION OF RISK</p>
                                <p>1.1 Participant expressly acknowledges, understands, and voluntarily accepts the inherent risks, dangers, and hazards associated with the Activities, including but not limited to:</p>
                                <ul class="small">
                                    <li>Strikes, kicks, throws, joint locks, chokes, grappling, sparring, and contact with training partners;</li>
                                    <li>Falls, collisions, impacts with equipment (bags, pads, cages, mats, ropes, walls, flooring);</li>
                                    <li>Over exertion, heat related illness, dehydration, cardiac events, musculoskeletal injury;</li>
                                    <li>Accidental injury caused by the negligence (ordinary or gross) of any other participant, instructor, or third party;</li>
                                    <li>Injury occurring outside Kaiten MMA's facility (e.g., at competitions, seminars, outdoor training sessions, partner drills at parks, or any location where Kaiten MMA branded Activities are conducted);</li>
                                    <li>Exposure to ANY infectious diseases (including but not limited to COVID-19, influenza, MRSA, staph);</li>
                                    <li>Injuries arising from failure to follow safety rules, instructor instructions, or Participant's own physical limitations;</li>
                                    <li>ANY AND ALL RISKS, KNOWN OR UNKNOWN, FORESEEABLE OR UNFORESEEABLE, associated with martial arts training or fitness activities.</li>
                                </ul>
                                <p>1.2 Participant covenants that they are physically fit to participate, have disclosed ALL medical conditions, injuries, or limitations to Kaiten MMA staff, and will immediately cease participation if they feel unwell, fatigued, or injured.</p>
                                <p>1.3 NO REPRESENTATION HAS BEEN MADE BY THE RELEASED PARTIES — EXPRESS OR IMPLIED — THAT THE ACTIVITIES ARE SAFE. Participant participates solely at their own risk.</p>

                                <p class="fw-bold mt-2">2. RELEASE AND WAIVER OF LIABILITY</p>
                                <p>2.1 Participant (and, if Participant is a minor, Parent/Guardian on Participant's behalf) hereby irrevocably RELEASES, WAIVES, DISCHARGES, AND COVENANTS NOT TO SUE the Released Parties from any and all liability, claims, demands, actions, suits, proceedings, damages, losses, injuries, deaths, costs, expenses, or liabilities whatsoever — whether based on contract, tort (including negligence, gross negligence, or willful misconduct), strict liability, statutory violation, breach of duty, or otherwise — arising out of, related to, or in any way connected with:</p>
                                <ul class="small">
                                    <li>Participation in the Activities;</li>
                                    <li>Any injury, disability, death, or property damage sustained on or off Kaiten's premises;</li>
                                    <li>Any act, omission, decision, or conduct of any Released Party before, during, or after the Activities;</li>
                                    <li>Any claim for loss of consortium, loss of earnings, emotional distress, or punitive damages;</li>
                                    <li>ANY CLAIM BY A PARENT OR GUARDIAN asserting that Kaiten MMA failed to protect a minor participant.</li>
                                </ul>
                                <p>THIS RELEASE IS INTENDED TO BE AS BROAD AND COMPREHENSIVE AS IS PERMISSIBLE UNDER APPLICABLE LAW IN BRITISH COLUMBIA.</p>
                                <p>2.2 This release applies to injuries or claims occurring NOW, IN THE PAST, OR IN THE FUTURE, and survives the termination of participation.</p>

                                <p class="fw-bold mt-2">3. INDEMNIFICATION CLAUSE (THE CORE OF THIS AGREEMENT)</p>
                                <p>3.1 Participant (and, if Participant is a minor, Parent/Guardian) agrees to INDEMNIFY, DEFEND, AND HOLD HARMLESS the Released Parties from and against any and all claims, demands, lawsuits, judgments, awards, settlements, losses, damages, costs, attorneys' fees, expenses, or liabilities — including but not limited to claims brought BY OR ON BEHALF OF THE PARTICIPANT, BY A PARENT/GUARDIAN, BY A THIRD PARTY, OR BY THE PARTICIPANT'S ESTATE — arising in any way from the Participant's participation in the Activities, whether on site or off site.</p>
                                <p>3.2 Specifically, Participant/Parent/Guardian agrees to:</p>
                                <ul class="small">
                                    <li>Pay all costs of defence (including reasonable legal fees) incurred by any Released Party in connection with any claim, lawsuit, or administrative proceeding arising from the Participant's involvement with Kaiten MMA;</li>
                                    <li>Reimburse the Released Parties immediately for any settlement paid, judgment entered, or damages awarded against them arising from such claims;</li>
                                    <li>Indemnify the Released Parties against any claim brought by a parent or guardian alleging in any way that Kaiten MMA was negligent, failed to supervise, failed to provide a safe environment, or otherwise caused injury to a minor participant.</li>
                                </ul>
                                <p>PARENTS/GUARDIANS WHO SIGN THIS AGREEMENT ARE THEMSELVES BOUND BY THIS INDEMNIFICATION OBLIGATION AND WAIVE ANY RIGHT TO SEEK INDEMNIFICATION FROM THE RELEASED PARTIES.</p>
                                <p>3.3 This indemnification obligation survives the Participant's death. In the event of the Participant's death, the Participant's estate, heirs, executors, and administrators shall be bound by this Agreement and shall continue the indemnification obligations set forth herein.</p>
                                <p>3.4 NO INSURANCE CARRIED BY KAITEN MMA (OR ANY RELEASED PARTY) SHALL BE RESPONSIBLE FOR, NOR SHALL IT INURE TO THE BENEFIT OF, ANY CLAIM ARISING FROM PARTICIPANT'S ACTIVITIES. Participant is solely responsible for obtaining their own personal injury and liability insurance.</p>

                                <p class="fw-bold mt-2">4. MINOR PARTICIPANTS – PARENT/GUARDIAN OBLIGATIONS</p>
                                <p>4.1 IF PARTICIPANT IS UNDER 18 YEARS OF AGE: The Parent/Guardian MUST SIGN THIS AGREEMENT on the Participant's behalf.</p>
                                <p>By signing, the Parent/Guardian certifies that they:</p>
                                <ul class="small">
                                    <li>Have read and fully understand all provisions of this Agreement;</li>
                                    <li>Have explained the risks, waivers, and indemnities to the minor Participant in language the minor understands;</li>
                                    <li>Voluntarily consent to the minor's participation;</li>
                                    <li>PERSONALLY ASSUMES ALL RISK associated with the minor's participation and waives any claim against the Released Parties for any injury to the minor;</li>
                                    <li>AGREES TO INDEMNIFY THE RELEASED PARTIES for any claim, lawsuit, or expense they (the Parent/Guardian) may bring or cause on behalf of the minor — including claims alleging parental rights violations, lack of supervision, or failure to protect the minor.</li>
                                </ul>
                                <p>4.2 A MINOR'S SIGNATURE ALONE IS INSUFFICIENT. The Parent/Guardian's signature is mandatory for the Agreement to be valid.</p>

                                <p class="fw-bold mt-2">5. ACKNOWLEDGEMENTS & COVENANTS</p>
                                <p>5.1 <strong>Voluntary Execution:</strong> Participant (and Parent/Guardian, if applicable) acknowledges that: They have read this Agreement carefully; They have had sufficient opportunity to ask questions and receive satisfactory answers; They have not been coerced, pressured, or induced by any representation to sign; They sign freely, voluntarily, and with full knowledge of its legal consequences.</p>
                                <p>5.2 <strong>No Oral Modifications:</strong> Any modification to this Agreement must be in writing and signed by an authorized officer of Kaiten MMA. Oral representations are VOID.</p>
                                <p>5.3 <strong>Rules & Instructions:</strong> Participant agrees to obey all rules, policies, safety protocols, and verbal/written instructions of Kaiten staff at all times, whether on site or off site. Violation of any rule voids the Participant's right to continue participation and does NOT diminish the enforceability of this waiver. Kaiten MMA retains the right to terminate any membership at any time to anyone for any reason.</p>
                                <p>5.4 <strong>Medical Consent:</strong> Participant consents to emergency medical treatment (including ambulance transport, hospital care, surgery, blood transfusion) if, in the judgment of a medical professional or Kaiten MMA staff, such treatment is necessary to prevent death or serious injury. Participant waives any claim against the Released Parties for acting in good faith reliance on this consent.</p>
                                <p>5.5 <strong>Photography/Video Release:</strong> Participant grants Kaiten MMA the <strong>irrevocable right</strong> to photograph, video record, and otherwise capture Participant's image during Activities, and to use, publish, broadcast, or display such images for any legal reason, promotional, educational, advertising, or archival purposes worldwide, in perpetuity, without compensation.</p>

                                <p class="fw-bold mt-2">6. GOVERNING LAW & ENFORCEABILITY</p>
                                <p>6.1 This Agreement is governed by and construed in accordance with the laws of the Province of British Columbia and the laws of Canada, without regard to conflict of law principles.</p>
                                <p>6.2 <strong>Severability:</strong> If any clause of this Agreement is deemed unenforceable by a court of competent jurisdiction, the remaining clauses shall remain in full force and effect.</p>
                                <p>6.3 <strong>Entire Agreement:</strong> This document constitutes the complete and exclusive statement of the agreement between the Parties concerning the subject matter herein and supersedes all prior discussions, representations, or agreements.</p>
                                <p>6.4 <strong>BC Case Law Support:</strong> The Supreme Court of British Columbia consistently upholds comprehensive waivers for inherently risky recreational activities (see Teno v. Arnold (1978), Ogden v. City of Vancouver (2001), British Columbia (Workers' Compensation Board) v. Fraser Health Authority (2020)). Martial arts training is explicitly recognized as an activity where participants assume the risk, making waivers of this breadth fully enforceable.</p>

                                <p class="fw-bold text-danger mt-2">IMPORTANT NOTICE: By signing this Agreement, you acknowledge that martial arts training wherever it occurs carries serious, permanent risks. You are solely responsible for your safety and for indemnifying Kaiten Mixed Martial Arts Academy and Fitness Ltd. against any claim arising from your participation. If you do not understand any provision of this Agreement, do not sign — seek independent legal advice.</p>
                            </div>

                            <div class="form-check mt-2 mb-1">
                                <input type="checkbox" name="waiver_agreement_read" value="1" id="waiver_agreement_read" class="form-check-input" required {{ old('waiver_agreement_read') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="waiver_agreement_read">I have read and fully understand the above Waiver, Release of Liability, Assumption of Risk & Indemnification Agreement.</label>
                            </div>
                        </div>

                        {{-- ============================================ --}}
                        {{-- Signature & Final Acceptance --}}
                        {{-- ============================================ --}}

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

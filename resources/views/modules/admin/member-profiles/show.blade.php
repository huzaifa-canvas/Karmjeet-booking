
@extends('layouts.master')
@section('title','Member Application | '.config('app.name'))

@section('content')

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Member Application — {{ $user->name }}</h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('admin.member-profiles.index') }}" class="btn btn-outline-secondary btn-sm me-1">
                        <i data-feather='arrow-left'></i> Back
                    </a>
                    <a href="{{ route('admin.member-profiles.edit', $user->id) }}" class="btn btn-primary btn-sm">
                        <i data-feather='edit'></i> Edit
                    </a>
                </div>
            </div>

            <div class="content-body">
                @php $p = $user->profile; @endphp

                @if(!$p)
                    <div class="alert alert-warning">
                        <i data-feather='alert-triangle'></i> This user has not completed their profile yet.
                    </div>
                @else

                {{-- Registration Information --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Registration Information</h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Registration Type</strong>
                                <span>{{ $p->registration_type == 'adult' ? 'Adult /Parent' : 'Minor' }}</span>
                            </div>
                            @if($p->registration_type == 'minor')
                                <div class="col-md-4 mb-1">
                                    <strong class="d-block text-muted small">Minor Full Name</strong>
                                    <span>{{ $p->minor_full_name ?? '—' }}</span>
                                </div>
                            @endif
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Date of Birth</strong>
                                <span>{{ $p->date_of_birth?->format('d-M-Y') ?? '—' }}</span>
                            </div>
                            <div class="col-md-2 mb-1">
                                <strong class="d-block text-muted small">Age</strong>
                                <span>{{ $p->age ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Phone Number</strong>
                                <span>{{ $p->phone_number ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Email</strong>
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Address</h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-12 mb-1">
                                <strong class="d-block text-muted small">Address</strong>
                                <span>{{ $p->address ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">City</strong>
                                <span>{{ $p->city ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Province</strong>
                                <span>{{ $p->province ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Postal Code</strong>
                                <span>{{ $p->postal_code ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Emergency Contact</h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Emergency Contact Name</strong>
                                <span>{{ $p->emergency_contact_name ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Relationship</strong>
                                <span>{{ $p->emergency_contact_relationship ?? '—' }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <strong class="d-block text-muted small">Phone Number</strong>
                                <span>{{ $p->emergency_contact_phone ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Training Background --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Training Background & Goals</h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <strong class="d-block text-muted small">How Did You Hear About Us?</strong>
                                <span>{{ $p->how_heard ?? '—' }}</span>
                            </div>
                            <div class="col-md-6 mb-1">
                                <strong class="d-block text-muted small">Prior Martial Arts Experience?</strong>
                                <span>{{ $p->prior_experience ? 'Yes' : 'No' }}</span>
                                @if($p->prior_experience && $p->experience_details)
                                    <br><small class="text-muted">Style/School: {{ $p->experience_details }}</small>
                                @endif
                            </div>
                            <div class="col-12 mb-1">
                                <strong class="d-block text-muted small">Goals for Training</strong>
                                @if(is_array($p->goals) && count($p->goals) > 0)
                                    @foreach($p->goals as $goal)
                                        <span class="badge bg-light-primary me-50 mb-50">{{ $goal }}</span>
                                    @endforeach
                                @else
                                    <span>—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Physical Activity Readiness --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Physical Activity Readiness</h4>
                    </div>
                    <div class="card-body pt-2">
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
                            $readiness = is_array($p->physical_readiness) ? $p->physical_readiness : [];
                        @endphp
                        @foreach($questions as $id => $question)
                            <div class="d-flex align-items-start mb-1">
                                <span class="me-1 fw-bold">{{ $id }}.</span>
                                <div>
                                    <span>{{ $question }}</span>
                                    @php $ans = $readiness[$id]['answer'] ?? 'no'; @endphp
                                    <span class="badge {{ $ans == 'yes' ? 'bg-warning' : 'bg-success' }} ms-1">{{ ucfirst($ans) }}</span>
                                    @if($ans == 'yes' && !empty($readiness[$id]['explanation']))
                                        <br><small class="text-muted">{{ $readiness[$id]['explanation'] }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-1 pt-1 border-top">
                            <strong class="text-muted small">Consent to background check if assisting classes (18+)?</strong>
                            <span class="badge {{ $p->consent_background_check ? 'bg-success' : 'bg-secondary' }}">{{ $p->consent_background_check ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Agreements --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Agreements & Waiver</h4>
                    </div>
                    <div class="card-body pt-2">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td>Media Release Consent</td>
                                    <td class="text-center" style="width:100px;">
                                        @if($p->media_release_consent)
                                            <span class="badge bg-success">Agreed</span>
                                        @else
                                            <span class="badge bg-danger">Not Agreed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Non-Compete Agreement (12-Month Term)</td>
                                    <td class="text-center">
                                        @if($p->non_compete_agreement)
                                            <span class="badge bg-success">Agreed</span>
                                        @else
                                            <span class="badge bg-danger">Not Agreed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Criminal Record Check</td>
                                    <td class="text-center">
                                        @if($p->criminal_record_agreement)
                                            <span class="badge bg-success">Agreed</span>
                                        @else
                                            <span class="badge bg-danger">Not Agreed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Waiver of Liability & Assumption of Risk</td>
                                    <td class="text-center">
                                        @if($p->waiver_agreement)
                                            <span class="badge bg-success">Agreed</span>
                                        @else
                                            <span class="badge bg-danger">Not Agreed</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Signature --}}
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Signature & Final Acceptance</h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <strong class="d-block text-muted small">Signature</strong>
                                <span class="fs-4 fst-italic">{{ $p->signature ?? '—' }}</span>
                            </div>
                            <div class="col-md-3 mb-1">
                                <strong class="d-block text-muted small">Date</strong>
                                <span>{{ $p->form_date?->format('d-M-Y') ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @endif
            </div>
        </div>
    </div>

@endsection

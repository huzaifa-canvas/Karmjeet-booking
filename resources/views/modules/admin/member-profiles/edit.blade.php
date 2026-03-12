
@extends('layouts.master')
@section('title','Edit Member Profile | '.config('app.name'))

@section('content')

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Edit Application — {{ $user->name }}</h2>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('admin.member-profiles.show', $user->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i data-feather='arrow-left'></i> Back to Profile
                    </a>
                </div>
            </div>

            <div class="content-body">
                @php $p = $user->profile; @endphp

                @if(!$p)
                    <div class="alert alert-warning">
                        <i data-feather='alert-triangle'></i> This user has not submitted a profile yet. There is nothing to edit.
                    </div>
                @else

                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.member-profiles.update', $user->id) }}">
                    @csrf

                    {{-- Registration Information --}}
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Registration Information</h4>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Registration Type</label>
                                    <select name="registration_type" class="form-select">
                                        <option value="adult" {{ $p->registration_type == 'adult' ? 'selected' : '' }}>Adult /Parent</option>
                                        <option value="minor" {{ $p->registration_type == 'minor' ? 'selected' : '' }}>Minor</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Minor Full Name:</label>
                                    <input type="text" name="minor_full_name" class="form-control" value="{{ old('minor_full_name', $p->minor_full_name) }}">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Date of Birth:</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $p->date_of_birth?->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label class="form-label">Age:</label>
                                    <input type="number" name="age" class="form-control" value="{{ old('age', $p->age) }}" required>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $p->phone_number) }}" required>
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
                                    <label class="form-label">Address:</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $p->address) }}" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">City:</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $p->city) }}" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Province:</label>
                                    <input type="text" name="province" class="form-control" value="{{ old('province', $p->province) }}" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Postal Code:</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $p->postal_code) }}" required>
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
                                    <label class="form-label">Emergency Contact Name:</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $p->emergency_contact_name) }}" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Relationship:</label>
                                    <input type="text" name="emergency_contact_relationship" class="form-control" value="{{ old('emergency_contact_relationship', $p->emergency_contact_relationship) }}" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label">Phone Number:</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $p->emergency_contact_phone) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Background --}}
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Training Background</h4>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">How Did You Hear About Us?</label>
                                    <input type="text" name="how_heard" class="form-control" value="{{ old('how_heard', $p->how_heard) }}">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Prior Martial Arts Experience?</label>
                                    <select name="prior_experience" class="form-select">
                                        <option value="1" {{ $p->prior_experience ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$p->prior_experience ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Style/School:</label>
                                    <input type="text" name="experience_details" class="form-control" value="{{ old('experience_details', $p->experience_details) }}">
                                </div>
                            </div>
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
                                    <label class="form-label">Signature:</label>
                                    <input type="text" name="signature" class="form-control" value="{{ old('signature', $p->signature) }}">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Date:</label>
                                    <input type="date" name="form_date" class="form-control" value="{{ old('form_date', $p->form_date?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <button type="submit" class="btn btn-primary btn-lg shadow">
                            <i data-feather='save'></i> Update Member Profile
                        </button>
                    </div>
                </form>

                @endif
            </div>
        </div>
    </div>

@endsection


@extends('layouts.master')
@section('title', ($isEdit ? 'Edit' : 'Create') . ' Class | ' . config('app.name'))

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">{{ $isEdit ? 'Edit' : 'Create' }} Class</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('schedule-session-list') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-feather='arrow-left'></i> Back to List
                    </a>
                </div>
            </div>

            <div class="content-body">

                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

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

                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Class Information</h4>
                    </div>
                    <div class="card-body pt-2">
                        <form method="POST" action="{{ $isEdit ? route('schedule-session-update', $class->id) : route('schedule-session-store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                {{-- Class Name --}}
                                <div class="col-md-6 mb-1">
                                    <label class="form-label fw-bold">Class Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required value="{{ old('name', $class->name ?? '') }}" placeholder="e.g. Muay Thai Kickboxing | Adults (Co-Ed)">
                                </div>

                                {{-- Instructor --}}
                                <div class="col-md-3 mb-1">
                                    <label class="form-label fw-bold">Instructor</label>
                                    <input type="text" name="instructor" class="form-control" value="{{ old('instructor', $class->instructor ?? '') }}" placeholder="e.g. Prof. Chad">
                                </div>

                                {{-- Price --}}
                                <div class="col-md-3 mb-1">
                                    <label class="form-label fw-bold">Price ($)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $class->price ?? '') }}" placeholder="0.00">
                                </div>
                            </div>

                            <div class="row">
                                {{-- Category --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        @foreach(\App\Models\MartialArtsClass::CATEGORIES as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $class->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Type --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Select Type</option>
                                        @foreach(\App\Models\MartialArtsClass::TYPES as $t)
                                            <option value="{{ $t }}" {{ old('type', $class->type ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Level --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Level <span class="text-danger">*</span></label>
                                    <select name="level" class="form-select" required>
                                        @foreach(\App\Models\MartialArtsClass::LEVELS as $l)
                                            <option value="{{ $l }}" {{ old('level', $class->level ?? 'All Levels') == $l ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Age Group --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Age Group</label>
                                    <select name="age_group" class="form-select">
                                        <option value="">Select Age Group</option>
                                        @foreach(\App\Models\MartialArtsClass::AGE_GROUPS as $ag)
                                            <option value="{{ $ag }}" {{ old('age_group', $class->age_group ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Format --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Format</label>
                                    <select name="format" class="form-select">
                                        <option value="">Select Format</option>
                                        @foreach(\App\Models\MartialArtsClass::FORMATS as $f)
                                            <option value="{{ $f }}" {{ old('format', $class->format ?? '') == $f ? 'selected' : '' }}>{{ $f }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-4 mb-1">
                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="active" {{ old('status', $class->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $class->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Image Upload --}}
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label fw-bold">Class Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Max 2MB. Formats: JPG, PNG, WEBP</small>
                                </div>
                                <div class="col-md-6 mb-1">
                                    @if($isEdit && $class->image)
                                        <label class="form-label fw-bold">Current Image</label>
                                        <div>
                                            <img src="{{ asset($class->image) }}" alt="{{ $class->name }}" class="rounded" style="max-height: 120px; object-fit: cover;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="row">
                                <div class="col-12 mb-1">
                                    <label class="form-label fw-bold">Description / Schedule Details</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="e.g. Monday & Wednesday 7:00-8:00 PM, Tuesday & Thursday 6:00-7:00 PM&#10;Includes sparring, pad work, and conditioning.">{{ old('description', $class->description ?? '') }}</textarea>
                                    <small class="text-muted">Add weekly schedule details, days, times, and any other notes here.</small>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather='save'></i> {{ $isEdit ? 'Update Class' : 'Create Class' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

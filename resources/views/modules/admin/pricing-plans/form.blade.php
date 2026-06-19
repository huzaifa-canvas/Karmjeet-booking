@extends('layouts.master')
@section('title', ($isEdit ? 'Edit' : 'Create') . ' Pricing Plan | ' . config('app.name'))

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ $isEdit ? 'Edit' : 'Create' }} Pricing Plan</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <a href="{{ route('admin.pricing-plans.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-feather="arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Plan Details</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $isEdit ? route('admin.pricing-plans.update', $plan->id) : route('admin.pricing-plans.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Plan Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name ?? '') }}" required placeholder="e.g. 2 Classes per Week">
                            </div>
                            
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Linked Class (Leave empty for Global passes)</label>
                                <select class="form-select" name="martial_arts_class_id">
                                    <option value="">-- Global Option (Available for all) --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('martial_arts_class_id', $plan->martial_arts_class_id ?? '') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label class="form-label fw-bold">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $plan->price ?? '') }}" required placeholder="150.00">
                            </div>

                            <div class="col-md-4 mb-1">
                                <label class="form-label fw-bold">Interval <span class="text-danger">*</span></label>
                                <select class="form-select" name="interval" required>
                                    <option value="monthly" {{ old('interval', $plan->interval ?? '') == 'monthly' ? 'selected' : '' }}>Monthly (Recurring)</option>
                                    <option value="weekly" {{ old('interval', $plan->interval ?? '') == 'weekly' ? 'selected' : '' }}>Weekly (Recurring)</option>
                                    <option value="one-time" {{ old('interval', $plan->interval ?? '') == 'one-time' ? 'selected' : '' }}>One-Time Payment</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-1">
                                <label class="form-label fw-bold">Class Limit / Week</label>
                                <input type="number" name="class_limit_per_week" class="form-control" value="{{ old('class_limit_per_week', $plan->class_limit_per_week ?? '') }}" placeholder="e.g. 2 (Leave empty for unlimited)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-3 mb-1">
                                <div class="form-check form-switch mt-1">
                                    <input type="hidden" name="is_tax_inclusive" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_tax_inclusive" name="is_tax_inclusive" value="1" {{ old('is_tax_inclusive', $plan->is_tax_inclusive ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_tax_inclusive">Price includes Tax?</label>
                                </div>
                            </div>

                            <div class="col-md-3 mb-1">
                                <div class="form-check form-switch mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">Status Active?</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> {{ $isEdit ? 'Update Plan' : 'Save Plan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

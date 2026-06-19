@extends('layouts.master')
@section('title', ($isEdit ? 'Edit' : 'Create') . ' Discount Coupon | ' . config('app.name'))

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">{{ $isEdit ? 'Edit' : 'Create' }} Discount Coupon</h2>
            </div>
            <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                <a href="{{ route('admin.discount-coupons.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="content-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body pt-2">
                    <form method="POST" action="{{ $isEdit ? route('admin.discount-coupons.update', $coupon->id) : route('admin.discount-coupons.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control text-uppercase" required value="{{ old('code', $coupon->code ?? '') }}" placeholder="e.g. SUMMER20">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Coupon Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name ?? '') }}" placeholder="e.g. Summer Sale 2026">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="percentage" {{ old('type', $coupon->type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="value" class="form-control" required value="{{ old('value', $coupon->value ?? '') }}" placeholder="e.g. 20">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Minimum Order Amount ($)</label>
                                <input type="number" step="0.01" min="0" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}" placeholder="e.g. 50">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Maximum Uses (Total)</label>
                                <input type="number" step="1" min="1" name="max_uses" class="form-control" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="Leave blank for unlimited">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <label class="form-label fw-bold">Valid From</label>
                                <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', isset($coupon->valid_from) ? $coupon->valid_from->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label fw-bold">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', isset($coupon->valid_until) ? $coupon->valid_until->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-4 mb-1 mt-2">
                                <div class="form-check form-switch mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">Is Active?</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather='save'></i> {{ $isEdit ? 'Update Coupon' : 'Create Coupon' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

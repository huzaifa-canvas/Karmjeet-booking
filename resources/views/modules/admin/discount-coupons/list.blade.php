@extends('layouts.master')
@section('title', 'Discount Coupons | ' . config('app.name'))

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Discount Coupons</h2>
            </div>
            <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                <a href="{{ route('admin.discount-coupons.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Add New Coupon
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

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Valid Until</th>
                                    <th>Uses</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td><strong>{{ $coupon->code }}</strong></td>
                                        <td>{{ ucfirst($coupon->type) }}</td>
                                        <td>{{ $coupon->type == 'percentage' ? rtrim(rtrim($coupon->value, '0'), '.') . '%' : '$' . $coupon->value }}</td>
                                        <td>{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'Never' }}</td>
                                        <td>{{ $coupon->used_count }} / {{ $coupon->max_uses ?: '∞' }}</td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge rounded-pill badge-light-success">Active</span>
                                            @else
                                                <span class="badge rounded-pill badge-light-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.discount-coupons.edit', $coupon->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <a href="{{ route('admin.discount-coupons.delete', $coupon->id) }}" class="btn btn-sm btn-icon btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this coupon?');">
                                                <i data-feather="trash-2"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No coupons found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

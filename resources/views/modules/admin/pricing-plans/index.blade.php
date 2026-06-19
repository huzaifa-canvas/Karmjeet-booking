@extends('layouts.master')
@section('title', 'Pricing Plans | ' . config('app.name'))

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Pricing Plans</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <a href="{{ route('admin.pricing-plans.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> Add New Plan
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if(session('status'))
                <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Filter Plans</h4>
                </div>
                <div class="card-body mt-2">
                    <form method="GET" action="{{ route('admin.pricing-plans.index') }}" class="row gy-1 gx-2 align-items-center">
                        <div class="col-md-4">
                            <select class="form-select" name="class_id">
                                <option value="">All Classes</option>
                                <option value="global" {{ request('class_id') == 'global' ? 'selected' : '' }}>-- Global Plans (No Class) --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="interval">
                                <option value="">All Intervals</option>
                                <option value="monthly" {{ request('interval') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="weekly" {{ request('interval') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="one-time" {{ request('interval') == 'one-time' ? 'selected' : '' }}>One-Time</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i data-feather="filter"></i> Filter</button>
                            <a href="{{ route('admin.pricing-plans.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Linked Class</th>
                                <th>Price</th>
                                <th>Interval</th>
                                <th>Limit/Week</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td class="fw-bold">{{ $plan->name }}</td>
                                <td>
                                    @if($plan->martial_arts_class_id)
                                        <span class="badge badge-light-info">{{ $plan->martialArtsClass->name }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">Global (All)</span>
                                    @endif
                                </td>
                                <td>${{ number_format($plan->price, 2) }} {!! $plan->is_tax_inclusive ? '<small class="text-muted">(Inc. Tax)</small>' : '' !!}</td>
                                <td><span class="badge badge-light-primary text-capitalize">{{ $plan->interval }}</span></td>
                                <td>{{ $plan->class_limit_per_week ?? 'Unlimited' }}</td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('admin.pricing-plans.edit', $plan->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.pricing-plans.delete', $plan->id) }}" onclick="return confirm('Are you sure you want to delete this plan?');">
                                                <i data-feather="trash" class="me-50"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">No pricing plans found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

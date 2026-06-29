@extends('layouts.master')
@section('title', 'Manage Subscriptions | ' . config('app.name'))

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Manage Subscriptions</h2>
            </div>
        </div>
        
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body pt-1 pb-0">
                    <form action="{{ route('admin.subscription.index') }}" method="GET" class="row align-items-end mb-1">
                        <div class="col-md-2 mb-1">
                            <label class="form-label small fw-bold">Search User</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="User Name">
                        </div>
                        <div class="col-md-2 mb-1">
                            <label class="form-label small fw-bold">Filter by Schedule</label>
                            <select name="class_id" class="form-select form-select-sm">
                                <option value="">All Schedules</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-1">
                            <label class="form-label small fw-bold">Date</label>
                            <input type="text" name="date" class="form-control form-control-sm flatpickr-range bg-white" placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2 mb-1">
                            <label class="form-label small fw-bold">Package</label>
                            <select name="package" class="form-select form-select-sm">
                                <option value="">All Packages</option>
                                <option value="normal" {{ request('package') == 'normal' ? 'selected' : '' }}>Standard</option>
                                <option value="unlimited" {{ request('package') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                <option value="day_pass" {{ request('package') == 'day_pass' ? 'selected' : '' }}>Day Pass</option>
                                <option value="weekly_pass" {{ request('package') == 'weekly_pass' ? 'selected' : '' }}>Weekly Pass</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1 d-flex gap-50">
                            <button type="submit" class="btn btn-primary btn-sm w-100" title="Filter"><i data-feather="search"></i></button>
                            <a href="{{ route('admin.subscription.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Clear Filters" style="padding: 0.386rem 0.5rem;"><i data-feather="x"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Schedule</th>
                                <th>Package</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Next Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>
                                    @if($sub->user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary me-50">
                                            <div class="avatar-content">{{ strtoupper(substr($sub->user->name, 0, 1)) }}</div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bolder">{{ $sub->user->name }}</span>
                                            <small class="text-muted">{{ $sub->user->email }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $sub->martialArtsClass->name ?? 'N/A' }}
                                    @if($sub->selected_location)
                                        <br><small class="text-primary"><i data-feather="map-pin" style="width:11px;height:11px;"></i> {{ $sub->selected_location }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($sub->package_type === 'unlimited')
                                        <span class="badge bg-primary">Unlimited</span>
                                    @elseif($sub->package_type === 'day_pass')
                                        <span class="badge bg-info">Day Pass</span>
                                    @elseif($sub->package_type === 'weekly_pass')
                                        <span class="badge bg-info">Weekly Pass</span>
                                    @else
                                        <span class="badge bg-secondary">Standard</span>
                                    @endif
                                </td>
                                <td>${{ number_format($sub->price, 2) }}</td>
                                <td>
                                    <span class="status-badge bg-light-{{ $sub->status == 'active' ? 'success' : ($sub->status == 'expired' ? 'danger' : 'secondary') }} text-{{ $sub->status == 'active' ? 'success' : ($sub->status == 'expired' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($sub->next_payment_date)
                                        {{ $sub->next_payment_date->format('M d, Y') }}
                                    @elseif(in_array($sub->package_type, ['day_pass', 'weekly_pass']))
                                        @php
                                            $days = $sub->package_type === 'day_pass' ? 1 : 7;
                                            $expiresAt = $sub->created_at ? $sub->created_at->copy()->addDays($days) : null;
                                        @endphp
                                        @if($expiresAt)
                                            <div class="d-flex flex-column">
                                                <span>{{ $expiresAt->format('M d, Y') }}</span>
                                                <small class="text-{{ $sub->status == 'expired' ? 'danger' : 'success' }}">{{ $sub->status == 'expired' ? 'Expired' : 'Valid Until' }}</small>
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @php $latestPayment = $sub->payments->first(); @endphp
                                    @if($latestPayment && $latestPayment->stripe_invoice_url)
                                        <a href="{{ $latestPayment->stripe_invoice_url }}" target="_blank" class="btn btn-sm btn-icon btn-neutral" title="View Latest Invoice">
                                            <i data-feather="file-text" class="text-primary font-medium-3"></i>
                                        </a>
                                    @endif

                                    @if($sub->status == 'active' && !in_array($sub->package_type, ['day_pass', 'weekly_pass']))
                                    <form action="{{ route('admin.subscription.cancel', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Arre you sure you want to cancel this subscription?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-icon btn-neutral" title="Cancel Subscription">
                                            <i data-feather="slash" class="text-danger font-medium-3"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-2">No subscriptions found matching the criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') { feather.replace(); }
        $('.flatpickr-range').flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d'
        });
    });
</script>
@endsection

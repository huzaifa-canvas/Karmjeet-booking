@extends('layouts.master')
@section('title', 'My Subscriptions | ' . config('app.name'))

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<style>
    .sub-table thead th {
        background-color: #f3f2f7 !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #5e5873;
        border: none !important;
    }
    .sub-table td {
        vertical-align: middle;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .status-active { background-color: #e8f9ed; color: #28c76f; }
    .status-canceled { background-color: #f2f2f3; color: #82868b; }
    
    .invoice-item {
        transition: background 0.2s;
    }
    .invoice-item:hover {
        background-color: #f8f8f8;
    }
    /* Fixed dropdown styling for Vuexy */
    .dropdown-toggle.hide-arrow::after {
        display: none !important;
    }
    .action-column {
        width: 80px;
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">My Subscriptions</h2>
            </div>
        </div>
        
        <div class="content-body">
            <div class="card mb-2">
                <div class="card-body py-1">
                    <form action="{{ route('user.subscription.list') }}" method="GET" class="row g-1 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Date</label>
                            <input type="text" name="date" class="form-control form-control-sm flatpickr-range bg-white" placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Package Type</label>
                            <select name="package" class="form-select form-select-sm">
                                <option value="">All Packages</option>
                                <option value="normal" {{ request('package') == 'normal' ? 'selected' : '' }}>Standard</option>
                                <option value="unlimited" {{ request('package') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                <option value="day_pass" {{ request('package') == 'day_pass' ? 'selected' : '' }}>Day Pass</option>
                                <option value="weekly_pass" {{ request('package') == 'weekly_pass' ? 'selected' : '' }}>Weekly Pass</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                            <a href="{{ route('user.subscription.list') }}" class="btn btn-outline-secondary btn-sm flex-grow-1">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($subscriptions->isEmpty())
                <div class="card p-3 text-center shadow-none border">
                    <div class="card-body">
                        <i data-feather="calendar" class="mb-1 text-muted" style="width: 48px; height: 48px;"></i>
                        <h4>No subscriptions found</h4>
                        <p class="text-muted">You haven't subscribed to any schedules yet.</p>
                        <a href="{{ route('user.schedule-session-list') }}" class="btn btn-primary">Browse Available Classes</a>
                    </div>
                </div>
            @else
                <div class="card shadow-none border">
                    <div class="table-responsive">
                        <table class="table sub-table mb-0">
                            <thead>
                                <tr>
                                    <th>Class / Schedule</th>
                                    <th>Package</th>
                                    <th>Buy Date</th>
                                    <th>Price</th>
                                    <th>Next Payment</th>
                                    <th>Status</th>
                                    <th class="text-center action-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content"><i data-feather="book-open" class="font-medium-3"></i></div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bolder">{{ $sub->martialArtsClass->name }}</h6>
                                                <small class="text-muted">{{ $sub->martialArtsClass->category }}</small>
                                                @if($sub->selected_location)
                                                    <br><small class="text-primary"><i data-feather="map-pin" style="width:11px;height:11px;"></i> {{ $sub->selected_location }}</small>
                                                @endif
                                            </div>
                                        </div>
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
                                    <td>
                                        <span class="fw-bold">{{ $sub->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($sub->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($sub->status == 'active' && $sub->next_payment_date)
                                                <span class="fw-bold">{{ $sub->next_payment_date->format('M d, Y') }}</span>
                                                <small class="text-muted small">Auto-renewal active</small>
                                            @elseif(in_array($sub->package_type, ['day_pass', 'weekly_pass']))
                                                @php
                                                    $days = $sub->package_type === 'day_pass' ? 1 : 7;
                                                    $expiresAt = $sub->created_at ? $sub->created_at->copy()->addDays($days) : null;
                                                @endphp
                                                @if($expiresAt)
                                                    <span class="fw-bold">{{ $expiresAt->format('M d, Y') }}</span>
                                                    <small class="text-{{ $sub->status == 'expired' ? 'danger' : 'success' }} small">
                                                        {{ $sub->status == 'expired' ? 'Expired' : 'Valid Until' }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $sub->status }}">
                                            {{ strtoupper($sub->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <!-- History Toggle Button -->
                                            <button class="btn btn-sm btn-icon btn-flat-primary me-50" 
                                                    type="button" 
                                                    data-toggle="collapse" 
                                                    data-bs-toggle="collapse" 
                                                    data-target="#invoices-{{ $sub->id }}" 
                                                    data-bs-target="#invoices-{{ $sub->id }}" 
                                                    title="Payment History">
                                                <i data-feather="file-text"></i>
                                            </button>

                                            @if($sub->status == 'active' && !in_array($sub->package_type, ['day_pass', 'weekly_pass']))
                                                @if($sub->cancellationRequest && $sub->cancellationRequest->status === 'pending')
                                                    <span class="badge bg-light-warning text-warning" title="Cancellation Requested">
                                                        <i data-feather="clock" class="font-small-3"></i> Pending
                                                    </span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-icon btn-flat-danger"
                                                            title="Request Cancellation"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cancelModal-{{ $sub->id }}">
                                                        <i data-feather="x-circle"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expandable Invoices Section -->
                                <tr class="collapse" id="invoices-{{ $sub->id }}">
                                    <td colspan="6" class="p-0 border-0">
                                        <div class="bg-light-primary p-2">
                                            <h6 class="mb-1 fw-bolder">Recent Payment Activities</h6>
                                            @forelse($sub->payments as $payment)
                                            <div class="row align-items-center py-1 rounded bg-white mx-0 mb-50 shadow-sm">
                                                <div class="col-4 ps-2 small">
                                                    {{ $payment->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="col-4 text-center fw-bolder small">
                                                    ${{ number_format($payment->amount, 2) }}
                                                </div>
                                                <div class="col-4 text-end pe-2">
                                                    <a href="{{ route('user.subscription.invoice', $payment->id) }}" class="btn btn-sm btn-flat-primary" title="Download Invoice">
                                                        <i data-feather="download" class="font-small-3"></i> PDF
                                                    </a>
                                                    @if($payment->stripe_invoice_url)
                                                    <a href="{{ $payment->stripe_invoice_url }}" target="_blank" class="btn btn-sm btn-flat-secondary" title="View in Stripe">
                                                        <i data-feather="external-link" class="font-small-3"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                            @empty
                                            <p class="text-center text-muted py-1 m-0">No payment records found.</p>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                                
                                @if($sub->status == 'active' && !in_array($sub->package_type, ['day_pass', 'weekly_pass']) && (!$sub->cancellationRequest || $sub->cancellationRequest->status !== 'pending'))
                                <!-- Cancel Modal -->
                                <div class="modal fade" id="cancelModal-{{ $sub->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Request Cancellation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('user.subscription.cancel', $sub->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-warning p-1">
                                                        <h6 class="alert-heading fw-bolder mb-50">60-Day Cancellation Policy</h6>
                                                        <div class="font-small-3">
                                                            As per our policy, a 60-day notice is required for all cancellations. 
                                                            Your request will be reviewed by our administration team.
                                                        </div>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label" for="notes">Reason for Cancellation (Optional)</label>
                                                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Please let us know why you're leaving..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Submit Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center mt-2">
                    {{ $subscriptions->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
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

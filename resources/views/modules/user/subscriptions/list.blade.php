@extends('layouts.master')
@section('title', 'My Subscriptions | ' . config('app.name'))

@section('style')
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
                                    <th>Monthly Price</th>
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
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($sub->martialArtsClass->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($sub->status == 'active' && $sub->next_payment_date)
                                                <span class="fw-bold">{{ $sub->next_payment_date->format('M d, Y') }}</span>
                                                <small class="text-muted small">Auto-renewal active</small>
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

                                            @if($sub->status == 'active')
                                                <form action="{{ route('user.subscription.cancel', $sub->id) }}" method="POST" onsubmit="return confirm('Confirm cancellation?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-icon btn-flat-danger" title="Cancel Plan">
                                                        <i data-feather="x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expandable Invoices Section -->
                                <tr class="collapse" id="invoices-{{ $sub->id }}">
                                    <td colspan="5" class="p-0 border-0">
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
                                                    @if($payment->stripe_invoice_url)
                                                    <a href="{{ $payment->stripe_invoice_url }}" target="_blank" class="btn btn-sm btn-flat-primary">
                                                        <i data-feather="download" class="font-small-3"></i>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    if (typeof feather !== 'undefined') { feather.replace(); }
</script>
@endsection

@extends('layouts.master')
@section('title', 'Manage Subscriptions | ' . config('app.name'))

@section('style')
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
                <div class="card-body pt-1">
                    <form action="{{ route('admin.subscription.index') }}" method="GET" class="row">
                        <div class="col-md-3 mb-1">
                            <label class="form-label">Search User</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="User Name">
                        </div>
                        <div class="col-md-3 mb-1">
                            <label class="form-label">Filter by Schedule</label>
                            <select name="class_id" class="form-select">
                                <option value="">All Schedules</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-1">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2 mb-1 d-flex align-items-end">
                            <a href="{{ route('admin.subscription.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
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
                                <th>Monthly Price</th>
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
                                <td>{{ $sub->martialArtsClass->name ?? 'N/A' }}</td>
                                <td>${{ number_format($sub->martialArtsClass->price, 2) }}</td>
                                <td>
                                    <span class="status-badge bg-light-{{ $sub->status == 'active' ? 'success' : 'secondary' }} text-{{ $sub->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td>{{ $sub->next_payment_date ? $sub->next_payment_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @php $latestPayment = $sub->payments->first(); @endphp
                                    @if($latestPayment && $latestPayment->stripe_invoice_url)
                                        <a href="{{ $latestPayment->stripe_invoice_url }}" target="_blank" class="btn btn-sm btn-icon btn-neutral" title="View Latest Invoice">
                                            <i data-feather="file-text" class="text-primary font-medium-3"></i>
                                        </a>
                                    @endif

                                    @if($sub->status == 'active')
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
<script>
    if (typeof feather !== 'undefined') { feather.replace(); }
</script>
@endsection

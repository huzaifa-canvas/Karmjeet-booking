
@extends('layouts.master')
@section('title','Orders | '.config('app.name'))
@section('style')
    <style>
        .status-badge-pending { background-color: #ff9f43; }
        .status-badge-processing { background-color: #00cfe8; }
        .status-badge-completed { background-color: #28c76f; }
        .status-badge-cancelled { background-color: #ea5455; }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Orders</h2>
                </div>
            </div>

            <div class="content-body">
                {{-- Filter --}}
                <div class="card">
                    <div class="card-body py-1">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-1 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Order # or customer name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i data-feather='filter'></i> Filter</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i data-feather='x'></i></a>
                            </div>
                        </form>
                    </div>
                </div>

                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><span class="fw-bold">{{ $order->order_number }}</span></td>
                                        <td>
                                            @if($order->is_guest)
                                                {{ $order->guest_name }}
                                                <div class="mt-25"><span class="badge bg-light-info">Guest</span></div>
                                            @else
                                                {{ $order->user->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light-primary">{{ $order->items->count() }} items</span></td>
                                        <td><span class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span></td>
                                        <td>
                                            <span class="badge bg-light-{{ $order->payment_method == 'stripe' ? 'info' : 'warning' }}">{{ strtoupper($order->payment_method) }}</span>
                                            <div class="mt-25">{!! $order->payment_status_badge !!}</div>
                                        </td>
                                        <td>{!! $order->status_badge !!}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather='eye' style="width:14px;height:14px"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3">
                                            <p class="text-muted mb-0">No orders found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($orders->hasPages())
                        <div class="card-footer">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        if (typeof feather !== 'undefined') { feather.replace({ width: 14, height: 14 }); }
    </script>
@endsection

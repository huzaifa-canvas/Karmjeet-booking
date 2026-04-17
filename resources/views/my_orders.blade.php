@extends('layouts.master')
@section('title','My Orders | '.config('app.name'))

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">My Orders</h2>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total Items</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td><span class="fw-bold">{{ $order->order_number }}</span></td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                            <td>{!! $order->status_badge !!}</td>
                                            <td>{!! $order->payment_status_badge !!}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    @if($order->payment_method == 'stripe' && $order->payment_status == 'unpaid')
                                                        <a href="{{ route('user.order.pay', $order->id) }}" class="btn btn-sm btn-success">Pay Now</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <h5>You haven't placed any orders yet.</h5>
                                                <a href="{{ route('shop.index') }}" class="btn btn-primary mt-1">Start Shopping</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($orders->hasPages())
                            <div class="mt-2 text-center">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

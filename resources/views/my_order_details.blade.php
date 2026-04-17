@extends('layouts.master')
@section('title','Order Details | '.config('app.name'))

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Order: {{ $order->order_number }}</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary">
                        <i data-feather='arrow-left'></i> Back
                    </a>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-8">
                        {{-- Items --}}
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Order Items</h4></div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset($item->product->image) }}" alt="product" class="me-1" width="40" height="40" style="object-fit:cover; border-radius:5px">
                                                        @endif
                                                        <span class="fw-bold">{{ $item->product_name }}</span>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($item->price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Subtotal</th>
                                            <th>${{ number_format($order->total_amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{-- Status & Summary --}}
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Order Summary</h4></div>
                            <div class="card-body">
                                <p><strong>Status:</strong> {!! $order->status_badge !!}</p>
                                <p><strong>Payment:</strong> {!! $order->payment_status_badge !!}</p>
                                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-1"><strong>Payment Method:</strong> 
                                    @if($order->payment_method == 'cod')
                                        <span class="badge bg-light-info">Cash on Delivery</span>
                                    @else
                                        <span class="badge bg-light-primary">Stripe</span>
                                    @endif
                                </p>
                                @if($order->payment_method == 'stripe' && $order->payment_status == 'unpaid')
                                    <a href="{{ route('user.order.pay', $order->id) }}" class="btn btn-success w-100">Pay Now</a>
                                @endif
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        @if($order->shippingAddress)
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Shipping Address</h4></div>
                                <div class="card-body">
                                    <h6 class="fw-bold">{{ $order->shippingAddress->full_name }}</h6>
                                    <p class="mb-50">{{ $order->shippingAddress->flat_house }}, {{ $order->shippingAddress->landmark }}</p>
                                    <p class="mb-50">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->pincode }}</p>
                                    <p class="mb-0"><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

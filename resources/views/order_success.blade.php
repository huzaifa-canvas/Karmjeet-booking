@extends('layouts.master')
@section('title', 'Order Success')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card text-center mt-3">
                            <div class="card-body py-5">
                                <div class="mb-2">
                                    <div class="avatar avatar-xl bg-light-success p-2 m-auto" style="width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                        <i data-feather="check-circle" style="width:40px;height:40px;color:#28c76f"></i>
                                    </div>
                                </div>
                                <h2 class="text-success mb-1">Order Placed Successfully!</h2>
                                <p class="text-muted mb-2">Thank you for your purchase. Your order has been placed.</p>

                                <div class="card bg-light-primary mb-2 mx-auto" style="max-width: 400px">
                                    <div class="card-body py-1">
                                        <h5 class="mb-50">Order Number</h5>
                                        <h3 class="text-primary fw-bolder">{{ $order->order_number }}</h3>
                                    </div>
                                </div>

                                <div class="table-responsive mx-auto" style="max-width: 500px">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td class="text-start"><strong>Payment Method:</strong></td>
                                                <td class="text-end">{{ strtoupper($order->payment_method) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-start"><strong>Total Amount:</strong></td>
                                                <td class="text-end text-success fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-start"><strong>Status:</strong></td>
                                                <td class="text-end"><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-start"><strong>Items:</strong></td>
                                                <td class="text-end">{{ $order->items->count() }} products</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                @if($order->shippingAddress)
                                    <div class="text-start mx-auto mt-2 p-1 border rounded" style="max-width: 500px">
                                        <h6><i data-feather="map-pin" style="width:14px;height:14px"></i> Shipping To:</h6>
                                        <p class="mb-0">{{ $order->shippingAddress->full_name }}, {{ $order->shippingAddress->flat_house }}, {{ $order->shippingAddress->city }} {{ $order->shippingAddress->pincode }}, {{ $order->shippingAddress->state }}</p>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <a href="{{ route('shop.index') }}" class="btn btn-primary me-1">
                                        <i data-feather="shopping-bag" style="width:14px;height:14px"></i> Continue Shopping
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                        <i data-feather="home" style="width:14px;height:14px"></i> Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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

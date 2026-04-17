
@extends('layouts.master')
@section('title','Order #'.$order->order_number.' | '.config('app.name'))
@section('style')
    <style>
        .order-status-form select { min-width: 150px; }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Order #{{ $order->order_number }}</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i data-feather='arrow-left'></i> Back
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

                <div class="row">
                    {{-- Order Info --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title">Order Items</h4>
                                {!! $order->status_badge !!}
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
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
                                                                <img src="{{ asset($item->product->image) }}" class="rounded me-1" width="40" height="40" style="object-fit:cover">
                                                            @endif
                                                            <span>{{ $item->product_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="fw-bold">${{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                                <td class="fw-bold text-success fs-5">${{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        @if($order->shippingAddress)
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Shipping Address</h4></div>
                                <div class="card-body">
                                    <p class="mb-25"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                                    <p class="mb-25">{{ $order->shippingAddress->flat_house }}</p>
                                    @if($order->shippingAddress->landmark)
                                        <p class="mb-25">Near: {{ $order->shippingAddress->landmark }}</p>
                                    @endif
                                    <p class="mb-25">{{ $order->shippingAddress->city }}{{ $order->shippingAddress->pincode ? ', ' . $order->shippingAddress->pincode : '' }}</p>
                                    <p class="mb-25">{{ $order->shippingAddress->state }}</p>
                                    <p class="mb-0"><i data-feather="phone" style="width:14px;height:14px"></i> {{ $order->shippingAddress->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Side Panel --}}
                    <div class="col-md-4">
                        {{-- Customer Info --}}
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Customer</h4></div>
                            <div class="card-body">
                                <p class="mb-25"><strong>{{ $order->user->name ?? 'N/A' }}</strong></p>
                                <p class="mb-25">{{ $order->user->email ?? '' }}</p>
                                <p class="mb-0">{{ $order->user->phone ?? '' }}</p>
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Payment</h4></div>
                            <div class="card-body">
                                <p class="mb-25"><strong>Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                                <p class="mb-25"><strong>Status:</strong> {!! $order->payment_status_badge !!}</p>
                                @if($order->stripe_session_id)
                                    <p class="mb-25"><strong>Stripe Session:</strong> <small>{{ $order->stripe_session_id }}</small></p>
                                @endif
                                @if($order->stripe_payment_intent)
                                    <p class="mb-0"><strong>Payment Intent:</strong> <small>{{ $order->stripe_payment_intent }}</small></p>
                                @endif
                            </div>
                        </div>

                        {{-- Update Status --}}
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Update Status</h4></div>
                            <div class="card-body order-status-form">
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                                    @csrf
                                    <select name="status" class="form-select mb-1">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <p class="mb-25"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-0"><strong>Last Updated:</strong> {{ $order->updated_at->format('M d, Y h:i A') }}</p>
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

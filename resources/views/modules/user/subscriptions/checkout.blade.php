@extends('layouts.master')
@section('title', 'Order Summary | ' . config('app.name'))

@section('style')
<style>
    .checkout-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .checkout-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
    }
    .class-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2.5rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }
    .class-img {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 0;
        border-bottom: 1px dashed #e0e0e0;
    }
    .price-item:last-of-type {
        border-bottom: none;
    }
    .total-section {
        background-color: #fcfcfc;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border: 1px solid #f0f0f0;
    }
    .trust-badge {
        font-size: 0.8rem;
        color: #82868b;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 1.5rem;
    }
    .btn-pay {
        padding: 1rem !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        border-radius: 10px !important;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <div class="checkout-wrapper">
                <div class="col-12 col-md-5">
                    <div class="card checkout-card">
                        <!-- Upper Detail Section -->
                        <div class="class-header">
                            <img src="{{ asset($class->image ?: 'assets/images/no-preview.png') }}" class="class-img" alt="{{ $class->name }}">
                            <h2 class="fw-bolder mb-0 text-dark">{{ $class->name }}</h2>
                            <p class="text-muted small mb-0">{{ $class->category }} Plan • Premium Level</p>
                        </div>

                        <div class="card-body p-2 p-md-3">
                            <h5 class="fw-bold mb-1 text-uppercase small text-muted font-small-3">Payment Breakdown</h5>
                            
                            <div class="price-item">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-light-secondary me-75">
                                        <div class="avatar-content"><i data-feather="calendar" class="font-medium-3 text-primary"></i></div>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">First Month</span>
                                        <small class="text-muted font-small-2">Subscription fee</small>
                                    </div>
                                </div>
                                <span class="fw-bolder text-dark h5 mb-0">${{ number_format($class->price, 2) }}</span>
                            </div>

                            <div class="price-item">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-light-secondary me-75">
                                        <div class="avatar-content"><i data-feather="shield" class="font-medium-3 text-primary"></i></div>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Security Deposit</span>
                                        <small class="text-muted font-small-2">Refundable/Advance</small>
                                    </div>
                                </div>
                                <span class="fw-bolder text-dark h5 mb-0">${{ number_format($class->price, 2) }}</span>
                            </div>

                            <div class="total-section">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <span class="h5 mb-0 text-muted">Amount Due Today</span>
                                    <span class="h2 mb-0 fw-bolder text-primary">${{ number_format($class->price * 2, 2) }}</span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <i data-feather="alert-circle" class="text-primary me-50" style="width: 14px;"></i>
                                    <small class="text-muted">Next payment will be charged automatically in 1 month.</small>
                                </div>
                            </div>

                            <form action="{{ route('user.subscription.process', $class->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 btn-pay shadow-sm mb-1">
                                    Confirm and Pay Securely
                                </button>
                            </form>
                            
                            <a href="{{ route('user.schedule-session-detail', $class->id) }}" class="btn btn-link w-100 text-muted small">
                                Back to Class Details
                            </a>

                            <div class="trust-badge">
                                <i data-feather="lock" style="width: 12px;"></i>
                                <span>Powered by Stripe | Secure 256-bit SSL Encryption</span>
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
    if (typeof feather !== 'undefined') { feather.replace(); }
</script>
@endsection

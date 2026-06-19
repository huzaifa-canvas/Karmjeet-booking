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
                            </div>
                            
                            <div class="price-item py-1">
                                <div class="d-flex justify-content-between w-100">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold" id="subtotal_display">${{ number_format($taxDetails['subtotal'], 2) }}</span>
                                </div>
                            </div>
                            
                            @if($taxDetails['gst_rate'] > 0)
                            <div class="price-item py-1 border-0">
                                <div class="d-flex justify-content-between w-100">
                                    <span class="text-muted">GST ({{ $taxDetails['gst_rate'] }}%)</span>
                                    <span class="fw-bold" id="gst_display">${{ number_format($taxDetails['gst_amount'], 2) }}</span>
                                </div>
                            </div>
                            @endif

                            @if($taxDetails['pst_rate'] > 0)
                            <div class="price-item py-1 border-0">
                                <div class="d-flex justify-content-between w-100">
                                    <span class="text-muted">PST ({{ $taxDetails['pst_rate'] }}%)</span>
                                    <span class="fw-bold" id="pst_display">${{ number_format($taxDetails['pst_amount'], 2) }}</span>
                                </div>
                            </div>
                            @endif

                            <div class="total-section">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <span class="h5 mb-0 text-muted">Amount Due Today</span>
                                    <span class="h2 mb-0 fw-bolder text-primary" id="total_display">${{ number_format($taxDetails['total'], 2) }}</span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <i data-feather="alert-circle" class="text-primary me-50" style="width: 14px;"></i>
                                    <small class="text-muted">
                                        @if(request('package_type') == 'day_pass' || request('package_type') == 'weekly_pass')
                                            This is a one-time payment. No automatic renewal.
                                        @else
                                            Next payment will be charged automatically every month.
                                        @endif
                                        @if($taxDetails['is_inclusive']) (Prices are Tax Inclusive) @endif
                                    </small>
                                </div>
                            </div>

                            @if(session('status') == 'failed')
                                <div class="alert alert-danger p-1 mt-1 mb-1">
                                    {{ session('message') }}
                                </div>
                            @endif

                            <form action="{{ route('user.subscription.process', $class->id) }}" method="POST" id="checkoutForm">
                                @csrf
                                
                                @if(request('package_type') == 'day_pass' || request('package_type') == 'weekly_pass')
                                    <input type="hidden" name="package_type" value="{{ request('package_type') }}">
                                    <div class="alert alert-info mt-2">
                                        You are purchasing a <strong>{{ request('package_type') == 'day_pass' ? 'Day Pass' : 'Weekly Pass' }}</strong>.
                                    </div>
                                @else
                                    @if($class->unlimited_price)
                                        <div class="mt-2 mb-2 p-2 border rounded" style="background-color: #f8f8f8;">
                                            <h5 class="fw-bolder mb-1">Select Your Package</h5>
                                            
                                            <div class="form-check mb-1">
                                                <input class="form-check-input package-selector" type="radio" name="package_type" id="pkg_normal" value="normal" 
                                                       data-price="{{ $taxDetails['total'] }}"
                                                       data-subtotal="{{ $taxDetails['subtotal'] }}"
                                                       data-gst="{{ $taxDetails['gst_amount'] }}"
                                                       data-pst="{{ $taxDetails['pst_amount'] }}"
                                                       checked>
                                                <label class="form-check-label fw-bold" for="pkg_normal">
                                                    Standard Package (${{ number_format($class->price, 2) }}/mo)
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input package-selector" type="radio" name="package_type" id="pkg_unlimited" value="unlimited" 
                                                       data-price="{{ $taxDetails['unlimited_total'] }}"
                                                       data-subtotal="{{ $taxDetails['unlimited_subtotal'] }}"
                                                       data-gst="{{ $taxDetails['unlimited_gst_amount'] }}"
                                                       data-pst="{{ $taxDetails['unlimited_pst_amount'] }}">
                                                <label class="form-check-label fw-bold text-primary" for="pkg_unlimited">
                                                    Unlimited Package (${{ number_format($class->unlimited_price, 2) }}/mo)
                                                </label>
                                                <div class="badge bg-light-danger text-danger mt-50 d-block text-start w-100 text-wrap" style="line-height:1.4;">
                                                    🔥 Unbelievable Offer: Additional classes available for only ${{ number_format($class->unlimited_price - $class->price, 2) }} extra!
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="package_type" value="normal">
                                    @endif
                                @endif
                                
                                <div class="mb-2 mt-2">
                                    <label class="form-label fw-bold text-muted font-small-3">Have a discount coupon?</label>
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code">
                                    </div>
                                    <small class="text-muted font-small-2">Discount will be applied on the Stripe payment page.</small>
                                </div>

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
    
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.package-selector');
        const totalDisplay = document.getElementById('total_display');
        const subtotalDisplay = document.getElementById('subtotal_display');
        const gstDisplay = document.getElementById('gst_display');
        const pstDisplay = document.getElementById('pst_display');
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const newTotal = parseFloat(this.getAttribute('data-price')).toFixed(2);
                    totalDisplay.innerText = '$' + newTotal;
                    
                    if (subtotalDisplay) {
                        subtotalDisplay.innerText = '$' + parseFloat(this.getAttribute('data-subtotal')).toFixed(2);
                    }
                    if (gstDisplay) {
                        gstDisplay.innerText = '$' + parseFloat(this.getAttribute('data-gst')).toFixed(2);
                    }
                    if (pstDisplay) {
                        pstDisplay.innerText = '$' + parseFloat(this.getAttribute('data-pst')).toFixed(2);
                    }
                }
            });
        });
    });
</script>
@endsection

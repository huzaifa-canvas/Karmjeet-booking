@extends('layouts.master')
@section('title', $class->name . ' | ' . config('app.name'))

@section('style')
<style>
    .class-hero {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
        height: 400px;
    }
    .class-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .class-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);
        padding: 3rem 2rem 2rem;
        color: white;
    }
    .class-badge-top {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #7367f0;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .class-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .detail-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        height: 100%;
    }
    
    .price-tag {
        font-size: 2.5rem;
        color: #28c76f;
        font-weight: 800;
        margin-bottom: 1.5rem;
    }
    
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-list li {
        display: flex;
        justify-content: space-between;
        padding: 1.2rem 0;
        border-bottom: 1px solid #ebe9f1;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-list li span.label {
        color: #6e6b7b;
        font-weight: 600;
        display: flex;
        align-items: center;
        font-size: 1.05rem;
    }
    .info-list li span.value {
        font-weight: 700;
        color: #5e5873;
        text-align: right;
        font-size: 1.05rem;
    }
    
    .schedule-box {
        background: #f8f8f8;
        border-left: 4px solid #7367f0;
        padding: 1.5rem;
        border-radius: 0 8px 8px 0;
        margin-top: 1.5rem;
    }
    
    .schedule-text {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 0;
        color: #5e5873;
        white-space: pre-line;
    }

    .btn-subscribe {
        background: #28c76f;
        color: white;
        font-size: 1.2rem;
        font-weight: 700;
        padding: 15px 30px;
        border-radius: 8px;
        border: none;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(40, 199, 111, 0.4);
    }

    .btn-subscribe:hover {
        background: #24af62;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 199, 111, 0.5);
        color: white;
    }

    .ecommerce-card1 {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
        transition: transform 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .ecommerce-card1:hover { transform: translateY(-5px); }
    .class-img-wrapper { width: 100%; height: 180px; overflow: hidden; position: relative; }
    .class-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .ecommerce-card1:hover .class-img-wrapper img { transform: scale(1.05); }
    .class-badge { position: absolute; top: 10px; right: 10px; background: rgba(115, 103, 240, 0.9); color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
    .class-details { padding: 1rem; text-align: left; flex-grow: 1; display: flex; flex-direction: column; }
    .related-title { color: #5e5873; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.3; }

    @media (max-width: 767.98px){
        .class-hero { height: 300px; }
        .class-title { font-size: 1.8rem; }
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Class Details</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.schedule-session-list') }}">Schedule Classes</a></li>
                        <li class="breadcrumb-item active">{{ $class->name }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">
            
            <div class="mb-2">
                <a href="{{ route('user.schedule-session-list') }}" class="btn btn-outline-primary">
                    <i data-feather="arrow-left" class="me-50"></i> Back to Schedule
                </a>
            </div>

            <!-- Hero Section -->
            <div class="class-hero">
                <img src="{{ asset($class->image ?: 'assets/images/no-preview.png') }}" alt="{{ $class->name }}" />
                <span class="class-badge-top">{{ $class->category }}</span>
                <div class="class-hero-overlay">
                    <h1 class="class-title">{{ $class->name }}</h1>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-1 py-1 px-2" style="font-size:0.9rem;">{{ $class->type }}</span>
                        <span class="badge bg-secondary py-1 px-2" style="font-size:0.9rem;">{{ $class->level }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="row">
                <!-- Left Column: Details -->
                <div class="col-lg-8 mb-2">
                    <div class="detail-card">
                        <h4 class="mb-2"><i data-feather="info" class="me-50 text-primary"></i> Class Information</h4>
                        
                        <ul class="info-list">
                            <li>
                                <span class="label"><i data-feather="users" class="me-50"></i> Age Group</span>
                                <span class="value">{{ $class->age_group ?: 'All Ages' }}</span>
                            </li>
                            <li>
                                <span class="label"><i data-feather="layout" class="me-50"></i> Format</span>
                                <span class="value">{{ $class->format ?: 'Mixed' }}</span>
                            </li>
                            <li>
                                <span class="label"><i data-feather="user" class="me-50"></i> Instructor</span>
                                <span class="value">{{ $class->instructor ?: 'TBA' }}</span>
                            </li>
                        </ul>

                        <h4 class="mt-4 mb-2"><i data-feather="calendar" class="me-50 text-primary"></i> Schedule Details</h4>
                        <div class="schedule-box">
                            <p class="schedule-text">{{ $class->description ?: 'Schedule to be announced. Contact gym for details.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Subscription -->
                <div class="col-lg-4 mb-2">
                    <div class="detail-card d-flex flex-column text-center">
                        <h5 class="text-muted mb-1 mt-2">Membership Fee</h5>
                        <div class="price-tag">${{ number_format($class->price ?? 0, 2) }} <span style="font-size:1rem;color:#6e6b7b;font-weight:600;">/ period</span></div>
                        
                        <hr class="w-100 my-2">
                        
                        <p class="text-muted mb-3">Ready to join this class? Click below to proceed with secure booking and payment.</p>
                        
                        <a href="javascript:void(0)" class="btn btn-subscribe" onclick="alert('Proceed to Stripe payment (Coming Soon!)')">
                            <i data-feather="check-circle" class="me-50"></i> Subscribe / Book
                        </a>
                        <small class="text-muted mt-2 d-block"><i data-feather="shield" class="me-25 font-small-2"></i> Secure payment via Stripe</small>
                    </div>
                </div>
            </div>

            <!-- Related Classes -->
            @if($relatedClasses->count() > 0)
            <div class="related-classes">
                <h3 class="mb-2 text-primary" style="font-weight: 800;">Related {{ $class->category }} Classes</h3>
                <div class="row">
                    @foreach($relatedClasses as $related)
                    <div class="col-md-4 mb-2">
                        <div class="ecommerce-card1">
                            <a href="{{ route('user.schedule-session-detail', $related->id) }}">
                                <div class="class-img-wrapper">
                                    <img src="{{ asset($related->image ?: 'assets/images/no-preview.png') }}" alt="{{ $related->name }}" />
                                    <span class="class-badge">{{ $related->level }}</span>
                                </div>
                            </a>
                            <div class="class-details">
                                <a href="{{ route('user.schedule-session-detail', $related->id) }}">
                                    <h5 class="related-title">{{ Str::limit($related->name, 45) }}</h5>
                                </a>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-1">
                                    <span class="fw-bolder text-success">${{ number_format($related->price ?? 0, 2) }}</span>
                                    <a href="{{ route('user.schedule-session-detail', $related->id) }}" class="btn btn-primary btn-sm">View Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace({ width: 20, height: 20 });
        }
    });
</script>
@endsection

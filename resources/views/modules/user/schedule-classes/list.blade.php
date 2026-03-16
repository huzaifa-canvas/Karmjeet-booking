@extends('layouts.master')
@section('title','Schedule Classes | '.config('app.name'))
@section('style')
    <style>
        .filter-card .form-select { font-size: 0.9rem; }
        .ecommerce-card1 {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .ecommerce-card1:hover {
            transform: translateY(-5px);
        }
        .class-img-wrapper {
            width: 100%;
            height: 180px;
            overflow: hidden;
            position: relative;
        }
        .class-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .ecommerce-card1:hover .class-img-wrapper img {
            transform: scale(1.05);
        }
        .class-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(115, 103, 240, 0.9);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .class-details {
            padding: 1rem;
            text-align: left;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .class-title {
            color: #5e5873;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        .class-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .class-price {
            font-weight: 700;
            color: #28c76f;
            font-size: 1.1rem;
        }
        .class-level {
            font-size: 0.85rem;
            color: #6e6b7b;
            background: #f3f2f7;
            padding: 3px 8px;
            border-radius: 4px;
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
                <h2 class="content-header-title float-start mb-0">Schedule Classes</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Schedule Classes</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">
            
            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body py-1">
                    <form action="{{ route('user.schedule-session-list') }}" method="GET" class="row g-1 align-items-end">
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-bold small">Category</label>
                            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\MartialArtsClass::CATEGORIES as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-bold small">Type</label>
                            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach(\App\Models\MartialArtsClass::TYPES as $t)
                                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-bold small">Level</label>
                            <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Levels</option>
                                @foreach(\App\Models\MartialArtsClass::LEVELS as $l)
                                    <option value="{{ $l }}" {{ request('level') == $l ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold small">Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search Class Name">
                                <button class="btn btn-primary" type="submit"><i data-feather="search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <a href="{{ route('user.schedule-session-list') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Classes Grid Starts -->
            <div class="row">
                @forelse ($classes as $class)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <div class="ecommerce-card1">
                            <a href="{{ route('user.schedule-session-detail', $class->id) }}">
                                <div class="class-img-wrapper">
                                    <img src="{{ asset($class->image ?: 'assets/images/no-preview.png') }}" alt="{{ $class->name }}" />
                                    <span class="class-badge">{{ $class->category }}</span>
                                </div>
                            </a>
                            <div class="class-details">
                                <a href="{{ route('user.schedule-session-detail', $class->id) }}">
                                    <h5 class="class-title">{{ $class->name }}</h5>
                                </a>
                                <div class="class-meta">
                                    <span class="class-level"><i data-feather="bar-chart-2" class="font-small-3 me-25"></i>{{ $class->level }}</span>
                                    <span class="class-price">${{ number_format($class->price ?? 0, 2) }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-1 mt-auto">
                                    <i data-feather="clock" class="text-primary font-small-3 me-50"></i>
                                    <small class="text-muted text-truncate" style="max-width: 90%;">{{ $class->description ? Str::limit(str_replace("\n", ", ", $class->description), 40) : 'Schedule info on details page' }}</small>
                                </div>

                                <a href="{{ route('user.schedule-session-detail', $class->id) }}" class="btn btn-primary btn-sm w-100 text-center mt-1">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <h3>No classes found matching your criteria.</h3>
                                <a href="{{ route('user.schedule-session-list') }}" class="btn btn-outline-primary mt-2">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- Classes Grid Ends -->

            <div class="d-flex justify-content-center mt-2 mb-4">
                {{ $classes->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
  <script>
      $(document).ready(function() {
          if (typeof feather !== 'undefined') {
              feather.replace({ width: 14, height: 14 });
          }
      });
  </script>
@endsection

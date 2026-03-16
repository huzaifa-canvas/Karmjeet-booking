
@extends('layouts.master')
@section('title','Schedule Classes | '.config('app.name'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <style>
        .filter-card .form-select { font-size: 0.9rem; }
        .badge-active { background-color: #28c76f; }
        .badge-inactive { background-color: #ea5455; }
        .category-header {
            background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%);
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .class-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .class-img-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Schedule Classes</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('schedule-session-create') }}" class="btn btn-primary">
                        <i data-feather='plus'></i> Add New Class
                    </a>
                </div>
            </div>

            <div class="content-body">

                {{-- Filter Card --}}
                <div class="card filter-card">
                    <div class="card-body py-1">
                        <form method="GET" action="{{ route('schedule-session-list') }}" class="row g-1 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Category</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach(\App\Models\MartialArtsClass::CATEGORIES as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Type</label>
                                <select name="type" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach(\App\Models\MartialArtsClass::TYPES as $t)
                                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Level</label>
                                <select name="level" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach(\App\Models\MartialArtsClass::LEVELS as $l)
                                        <option value="{{ $l }}" {{ request('level') == $l ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Age Group</label>
                                <select name="age_group" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach(\App\Models\MartialArtsClass::AGE_GROUPS as $ag)
                                        <option value="{{ $ag }}" {{ request('age_group') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Format</label>
                                <select name="format" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach(\App\Models\MartialArtsClass::FORMATS as $f)
                                        <option value="{{ $f }}" {{ request('format') == $f ? 'selected' : '' }}>{{ $f }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                    <i data-feather='filter'></i> Filter
                                </button>
                                <a href="{{ route('schedule-session-list') }}" class="btn btn-outline-secondary btn-sm">
                                    <i data-feather='x'></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Classes Grouped by Category --}}
                @forelse($groupedClasses as $category => $categoryClasses)
                    <div class="category-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-white"><i data-feather='folder'></i> {{ $category ?? 'Uncategorized' }}</h6>
                        <span class="badge bg-light text-dark">{{ $categoryClasses->count() }} {{ $categoryClasses->count() == 1 ? 'class' : 'classes' }}</span>
                    </div>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:60px">Image</th>
                                        <th>Class Name</th>
                                        <th>Type</th>
                                        <th>Level</th>
                                        <th>Age Group</th>
                                        <th>Format</th>
                                        <th>Instructor</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryClasses as $cls)
                                        <tr>
                                            <td>
                                                @if($cls->image)
                                                    <img src="{{ asset($cls->image) }}" alt="{{ $cls->name }}" class="class-img">
                                                @else
                                                    <div class="class-img-placeholder">{{ strtoupper(substr($cls->name, 0, 1)) }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $cls->name }}</span>
                                                @if($cls->description)
                                                    <br><small class="text-muted">{{ Str::limit($cls->description, 60) }}</small>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light-primary">{{ $cls->type ?? '—' }}</span></td>
                                            <td>{{ $cls->level ?? '—' }}</td>
                                            <td>{{ $cls->age_group ?? '—' }}</td>
                                            <td>{{ $cls->format ?? '—' }}</td>
                                            <td>{{ $cls->instructor ?? '—' }}</td>
                                            <td>
                                                @if($cls->price)
                                                    <span class="fw-bold text-success">${{ number_format($cls->price, 2) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill {{ $cls->status == 'active' ? 'badge-active' : 'badge-inactive' }}">{{ ucfirst($cls->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('schedule-session-edit', $cls->id) }}" class="item-edit" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                    <x-edit-icon/>
                                                </a>
                                                <a href="{{ route('schedule-session-delete', $cls->id) }}" class="delete-record" data-bs-toggle="tooltip" data-bs-original-title="Delete" onclick="return confirm('Are you sure you want to delete this class?')">
                                                    <x-trash-icon/>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <p class="text-muted mb-0">No classes found. <a href="{{ route('schedule-session-create') }}">Add your first class</a>.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace({ width: 14, height: 14 });
        }
    </script>
@endsection

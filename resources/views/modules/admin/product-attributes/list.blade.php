
@extends('layouts.master')
@section('title','Categories & Brands | '.config('app.name'))
@section('style')
    <style>
        .attr-tabs .nav-link { font-weight: 600; }
        .attr-tabs .nav-link.active { background: linear-gradient(118deg, #7367f0, #9e95f5) !important; color: #fff !important; }
        .edit-row input, .edit-row select { max-width: 200px; display: inline-block; }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Categories & Brands</h2>
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
                    {{-- Left: Add New Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Add New</h4></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.product-attributes.store') }}">
                                    @csrf
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required>
                                            <option value="category">Category</option>
                                            <option value="brand">Brand</option>
                                        </select>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i data-feather='plus' style="width:14px;height:14px"></i> Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Right: List --}}
                    <div class="col-md-8">
                        {{-- Filter Tabs --}}
                        <div class="card">
                            <div class="card-body pb-0">
                                <ul class="nav nav-pills attr-tabs mb-1">
                                    <li class="nav-item">
                                        <a class="nav-link {{ !request('type') ? 'active' : '' }}" href="{{ route('admin.product-attributes.index') }}">
                                            All <span class="badge bg-light text-dark ms-50">{{ $totalCategories + $totalBrands }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('type') == 'category' ? 'active' : '' }}" href="{{ route('admin.product-attributes.index', ['type' => 'category']) }}">
                                            Categories <span class="badge bg-light text-dark ms-50">{{ $totalCategories }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('type') == 'brand' ? 'active' : '' }}" href="{{ route('admin.product-attributes.index', ['type' => 'brand']) }}">
                                            Brands <span class="badge bg-light text-dark ms-50">{{ $totalBrands }}</span>
                                        </a>
                                    </li>
                                </ul>

                                {{-- Search --}}
                                <form method="GET" action="{{ route('admin.product-attributes.index') }}" class="row g-1 align-items-end mb-1">
                                    @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                                    <div class="col-md-8">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-4 d-flex gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i data-feather='search' style="width:12px;height:12px"></i> Search</button>
                                        <a href="{{ route('admin.product-attributes.index', request('type') ? ['type' => request('type')] : []) }}" class="btn btn-outline-secondary btn-sm"><i data-feather='x' style="width:12px;height:12px"></i></a>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attributes as $index => $attr)
                                            <tr id="row-{{ $attr->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{-- Display Mode --}}
                                                    <span class="display-mode-{{ $attr->id }}">
                                                        <strong>{{ $attr->name }}</strong>
                                                    </span>
                                                    {{-- Edit Mode --}}
                                                    <span class="edit-mode-{{ $attr->id }} d-none edit-row">
                                                        <form method="POST" action="{{ route('admin.product-attributes.update', $attr->id) }}" class="d-inline">
                                                            @csrf
                                                            <input type="text" name="name" value="{{ $attr->name }}" class="form-control form-control-sm d-inline" style="width:140px" required>
                                                            <select name="status" class="form-select form-select-sm d-inline" style="width:100px">
                                                                <option value="active" {{ $attr->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ $attr->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-success"><i data-feather='check' style="width:12px;height:12px"></i></button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleEdit({{ $attr->id }})"><i data-feather='x' style="width:12px;height:12px"></i></button>
                                                        </form>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-{{ $attr->type == 'category' ? 'primary' : 'info' }}">{{ ucfirst($attr->type) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill {{ $attr->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($attr->status) }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary display-mode-{{ $attr->id }}" onclick="toggleEdit({{ $attr->id }})" data-bs-toggle="tooltip" title="Edit">
                                                        <i data-feather='edit-2' style="width:12px;height:12px"></i>
                                                    </button>
                                                    <a href="{{ route('admin.product-attributes.delete', $attr->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')" data-bs-toggle="tooltip" title="Delete">
                                                        <i data-feather='trash-2' style="width:12px;height:12px"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3">
                                                    <p class="text-muted mb-0">No items found. Add a category or brand.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    function toggleEdit(id) {
        document.querySelectorAll('.display-mode-' + id).forEach(el => el.classList.toggle('d-none'));
        document.querySelectorAll('.edit-mode-' + id).forEach(el => el.classList.toggle('d-none'));
    }
    if (typeof feather !== 'undefined') { feather.replace({ width: 14, height: 14 }); }
</script>
@endsection

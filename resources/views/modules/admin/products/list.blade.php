
@extends('layouts.master')
@section('title','Products | '.config('app.name'))
@section('style')
    <style>
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .product-img-placeholder { width: 50px; height: 50px; border-radius: 8px; background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 16px; }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">Products</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i data-feather='plus'></i> Add Product
                    </a>
                </div>
            </div>

            <div class="content-body">

                {{-- Filter Card --}}
                <div class="card">
                    <div class="card-body py-1">
                        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-1 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Product name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Category</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i data-feather='filter'></i> Filter</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm"><i data-feather='x'></i></a>
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

                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-img">
                                            @else
                                                <div class="product-img-placeholder">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $product->name }}</span>
                                            @if($product->brand)
                                                <br><small class="text-muted">{{ $product->brand }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light-primary">{{ $product->category ?? '—' }}</span></td>
                                        <td>
                                            @if($product->sale_price)
                                                <span class="fw-bold text-success">${{ number_format($product->sale_price, 2) }}</span>
                                                <br><small class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</small>
                                            @else
                                                <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->stock > 0)
                                                <span class="badge bg-light-success">{{ $product->stock }}</span>
                                            @else
                                                <span class="badge bg-light-danger">Out of stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($product->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="item-edit" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                <x-edit-icon/>
                                            </a>
                                            <a href="{{ route('admin.products.delete', $product->id) }}" class="delete-record" data-bs-toggle="tooltip" data-bs-original-title="Delete" onclick="return confirm('Are you sure?')">
                                                <x-trash-icon/>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            <p class="text-muted mb-0">No products found. <a href="{{ route('admin.products.create') }}">Add your first product</a>.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($products->hasPages())
                        <div class="card-footer">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
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

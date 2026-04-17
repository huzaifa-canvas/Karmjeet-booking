@extends('layouts.master')
@section('title', 'Shop')
@section('style')
     <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/extensions/nouislider.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/extensions/ext-component-sliders.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/pages/app-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/extensions/ext-component-toastr.css">
    <!-- END: Page CSS-->
@endsection
@section('content')


    <!-- BEGIN: Content-->
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Shop</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Shop</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-detached content-right">
                <div class="content-body">
                    <!-- E-commerce Content Section Starts -->
                    <section id="ecommerce-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="ecommerce-header-items">
                                    <div class="result-toggler">
                                        <button class="navbar-toggler shop-sidebar-toggler" type="button" data-bs-toggle="collapse">
                                            <span class="navbar-toggler-icon d-block d-lg-none"><i data-feather="menu"></i></span>
                                        </button>
                                        <div class="search-results">{{ $products->total() }} results found</div>
                                    </div>
                                    <div class="view-options d-flex">
                                        <div class="btn-group dropdown-sort">
                                            <button type="button" class="btn btn-outline-primary dropdown-toggle me-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="active-sorting">{{ request('sort') == 'lowest' ? 'Lowest' : (request('sort') == 'highest' ? 'Highest' : 'Featured') }}</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => '']) }}">Featured</a>
                                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'lowest']) }}">Lowest</a>
                                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'highest']) }}">Highest</a>
                                            </div>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="radio_options" id="radio_option1" autocomplete="off" checked />
                                            <label class="btn btn-icon btn-outline-primary view-btn grid-view-btn" for="radio_option1"><i data-feather="grid" class="font-medium-3"></i></label>
                                            <input type="radio" class="btn-check" name="radio_options" id="radio_option2" autocomplete="off" />
                                            <label class="btn btn-icon btn-outline-primary view-btn list-view-btn" for="radio_option2"><i data-feather="list" class="font-medium-3"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="body-content-overlay"></div>

                    <!-- E-commerce Search Bar -->
                    <section id="ecommerce-searchbar" class="ecommerce-searchbar">
                        <div class="row mt-1">
                            <div class="col-sm-12">
                                <form method="GET" action="{{ route('shop.index') }}">
                                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                                    @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control search-product" name="search" id="shop-search" placeholder="Search Product" value="{{ request('search') }}" />
                                        <span class="input-group-text"><i data-feather="search" class="text-muted"></i></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>

                    <!-- E-commerce Products -->
                    <section id="ecommerce-products" class="grid-view">
                        @forelse($products as $product)
                        <div class="card ecommerce-card">
                            <div class="item-img text-center">
                                <a href="{{ route('shop.show', $product->slug) }}">
                                    @if($product->image)
                                        <img class="img-fluid card-img-top" src="{{ asset($product->image) }}" alt="{{ $product->name }}" />
                                    @else
                                        <img class="img-fluid card-img-top" src="{{ asset('app-assets/images/pages/eCommerce/1.png') }}" alt="placeholder" />
                                    @endif
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="item-wrapper">
                                    <div class="item-rating">
                                        <ul class="unstyled-list list-inline">
                                            @for($i = 1; $i <= 5; $i++)
                                                <li class="ratings-list-item"><i data-feather="star" class="{{ $i <= 4 ? 'filled-star' : 'unfilled-star' }}"></i></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <div>
                                        @if($product->sale_price)
                                            <h6 class="item-price">${{ number_format($product->sale_price, 2) }}</h6>
                                        @else
                                            <h6 class="item-price">${{ number_format($product->price, 2) }}</h6>
                                        @endif
                                    </div>
                                </div>
                                <h6 class="item-name">
                                    <a class="text-body" href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                                    @if($product->brand)
                                        <span class="card-text item-company">By <a href="{{ route('shop.index', ['search' => $product->brand]) }}" class="company-name">{{ $product->brand }}</a></span>
                                    @endif
                                </h6>
                                <p class="card-text item-description">{{ Str::limit($product->description, 150) }}</p>
                            </div>
                            <div class="item-options text-center">
                                <div class="item-wrapper">
                                    <div class="item-cost">
                                        @if($product->sale_price)
                                            <h4 class="item-price">${{ number_format($product->sale_price, 2) }}</h4>
                                        @else
                                            <h4 class="item-price">${{ number_format($product->price, 2) }}</h4>
                                        @endif
                                        @if($product->stock > 0)
                                            <p class="card-text shipping"><span class="badge rounded-pill badge-light-success">In Stock</span></p>
                                        @else
                                            <p class="card-text shipping"><span class="badge rounded-pill badge-light-danger">Out of Stock</span></p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-light btn-wishlist">
                                    <i data-feather="eye"></i>
                                    <span>Details</span>
                                </a>
                                @if($product->stock > 0)
                                    <a href="javascript:void(0)" class="btn btn-primary btn-cart" onclick="addToCart({{ $product->id }})">
                                        <i data-feather="shopping-cart"></i>
                                        <span class="add-to-cart">Add to cart</span>
                                    </a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-secondary btn-cart" disabled>
                                        <i data-feather="shopping-cart"></i>
                                        <span>Out of stock</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-3">
                                        <h5 class="text-muted">No products found</h5>
                                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-1">View All Products</a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </section>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <section id="ecommerce-pagination">
                        <div class="row">
                            <div class="col-sm-12">
                                <nav>
                                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    </section>
                    @endif

                </div>
            </div>
            <div class="sidebar-detached sidebar-left">
                <div class="sidebar">
                    <div class="sidebar-shop">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6 class="filter-heading d-none d-lg-block">Filters</h6>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- Price Filter -->
                                <div class="multi-range-price">
                                    <h6 class="filter-title mt-0">Price Range</h6>
                                    <ul class="list-unstyled price-range" id="price-range">
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="priceAll" name="price-range" class="form-check-input" {{ !request('price_range') ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['price_range' => '']) }}'" />
                                                <label class="form-check-label" for="priceAll">All</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="priceRange1" name="price-range" class="form-check-input" {{ request('price_range') == '0-10' ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['price_range' => '0-10']) }}'" />
                                                <label class="form-check-label" for="priceRange1">&lt;=$10</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="priceRange2" name="price-range" class="form-check-input" {{ request('price_range') == '10-100' ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['price_range' => '10-100']) }}'" />
                                                <label class="form-check-label" for="priceRange2">$10 - $100</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="priceRange3" name="price-range" class="form-check-input" {{ request('price_range') == '100-500' ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['price_range' => '100-500']) }}'" />
                                                <label class="form-check-label" for="priceRange3">$100 - $500</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="priceRange4" name="price-range" class="form-check-input" {{ request('price_range') == '500+' ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['price_range' => '500+']) }}'" />
                                                <label class="form-check-label" for="priceRange4">&gt;= $500</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Categories -->
                                <div id="product-categories">
                                    <h6 class="filter-title">Categories</h6>
                                    <ul class="list-unstyled categories-list">
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="categoryAll" name="category-filter" class="form-check-input" {{ !request('category') ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['category' => '']) }}'" />
                                                <label class="form-check-label" for="categoryAll">All</label>
                                            </div>
                                        </li>
                                        @foreach($categories as $cat)
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="cat_{{ Str::slug($cat) }}" name="category-filter" class="form-check-input" {{ request('category') == $cat ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['category' => $cat]) }}'" />
                                                <label class="form-check-label" for="cat_{{ Str::slug($cat) }}">{{ $cat }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Brands -->
                                <div id="product-brands">
                                    <h6 class="filter-title">Brands</h6>
                                    <ul class="list-unstyled brands-list">
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="brandAll" name="brand-filter" class="form-check-input" {{ !request('brand') ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['brand' => '']) }}'" />
                                                <label class="form-check-label" for="brandAll">All</label>
                                            </div>
                                        </li>
                                        @foreach($brands as $brand)
                                        <li>
                                            <div class="form-check">
                                                <input type="radio" id="brand_{{ Str::slug($brand) }}" name="brand-filter" class="form-check-input" {{ request('brand') == $brand ? 'checked' : '' }} onchange="window.location='{{ request()->fullUrlWithQuery(['brand' => $brand]) }}'" />
                                                <label class="form-check-label" for="brand_{{ Str::slug($brand) }}">{{ $brand }}</label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
@endsection

@section('scripts')
    <script src="{{ asset('/') }}app-assets/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('/') }}app-assets/js/scripts/pages/app-ecommerce.js"></script>
    <script>
        function addToCart(productId) {
            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: { product_id: productId, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        updateHeaderCart(res.cartData, res.cartCount);
                        // Show toast
                        if (typeof toastr !== 'undefined') {
                            toastr.success(res.message, 'Cart', { positionClass: 'toast-top-right', timeOut: 2000 });
                        } else {
                            alert(res.message);
                        }
                    }
                }
            });
        }
    </script>
@endsection

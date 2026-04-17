@extends('layouts.master')
@section('title', $product->name . ' | Shop')
@section('style')

    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/extensions/swiper.min.css">
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/pages/app-ecommerce-details.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/form-number-input.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/extensions/ext-component-toastr.css">
    <!-- END: Page CSS-->
    <style>
        .product-gallery { display: flex; gap: 10px; margin-top: 15px; }
        .product-gallery-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: border-color 0.3s; }
        .product-gallery-thumb:hover, .product-gallery-thumb.active { border-color: #7367f0; }
    </style>
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
                            <h2 class="content-header-title float-start mb-0">Product Details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                                    <li class="breadcrumb-item active">Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- app e-commerce details start -->
                <section class="app-ecommerce-details">
                    <div class="card">
                        <!-- Product Details starts -->
                        <div class="card-body">
                            <div class="row my-2">
                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" class="img-fluid product-img" alt="{{ $product->name }}" id="mainProductImage" />
                                        @else
                                            <img src="{{ asset('app-assets/images/pages/eCommerce/1.png') }}" class="img-fluid product-img" alt="placeholder" id="mainProductImage" />
                                        @endif

                                        {{-- Image gallery thumbnails --}}
                                        @if($product->all_images->count() > 1)
                                            <div class="product-gallery">
                                                @foreach($product->all_images as $index => $img)
                                                    <img src="{{ asset($img) }}" class="product-gallery-thumb {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage(this, '{{ asset($img) }}')" alt="Product Image {{ $index + 1 }}">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <h4>{{ $product->name }}</h4>
                                    @if($product->brand)
                                        <span class="card-text item-company">By <a href="{{ route('shop.index', ['search' => $product->brand]) }}" class="company-name">{{ $product->brand }}</a></span>
                                    @endif
                                    <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                        @if($product->sale_price)
                                            <h4 class="item-price me-1">${{ number_format($product->sale_price, 2) }}</h4>
                                            <small class="text-decoration-line-through text-muted me-1" style="line-height:2.5">${{ number_format($product->price, 2) }}</small>
                                        @else
                                            <h4 class="item-price me-1">${{ number_format($product->price, 2) }}</h4>
                                        @endif
                                        <ul class="unstyled-list list-inline ps-1 border-start">
                                            @for($i = 1; $i <= 5; $i++)
                                                <li class="ratings-list-item"><i data-feather="star" class="{{ $i <= 4 ? 'filled-star' : 'unfilled-star' }}"></i></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <p class="card-text">Available -
                                        @if($product->stock > 0)
                                            <span class="text-success">In stock ({{ $product->stock }})</span>
                                        @else
                                            <span class="text-danger">Out of stock</span>
                                        @endif
                                    </p>
                                    <p class="card-text">{{ $product->description }}</p>

                                    @if($product->category)
                                    <ul class="product-features list-unstyled">
                                        <li><i data-feather="tag"></i> <span>Category: {{ $product->category }}</span></li>
                                    </ul>
                                    @endif
                                    <hr />
                                    <div class="d-flex flex-column flex-sm-row pt-1">
                                        @if($product->stock > 0)
                                            <a href="javascript:void(0)" class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0" onclick="addToCart({{ $product->id }})" id="addToCartBtn">
                                                <i data-feather="shopping-cart" class="me-50"></i>
                                                <span class="add-to-cart">{{ $inCart ? 'View in cart' : 'Add to cart' }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)" class="btn btn-secondary btn-cart me-0 me-sm-1 mb-1 mb-sm-0" disabled>
                                                <i data-feather="shopping-cart" class="me-50"></i>
                                                <span>Out of Stock</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->

                        <!-- Item features starts -->
                        <div class="item-features">
                            <div class="row text-center">
                                <div class="col-12 col-md-4 mb-4 mb-md-0">
                                    <div class="w-75 mx-auto">
                                        <i data-feather="award"></i>
                                        <h4 class="mt-2 mb-1">100% Original</h4>
                                        <p class="card-text">Guaranteed authentic products with quality assurance.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4 mb-md-0">
                                    <div class="w-75 mx-auto">
                                        <i data-feather="clock"></i>
                                        <h4 class="mt-2 mb-1">10 Day Replacement</h4>
                                        <p class="card-text">Easy replacement within 10 days of purchase.</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4 mb-md-0">
                                    <div class="w-75 mx-auto">
                                        <i data-feather="shield"></i>
                                        <h4 class="mt-2 mb-1">1 Year Warranty</h4>
                                        <p class="card-text">Full warranty coverage for one year.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Item features ends -->

                        <!-- Related Products starts -->
                        @if($relatedProducts->count() > 0)
                        <div class="card-body">
                            <div class="mt-4 mb-2 text-center">
                                <h4>Related Products</h4>
                                <p class="card-text">People also search for this items</p>
                            </div>
                            <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                                <div class="swiper-wrapper">
                                    @foreach($relatedProducts as $related)
                                    <div class="swiper-slide">
                                        <a href="{{ route('shop.show', $related->slug) }}">
                                            <div class="item-heading">
                                                <h5 class="text-truncate mb-0">{{ $related->name }}</h5>
                                                @if($related->brand)
                                                    <small class="text-body">by {{ $related->brand }}</small>
                                                @endif
                                            </div>
                                            <div class="img-container w-50 mx-auto py-75">
                                                @if($related->image)
                                                    <img src="{{ asset($related->image) }}" class="img-fluid" alt="{{ $related->name }}" />
                                                @else
                                                    <img src="{{ asset('app-assets/images/pages/eCommerce/1.png') }}" class="img-fluid" alt="placeholder" />
                                                @endif
                                            </div>
                                            <div class="item-meta">
                                                <ul class="unstyled-list list-inline mb-25">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <li class="ratings-list-item"><i data-feather="star" class="{{ $i <= 4 ? 'filled-star' : 'unfilled-star' }}"></i></li>
                                                    @endfor
                                                </ul>
                                                <p class="card-text text-primary mb-0">${{ number_format($related->display_price, 2) }}</p>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                        @endif
                        <!-- Related Products ends -->
                    </div>
                </section>
                <!-- app e-commerce details end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
@endsection

@section('scripts')
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/extensions/swiper.min.js"></script>
    <script src="{{ asset('/') }}app-assets/js/scripts/pages/app-ecommerce-details.js"></script>
    <script src="{{ asset('/') }}app-assets/js/scripts/forms/form-number-input.js"></script>
    <script>
        function changeMainImage(thumb, src) {
            document.getElementById('mainProductImage').src = src;
            document.querySelectorAll('.product-gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        function addToCart(productId) {
            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: { product_id: productId, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        updateHeaderCart(res.cartData, res.cartCount);
                        $('#addToCartBtn .add-to-cart').text('View in cart');
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

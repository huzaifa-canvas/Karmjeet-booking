@extends('layouts.master')
@section('title', 'Checkout')
@section('style')

    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/wizard/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/pages/app-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/pickers/form-pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/form-wizard.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/form-number-input.css">
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
                            <h2 class="content-header-title float-start mb-0">Checkout</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                                    <li class="breadcrumb-item active">Checkout</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="bs-stepper checkout-tab-steps" id="checkoutStepper">
                    <!-- Wizard starts -->
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#step-cart" role="tab" id="step-cart-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box"><i data-feather="shopping-cart" class="font-medium-3"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Cart</span>
                                    <span class="bs-stepper-subtitle">Your Cart Items</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"><i data-feather="chevron-right" class="font-medium-2"></i></div>
                        <div class="step" data-target="#step-address" role="tab" id="step-address-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box"><i data-feather="home" class="font-medium-3"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Address</span>
                                    <span class="bs-stepper-subtitle">Enter Your Address</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"><i data-feather="chevron-right" class="font-medium-2"></i></div>
                        <div class="step" data-target="#step-payment" role="tab" id="step-payment-trigger">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box"><i data-feather="credit-card" class="font-medium-3"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Payment</span>
                                    <span class="bs-stepper-subtitle">Select Payment Method</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <!-- Wizard ends -->

                    <form method="POST" action="{{ route('shop.placeOrder') }}" id="checkoutForm">
                        @csrf
                        <div class="bs-stepper-content">
                            <!-- Step 1: Cart -->
                            <div id="step-cart" class="content" role="tabpanel" aria-labelledby="step-cart-trigger">
                                <div id="place-order" class="list-view product-checkout">
                                    <div class="checkout-items">
                                        @php $cartTotal = 0; @endphp
                                        @foreach($cart as $id => $item)
                                            @php $itemTotal = $item['price'] * $item['quantity']; $cartTotal += $itemTotal; @endphp
                                            <div class="card ecommerce-card" id="cart-item-{{ $id }}">
                                                <div class="item-img">
                                                    <a href="{{ route('shop.show', $item['slug']) }}">
                                                        @if($item['image'])
                                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" />
                                                        @else
                                                            <img src="{{ asset('app-assets/images/pages/eCommerce/1.png') }}" alt="placeholder" />
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="card-body">
                                                    <div class="item-name">
                                                        <h6 class="mb-0"><a href="{{ route('shop.show', $item['slug']) }}" class="text-body">{{ $item['name'] }}</a></h6>
                                                        <span class="item-company">By <a href="#" class="company-name">{{ $item['brand'] ?? 'Karmjeet' }}</a></span>
                                                        <div class="item-rating">
                                                            <ul class="unstyled-list list-inline">
                                                                <li class="ratings-list-item"><i data-feather="star" class="filled-star text-warning"></i></li>
                                                                <li class="ratings-list-item"><i data-feather="star" class="filled-star text-warning"></i></li>
                                                                <li class="ratings-list-item"><i data-feather="star" class="filled-star text-warning"></i></li>
                                                                <li class="ratings-list-item"><i data-feather="star" class="filled-star text-warning"></i></li>
                                                                <li class="ratings-list-item"><i data-feather="star" class="unfilled-star text-muted"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <span class="text-success mb-1">In Stock</span>
                                                    <div class="item-quantity">
                                                        <span class="quantity-title">Qty:</span>
                                                        <div class="quantity-counter-wrapper">
                                                            <div class="input-group">
                                                                <input type="text" class="quantity-counter" value="{{ $item['quantity'] }}" data-id="{{ $id }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item-options text-center">
                                                    <div class="item-wrapper">
                                                        <div class="item-cost">
                                                            <h4 class="item-price">${{ number_format($item['price'], 2) }}</h4>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-light mt-1 remove-wishlist" onclick="removeFromCheckout({{ $id }})">
                                                        <i data-feather="x" class="align-middle me-25"></i>
                                                        <span>Remove</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="checkout-options">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="section-label form-label mb-1">Price Details</label>
                                                <hr />
                                                <div class="price-details">
                                                    <ul class="list-unstyled">
                                                        <li class="price-detail">
                                                            <div class="detail-title">Total MRP</div>
                                                            <div class="detail-amt" id="totalMrp">${{ number_format($cartTotal, 2) }}</div>
                                                        </li>
                                                        <li class="price-detail">
                                                            <div class="detail-title">Delivery Charges</div>
                                                            <div class="detail-amt discount-amt text-success">Free</div>
                                                        </li>
                                                    </ul>
                                                    <hr />
                                                    <ul class="list-unstyled">
                                                        <li class="price-detail">
                                                            <div class="detail-title detail-total">Total</div>
                                                            <div class="detail-amt fw-bolder" id="cartTotalDisplay">${{ number_format($cartTotal, 2) }}</div>
                                                        </li>
                                                    </ul>
                                                    <button type="button" class="btn btn-primary w-100 btn-next place-order" onclick="stepper.next()">Place Order</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Address -->
                            <div id="step-address" class="content" role="tabpanel" aria-labelledby="step-address-trigger">
                                <div class="list-view product-checkout">
                                    <div class="card">
                                        <div class="card-header flex-column align-items-start">
                                            <h4 class="card-title">Shipping Address</h4>
                                            <p class="card-text text-muted mt-25">Enter your delivery address</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-name">Full Name: <span class="text-danger">*</span></label>
                                                        <input type="text" id="checkout-name" class="form-control" name="full_name" placeholder="John Doe" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-number">Mobile Number: <span class="text-danger">*</span></label>
                                                        <input type="text" id="checkout-number" class="form-control" name="phone" placeholder="0123456789" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-apt-number">Flat, House No:</label>
                                                        <input type="text" id="checkout-apt-number" class="form-control" name="flat_house" placeholder="9447 Glen Eagles Drive" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-landmark">Landmark:</label>
                                                        <input type="text" id="checkout-landmark" class="form-control" name="landmark" placeholder="Near Apollo Hospital" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-city">Town/City: <span class="text-danger">*</span></label>
                                                        <input type="text" id="checkout-city" class="form-control" name="city" placeholder="Tokyo" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-pincode">Pincode:</label>
                                                        <input type="text" id="checkout-pincode" class="form-control" name="pincode" placeholder="201301" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="checkout-state">State:</label>
                                                        <input type="text" id="checkout-state" class="form-control" name="state" placeholder="California" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="add-type">Address Type:</label>
                                                        <select class="form-select" id="add-type" name="address_type">
                                                            <option value="home">Home</option>
                                                            <option value="work">Work</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-primary btn-next delivery-address" onclick="stepper.next()">Save And Continue</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Payment -->
                            <div id="step-payment" class="content" role="tabpanel" aria-labelledby="step-payment-trigger">
                                <div class="list-view product-checkout">
                                    <div class="payment-type">
                                        <div class="card">
                                            <div class="card-header flex-column align-items-start">
                                                <h4 class="card-title">Payment Options</h4>
                                                <p class="card-text text-muted mt-25">Select your preferred payment method</p>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input type="radio" id="paymentStripe" name="payment_method" value="stripe" class="form-check-input" checked />
                                                    <label class="form-check-label fw-bold" for="paymentStripe">
                                                        <i data-feather="credit-card" style="width:16px;height:16px" class="me-50"></i>
                                                        Pay with Stripe (Credit/Debit Card)
                                                    </label>
                                                    <p class="text-muted small mt-50 ms-2">You will be redirected to Stripe's secure checkout page.</p>
                                                </div>
                                                <hr />
                                                <div class="form-check mb-1">
                                                    <input type="radio" id="paymentCOD" name="payment_method" value="cod" class="form-check-input" />
                                                    <label class="form-check-label fw-bold" for="paymentCOD">
                                                        <i data-feather="dollar-sign" style="width:16px;height:16px" class="me-50"></i>
                                                        Cash On Delivery
                                                    </label>
                                                    <p class="text-muted small mt-50 ms-2">Pay when you receive the order.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="amount-payable checkout-options">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Price Details</h4>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled price-details">
                                                    <li class="price-detail">
                                                        <div class="details-title">Price of {{ count($cart) }} items</div>
                                                        <div class="detail-amt"><strong>${{ number_format($cartTotal, 2) }}</strong></div>
                                                    </li>
                                                    <li class="price-detail">
                                                        <div class="details-title">Delivery Charges</div>
                                                        <div class="detail-amt discount-amt text-success">Free</div>
                                                    </li>
                                                </ul>
                                                <hr />
                                                <ul class="list-unstyled price-details">
                                                    <li class="price-detail">
                                                        <div class="details-title">Amount Payable</div>
                                                        <div class="detail-amt fw-bolder">${{ number_format($cartTotal, 2) }}</div>
                                                    </li>
                                                </ul>
                                                <button type="submit" class="btn btn-primary w-100 mt-1">
                                                    <i data-feather="lock" style="width:14px;height:14px" class="me-50"></i>
                                                    Place Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
@endsection

@section('scripts')
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script>
        var stepper;
        document.addEventListener('DOMContentLoaded', function () {
            stepper = new Stepper(document.querySelector('#checkoutStepper'), { linear: false });
        });

        function removeFromCheckout(productId) {
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: 'POST',
                data: { product_id: productId, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        $('#cart-item-' + productId).fadeOut(300, function() { $(this).remove(); });
                        updateHeaderCart(res.cartData, res.cartCount);
                        // Update totals
                        if (res.cartCount === 0) {
                            window.location.href = '{{ route("shop.index") }}';
                        } else {
                            $('#totalMrp').text('$' + res.cartData.total.toFixed(2));
                            $('#cartTotalDisplay').text('$' + res.cartData.total.toFixed(2));
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            if ($('.quantity-counter').length > 0) {
                $('.quantity-counter').TouchSpin({
                    min: 1,
                    max: 100,
                    buttondown_class: 'btn btn-primary',
                    buttonup_class: 'btn btn-primary'
                }).on('change', function() {
                    var input = $(this);
                    var quantity = input.val();
                    var productId = input.data('id');
                    
                    $.ajax({
                        url: '{{ route("cart.update") }}',
                        method: 'POST',
                        data: { product_id: productId, quantity: quantity, _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            if (res.success) {
                                updateHeaderCart(res.cartData, res.cartCount);
                                $('#totalMrp').text('$' + res.cartData.total.toFixed(2));
                                $('#cartTotalDisplay').text('$' + res.cartData.total.toFixed(2));
                                // Update item row total
                                var itemData = res.cartData.items.find(i => String(i.id) === String(productId));
                                if(itemData){
                                    $('#cart-item-' + productId + ' .item-price').text('$' + itemData.total.toFixed(2));
                                }
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection

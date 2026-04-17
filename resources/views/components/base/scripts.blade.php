
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('/') }}app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('/') }}app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('/') }}app-assets/js/core/app-menu.js"></script>
    <script src="{{ asset('/') }}app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

<script>
    let token = '{{ csrf_token() }}';

    toastr.options = {
        "closeButton": true,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "2000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    @if (session('status') == 'success')
        toastr.success("{{ session('message') }}");
    @endif

    @if (session('status') == "failed")
        toastr.error("{{ session('message') }}");
    @endif

</script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>

{{-- Global Cart JS for header dropdown --}}
<script>
    function updateHeaderCart(cartData, cartCount) {
        // Update badge count
        $('.cart-item-count').text(cartCount);
        $('.cart-header-count').text(cartCount + ' Items');

        // Update total
        if (cartData && cartData.total !== undefined) {
            $('#header-cart-total').text('$' + cartData.total.toFixed(2));
        }

        // Render items
        var container = $('#header-cart-items');
        container.empty();

        if (!cartData || !cartData.items || cartData.items.length === 0) {
            container.html('<div class="text-center py-2 text-muted">Your cart is empty</div>');
            return;
        }

        cartData.items.forEach(function(item) {
            var imgSrc = item.image || '{{ asset("app-assets/images/pages/eCommerce/1.png") }}';
            var html = '<div class="list-item align-items-center">' +
                '<img class="d-block rounded me-1" src="' + imgSrc + '" alt="' + item.name + '" width="62">' +
                '<div class="list-item-body flex-grow-1">' +
                '<i class="ficon cart-item-remove cursor-pointer" data-feather="x" onclick="headerRemoveFromCart(' + item.id + ')"></i>' +
                '<div class="media-heading">' +
                '<h6 class="cart-item-title"><a class="text-body" href="/shop/' + item.slug + '">' + item.name + '</a></h6>' +
                '</div>' +
                '<span>Qty: ' + item.quantity + '</span>' +
                '<h5 class="cart-item-price">$' + parseFloat(item.price).toFixed(2) + '</h5>' +
                '</div></div>';
            container.append(html);
        });

        // Re-render feather icons for the new X buttons
        if (typeof feather !== 'undefined') {
            feather.replace({ width: 14, height: 14 });
        }
    }

    function headerRemoveFromCart(productId) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: { product_id: productId, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    updateHeaderCart(res.cartData, res.cartCount);
                }
            }
        });
    }

    // Load cart on every page load
    $(document).ready(function() {
        $.ajax({
            url: '{{ route("cart.data") }}',
            method: 'GET',
            success: function(res) {
                if (res.success) {
                    updateHeaderCart(res.cartData, res.cartCount);
                }
            }
        });
    });
</script>

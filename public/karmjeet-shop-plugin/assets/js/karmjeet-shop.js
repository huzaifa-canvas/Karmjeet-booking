/**
 * Karmjeet Shop – WordPress Plugin JS
 * Cart stored in localStorage, API calls to Laravel backend
 */
(function($) {
'use strict';

const API = KJS_CONFIG.api_url;
const API_KEY = KJS_CONFIG.api_key;
const PAGES = KJS_CONFIG.pages;
let stripeKey = null;
let stripeInstance = null;
let stripeElements = null;
let cardElement = null;

// Global AJAX setup for API Key
$.ajaxSetup({
    headers: {
        'X-API-Key': API_KEY
    }
});

/* ══════════════════════════════════════
   CART (localStorage)
   ══════════════════════════════════════ */
const Cart = {
    KEY: 'kjs_cart',
    get() {
        try { return JSON.parse(localStorage.getItem(this.KEY)) || []; }
        catch(e) { return []; }
    },
    save(items) { localStorage.setItem(this.KEY, JSON.stringify(items)); this.updateBadge(); },
    add(product, qty = 1) {
        let items = this.get();
        let idx = items.findIndex(i => i.id == product.id);
        if (idx > -1) { items[idx].quantity += qty; }
        else { items.push({ id: product.id, name: product.name, price: parseFloat(product.sale_price || product.price), image_url: product.image_url, slug: product.slug, quantity: qty }); }
        this.save(items);
        showToast(product.name + ' added to cart!', 'success');
    },
    remove(id) {
        let items = this.get().filter(i => i.id != id);
        this.save(items);
    },
    updateQty(id, qty) {
        let items = this.get();
        let idx = items.findIndex(i => i.id == id);
        if (idx > -1) { items[idx].quantity = Math.max(1, parseInt(qty)); this.save(items); }
    },
    getTotal() {
        return this.get().reduce((sum, i) => sum + (i.price * i.quantity), 0);
    },
    count() { return this.get().reduce((sum, i) => sum + i.quantity, 0); },
    clear() { localStorage.removeItem(this.KEY); this.updateBadge(); },
    updateBadge() {
        let c = this.count();
        $('.kjs-cart-badge').text(c).toggle(c > 0);
    }
};

/* ══════════════════════════════════════
   TOAST
   ══════════════════════════════════════ */
function showToast(msg, type) {
    let t = $('<div class="kjs-toast kjs-toast-' + (type||'success') + '">' + msg + '</div>');
    $('body').append(t);
    setTimeout(() => t.fadeOut(300, () => t.remove()), 3000);
}

/* ══════════════════════════════════════
   HELPERS
   ══════════════════════════════════════ */
function formatPrice(p) { return '$' + parseFloat(p).toFixed(2); }
function getParam(name) {
    let u = new URLSearchParams(window.location.search);
    return u.get(name) || '';
}

/* ══════════════════════════════════════
   SHOP PAGE
   ══════════════════════════════════════ */
window.kjsInitShop = function() {
    let currentPage = 1;
    let filters = { search: '', category: '', brand: '', price_range: '', sort: '' };

    loadFilters();
    loadProducts();

    // Search
    let searchTimer;
    $('#kjs-search').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { filters.search = $(this).val(); currentPage = 1; loadProducts(); }, 400);
    });

    // Sort
    $('#kjs-sort').on('change', function() { filters.sort = $(this).val(); currentPage = 1; loadProducts(); });

    // Filter clicks
    $(document).on('change', '.kjs-filter-radio', function() {
        let f = $(this).data('filter');
        filters[f] = $(this).val();
        currentPage = 1;
        loadProducts();
    });

    // Pagination
    $(document).on('click', '.kjs-page-btn', function() {
        currentPage = $(this).data('page');
        loadProducts();
        $('html,body').animate({ scrollTop: $('#kjs-shop-container').offset().top - 60 }, 300);
    });

    // Mobile filter toggle
    $('.kjs-filter-toggle').on('click', function() { $('.kjs-sidebar, .kjs-overlay').addClass('open'); });
    $(document).on('click', '.kjs-overlay', function() { $('.kjs-sidebar, .kjs-overlay').removeClass('open'); });

    function loadFilters() {
        $.get(API + '/filters', function(res) {
            if (!res.success) return;
            let catHtml = '<li><label><input type="radio" name="kjs-cat" class="kjs-filter-radio" data-filter="category" value="" checked> All</label></li>';
            res.categories.forEach(c => { catHtml += '<li><label><input type="radio" name="kjs-cat" class="kjs-filter-radio" data-filter="category" value="'+c+'"> '+c+'</label></li>'; });
            $('#kjs-category-list').html(catHtml);

            let brHtml = '<li><label><input type="radio" name="kjs-br" class="kjs-filter-radio" data-filter="brand" value="" checked> All</label></li>';
            res.brands.forEach(b => { brHtml += '<li><label><input type="radio" name="kjs-br" class="kjs-filter-radio" data-filter="brand" value="'+b+'"> '+b+'</label></li>'; });
            $('#kjs-brand-list').html(brHtml);
        });
    }

    function loadProducts() {
        $('#kjs-products').html('<div class="kjs-loader"><div class="kjs-spinner"></div></div>');
        let params = { page: currentPage };
        if (filters.search) params.search = filters.search;
        if (filters.category) params.category = filters.category;
        if (filters.brand) params.brand = filters.brand;
        if (filters.price_range) params.price_range = filters.price_range;
        if (filters.sort) params.sort = filters.sort;

        $.get(API + '/products', params, function(res) {
            if (!res.success) return;
            let data = res.data;
            $('#kjs-results-count').text(data.total + ' products found');

            if (data.data.length === 0) {
                $('#kjs-products').html('<div class="kjs-cart-empty"><h3>No products found</h3><p>Try adjusting your filters.</p></div>');
                $('#kjs-pagination').html('');
                return;
            }

            let html = '';
            data.data.forEach(p => {
                let price = p.sale_price || p.price;
                let hasDiscount = p.sale_price && parseFloat(p.sale_price) < parseFloat(p.price);
                let productUrl = PAGES.product + (PAGES.product.indexOf('?') > -1 ? '&' : '?') + 'slug=' + p.slug;

                html += '<div class="kjs-product-card">';
                html += '<a href="'+productUrl+'" class="kjs-product-img">';
                if (hasDiscount) html += '<span class="kjs-sale-tag">SALE</span>';
                if (p.featured) html += '<span class="kjs-featured-tag">★ FEATURED</span>';
                html += p.image_url ? '<img src="'+p.image_url+'" alt="'+p.name+'">' : '<div class="kjs-no-img">No Image</div>';
                html += '</a>';
                html += '<div class="kjs-product-info">';
                if (p.category) html += '<div class="kjs-product-category">'+p.category+'</div>';
                html += '<h3 class="kjs-product-name"><a href="'+productUrl+'">'+p.name+'</a></h3>';
                if (p.description) html += '<div class="kjs-product-desc">'+p.description.substring(0,100)+'</div>';
                html += '<div class="kjs-price-row">';
                html += '<span class="kjs-price">'+formatPrice(price)+'</span>';
                if (hasDiscount) html += '<span class="kjs-price-old">'+formatPrice(p.price)+'</span>';
                html += '</div>';
                html += '<div class="kjs-product-actions">';
                html += '<a href="'+productUrl+'" class="kjs-btn kjs-btn-outline kjs-btn-sm">Details</a>';
                if (p.stock > 0) {
                    html += '<button class="kjs-btn kjs-btn-primary kjs-btn-sm kjs-add-cart" data-product=\''+JSON.stringify({id:p.id,name:p.name,price:p.price,sale_price:p.sale_price,image_url:p.image_url,slug:p.slug})+'\'>🛒 Add</button>';
                } else {
                    html += '<button class="kjs-btn kjs-btn-sm disabled">Out of Stock</button>';
                }
                html += '</div></div></div>';
            });
            $('#kjs-products').html(html);

            // Pagination
            let pgHtml = '';
            if (data.last_page > 1) {
                for (let i = 1; i <= data.last_page; i++) {
                    pgHtml += '<button class="kjs-page-btn '+(i===data.current_page?'active':'')+'" data-page="'+i+'">'+i+'</button>';
                }
            }
            $('#kjs-pagination').html(pgHtml);
        });
    }

    // Add to cart from grid
    $(document).on('click', '.kjs-add-cart', function(e) {
        e.preventDefault();
        let p = $(this).data('product');
        Cart.add(p);
    });
};

/* ══════════════════════════════════════
   PRODUCT DETAIL PAGE
   ══════════════════════════════════════ */
window.kjsInitProductDetail = function() {
    let slug = getParam('slug');
    if (!slug) { $('#kjs-product-detail').html('<p>Product not found.</p>'); return; }

    $('#kjs-product-detail').html('<div class="kjs-loader"><div class="kjs-spinner"></div></div>');

    $.get(API + '/products/' + slug, function(res) {
        if (!res.success) { $('#kjs-product-detail').html('<p>Product not found.</p>'); return; }
        let p = res.product;
        let related = res.related;
        let price = p.sale_price || p.price;
        let hasDiscount = p.sale_price && parseFloat(p.sale_price) < parseFloat(p.price);
        let images = p.all_images && p.all_images.length ? p.all_images : (p.image_url ? [p.image_url] : []);

        let html = '<div class="kjs-detail-layout">';
        // Gallery
        html += '<div class="kjs-gallery">';
        html += '<div class="kjs-gallery-main"><img id="kjs-main-img" src="'+(images[0]||'')+'" alt="'+p.name+'"></div>';
        if (images.length > 1) {
            html += '<div class="kjs-gallery-thumbs">';
            images.forEach((img, idx) => { html += '<img src="'+img+'" class="kjs-thumb '+(idx===0?'active':'')+'" onclick="kjsSetMainImg(this, \''+img+'\')">'; });
            html += '</div>';
        }
        html += '</div>';
        // Info
        html += '<div class="kjs-detail-info">';
        if (p.category) html += '<div class="kjs-detail-category">'+p.category+'</div>';
        html += '<h1>'+p.name+'</h1>';
        if (p.brand) html += '<p style="color:var(--kjs-secondary);margin-bottom:12px">By <strong>'+p.brand+'</strong></p>';
        html += '<div class="kjs-detail-price"><span class="kjs-price">'+formatPrice(price)+'</span>';
        if (hasDiscount) html += '<span class="kjs-price-old">'+formatPrice(p.price)+'</span><span class="kjs-badge kjs-badge-danger">SALE</span>';
        html += '</div>';
        html += '<div class="kjs-stock-info">';
        if (p.stock > 0) html += '<span class="kjs-badge kjs-badge-success">In Stock ('+p.stock+')</span>';
        else html += '<span class="kjs-badge kjs-badge-danger">Out of Stock</span>';
        html += '</div>';
        if (p.description) html += '<div class="kjs-detail-desc">'+p.description+'</div>';
        if (p.stock > 0) {
            html += '<div class="kjs-qty-selector"><button onclick="kjsQty(-1)">−</button><input type="number" id="kjs-detail-qty" value="1" min="1" max="'+p.stock+'"><button onclick="kjsQty(1)">+</button></div>';
            html += '<button class="kjs-btn kjs-btn-primary kjs-btn-lg" id="kjs-detail-add" data-product=\''+JSON.stringify({id:p.id,name:p.name,price:p.price,sale_price:p.sale_price,image_url:p.image_url,slug:p.slug})+'\'>🛒 Add to Cart</button>';
        }
        html += '</div></div>';

        // Related
        if (related && related.length) {
            html += '<div class="kjs-related-section"><h2>Related Products</h2><div class="kjs-related-grid">';
            related.forEach(r => {
                let rPrice = r.sale_price || r.price;
                let rUrl = PAGES.product + (PAGES.product.indexOf('?') > -1 ? '&' : '?') + 'slug=' + r.slug;
                html += '<div class="kjs-product-card"><a href="'+rUrl+'" class="kjs-product-img">';
                html += r.image_url ? '<img src="'+r.image_url+'" alt="'+r.name+'">' : '<div class="kjs-no-img">No Image</div>';
                html += '</a><div class="kjs-product-info"><h3 class="kjs-product-name"><a href="'+rUrl+'">'+r.name+'</a></h3>';
                html += '<div class="kjs-price-row"><span class="kjs-price">'+formatPrice(rPrice)+'</span></div>';
                html += '</div></div>';
            });
            html += '</div></div>';
        }

        $('#kjs-product-detail').html(html);

        $('#kjs-detail-add').on('click', function() {
            let p = $(this).data('product');
            let qty = parseInt($('#kjs-detail-qty').val()) || 1;
            Cart.add(p, qty);
        });
    });
};

window.kjsSetMainImg = function(el, src) {
    $('#kjs-main-img').attr('src', src);
    $('.kjs-thumb').removeClass('active');
    $(el).addClass('active');
};

window.kjsQty = function(delta) {
    let inp = $('#kjs-detail-qty');
    let val = parseInt(inp.val()) + delta;
    inp.val(Math.max(1, Math.min(val, parseInt(inp.attr('max')) || 99)));
};

/* ══════════════════════════════════════
   CART PAGE
   ══════════════════════════════════════ */
window.kjsInitCart = function() { renderCart(); };

function renderCart() {
    let items = Cart.get();
    if (!items.length) {
        $('#kjs-cart-content').html('<div class="kjs-cart-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg><h3>Your cart is empty</h3><p>Browse our shop and add products!</p><a href="'+PAGES.shop+'" class="kjs-btn kjs-btn-primary">Continue Shopping</a></div>');
        return;
    }

    let cartHtml = '<div class="kjs-cart-layout"><div class="kjs-card"><div class="kjs-card-body" id="kjs-cart-items">';
    items.forEach(item => {
        cartHtml += '<div class="kjs-cart-item" data-id="'+item.id+'">';
        cartHtml += '<div class="kjs-cart-item-img">'+(item.image_url ? '<img src="'+item.image_url+'" alt="'+item.name+'">' : '<div class="kjs-no-img">No Img</div>')+'</div>';
        cartHtml += '<div class="kjs-cart-item-info"><h4>'+item.name+'</h4><div class="kjs-cart-item-price">'+formatPrice(item.price)+'</div></div>';
        cartHtml += '<div class="kjs-cart-item-qty"><button onclick="kjsCartQty('+item.id+',-1)">−</button><input type="text" value="'+item.quantity+'" readonly><button onclick="kjsCartQty('+item.id+',1)">+</button></div>';
        cartHtml += '<div class="kjs-cart-item-total">'+formatPrice(item.price * item.quantity)+'</div>';
        cartHtml += '<button class="kjs-cart-item-remove" onclick="kjsCartRemove('+item.id+')">✕</button>';
        cartHtml += '</div>';
    });
    cartHtml += '</div></div>';

    // Summary
    cartHtml += '<div class="kjs-card kjs-summary-card"><div class="kjs-card-body">';
    cartHtml += '<h3>Order Summary</h3>';
    cartHtml += '<div class="kjs-summary-row"><span>Subtotal ('+Cart.count()+' items)</span><span>'+formatPrice(Cart.getTotal())+'</span></div>';
    cartHtml += '<div class="kjs-summary-row"><span>Delivery</span><span class="kjs-free">Free</span></div>';
    cartHtml += '<div class="kjs-summary-row total"><span>Total</span><span>'+formatPrice(Cart.getTotal())+'</span></div>';
    cartHtml += '<a href="'+PAGES.checkout+'" class="kjs-btn kjs-btn-primary kjs-btn-block kjs-btn-lg" style="margin-top:16px">Proceed to Checkout</a>';
    cartHtml += '<a href="'+PAGES.shop+'" class="kjs-btn kjs-btn-outline kjs-btn-block" style="margin-top:8px">Continue Shopping</a>';
    cartHtml += '</div></div></div>';

    $('#kjs-cart-content').html(cartHtml);
}

window.kjsCartQty = function(id, delta) {
    let items = Cart.get();
    let idx = items.findIndex(i => i.id == id);
    if (idx > -1) { items[idx].quantity = Math.max(1, items[idx].quantity + delta); Cart.save(items); renderCart(); }
};

window.kjsCartRemove = function(id) {
    Cart.remove(id); renderCart();
};

/* ══════════════════════════════════════
   CHECKOUT PAGE
   ══════════════════════════════════════ */
window.kjsInitCheckout = function() {
    let items = Cart.get();
    if (!items.length) { window.location.href = PAGES.shop; return; }

    let currentStep = 0;

    // Load Stripe key from Laravel API
    $.get(API + '/config', function(res) {
        if (res.success && res.stripe_key) {
            stripeKey = res.stripe_key;
        }
    });

    renderCheckoutSummary();
    updateSteps();

    // Step navigation
    $(document).on('click', '.kjs-next-step', function() {
        if (currentStep === 0 && !validateAddress()) return;
        if (currentStep === 1 && !validatePayment()) return;
        if (currentStep === 1) { submitOrder(); return; }
        currentStep++;
        updateSteps();
    });

    $(document).on('click', '.kjs-prev-step', function() {
        if (currentStep > 0) { currentStep--; updateSteps(); }
    });

    // Payment method toggle
    $(document).on('change', 'input[name="kjs_payment"]', function() {
        let val = $(this).val();
        $('.kjs-payment-option').removeClass('selected');
        $(this).closest('.kjs-payment-option').addClass('selected');
        if (val === 'stripe') {
            $('#kjs-stripe-wrap').slideDown(200);
            initStripeElements();
        } else {
            $('#kjs-stripe-wrap').slideUp(200);
        }
    });

    function updateSteps() {
        $('.kjs-step').removeClass('active done');
        for (let i = 0; i < currentStep; i++) $('.kjs-step').eq(i).addClass('done');
        $('.kjs-step').eq(currentStep).addClass('active');
        $('.kjs-step-content').removeClass('active').eq(currentStep).addClass('active');
    }

    function renderCheckoutSummary() {
        let html = '<h3>Order Summary</h3>';
        items.forEach(i => {
            html += '<div class="kjs-summary-row"><span>'+i.name+' × '+i.quantity+'</span><span>'+formatPrice(i.price*i.quantity)+'</span></div>';
        });
        html += '<div class="kjs-summary-row"><span>Delivery</span><span class="kjs-free">Free</span></div>';
        html += '<div class="kjs-summary-row total"><span>Total</span><span>'+formatPrice(Cart.getTotal())+'</span></div>';
        $('#kjs-checkout-summary').html(html);
    }

    function initStripeElements() {
        if (cardElement || !stripeKey) return;
        stripeInstance = Stripe(stripeKey);
        stripeElements = stripeInstance.elements();
        cardElement = stripeElements.create('card', {
            style: { base: { fontSize: '16px', fontFamily: 'Inter, sans-serif', color: '#4b4b4b', '::placeholder': { color: '#82868b' } } }
        });
        cardElement.mount('#kjs-stripe-element');
        cardElement.on('change', function(e) {
            $('#kjs-card-errors').text(e.error ? e.error.message : '');
        });
    }

    function validateAddress() {
        let valid = true;
        let fields = ['kjs_guest_name','kjs_guest_email','kjs_guest_phone','kjs_full_name','kjs_phone','kjs_city'];
        fields.forEach(f => {
            let el = $('#'+f);
            if (!el.val().trim()) { el.css('border-color','var(--kjs-danger)'); valid = false; }
            else { el.css('border-color',''); }
        });
        if (!valid) showToast('Please fill all required fields.', 'error');
        return valid;
    }

    function validatePayment() {
        let method = $('input[name="kjs_payment"]:checked').val();
        if (!method) { showToast('Please select a payment method.', 'error'); return false; }
        return true;
    }

    function submitOrder() {
        let method = $('input[name="kjs_payment"]:checked').val();
        let $btn = $('#kjs-place-order-btn');
        $btn.prop('disabled', true).text('Processing...');

        if (method === 'stripe') {
            // Create PaymentIntent via Laravel API, then confirm with Stripe.js
            let cartItems = Cart.get().map(i => ({ product_id: i.id, quantity: i.quantity }));
            $.ajax({
                url: API + '/create-payment-intent',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ items: cartItems }),
                success: function(res) {
                    if (!res.success) { showToast(res.message || 'Payment failed.', 'error'); $btn.prop('disabled',false).text('Place Order'); return; }
                    stripeInstance.confirmCardPayment(res.client_secret, {
                        payment_method: { card: cardElement, billing_details: { name: $('#kjs_guest_name').val(), email: $('#kjs_guest_email').val() } }
                    }).then(function(result) {
                        if (result.error) { showToast(result.error.message, 'error'); $btn.prop('disabled',false).text('Place Order'); }
                        else if (result.paymentIntent.status === 'succeeded') { placeOrderAPI(method, result.paymentIntent.id); }
                    });
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Payment error.';
                    showToast(msg, 'error'); $btn.prop('disabled',false).text('Place Order');
                }
            });
        } else {
            placeOrderAPI(method, null);
        }
    }

    function placeOrderAPI(method, stripePI) {
        let cartItems = Cart.get().map(i => ({ product_id: i.id, quantity: i.quantity }));
        let payload = {
            guest_name: $('#kjs_guest_name').val(),
            guest_email: $('#kjs_guest_email').val(),
            guest_phone: $('#kjs_guest_phone').val(),
            full_name: $('#kjs_full_name').val(),
            phone: $('#kjs_phone').val(),
            flat_house: $('#kjs_flat_house').val(),
            landmark: $('#kjs_landmark').val(),
            city: $('#kjs_city').val(),
            pincode: $('#kjs_pincode').val(),
            state: $('#kjs_state').val(),
            address_type: $('#kjs_address_type').val(),
            payment_method: method,
            stripe_payment_intent: stripePI,
            items: cartItems,
        };

        $.ajax({
            url: API + '/place-order',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                if (res.success) {
                    Cart.clear();
                    let successUrl = PAGES.order_success + (PAGES.order_success.indexOf('?') > -1 ? '&' : '?');
                    successUrl += 'order_number=' + res.order.order_number + '&total=' + res.order.total_amount + '&status=' + res.order.payment_status;
                    window.location.href = successUrl;
                } else {
                    showToast(res.message || 'Order failed.', 'error');
                    $('#kjs-place-order-btn').prop('disabled',false).text('Place Order');
                }
            },
            error: function(xhr) {
                let msg = 'Something went wrong.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    else if (xhr.responseJSON.errors) msg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                showToast(msg, 'error');
                $('#kjs-place-order-btn').prop('disabled',false).text('Place Order');
            }
        });
    }
};

/* ══════════════════════════════════════
   ORDER SUCCESS PAGE
   ══════════════════════════════════════ */
window.kjsInitOrderSuccess = function() {
    let orderNum = getParam('order_number');
    let total = getParam('total');
    let status = getParam('status');

    let html = '<div class="kjs-success-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>';
    html += '<h1>Order Placed Successfully!</h1>';
    if (orderNum) html += '<p class="kjs-order-num">Order #' + orderNum + '</p>';
    html += '<div class="kjs-success-details">';
    if (total) html += '<div class="kjs-summary-row"><span>Total Amount</span><span><strong>$'+parseFloat(total).toFixed(2)+'</strong></span></div>';
    if (status) html += '<div class="kjs-summary-row"><span>Payment Status</span><span><span class="kjs-badge kjs-badge-'+(status==='paid'?'success':'warning')+'">'+status.toUpperCase()+'</span></span></div>';
    html += '<div class="kjs-summary-row"><span>Delivery</span><span class="kjs-free" style="color:var(--kjs-success);font-weight:600">Free</span></div>';
    html += '</div>';
    html += '<p style="color:var(--kjs-secondary);margin-bottom:20px">Thank you for your order! You will receive a confirmation soon.</p>';
    html += '<a href="'+PAGES.shop+'" class="kjs-btn kjs-btn-primary kjs-btn-lg">Continue Shopping</a>';

    $('#kjs-order-success').html(html);
};

/* ══════════════════════════════════════
   INIT ON READY
   ══════════════════════════════════════ */
$(function() {
    Cart.updateBadge();
});

})(jQuery);

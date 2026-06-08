<div class="kjs-wrap">
    <div class="kjs-container">
        <div class="kjs-page-header">
            <h1>Checkout</h1>
            <p>Complete your order</p>
        </div>

        <!-- Steps -->
        <div class="kjs-steps">
            <div class="kjs-step active">
                <span class="kjs-step-num">1</span>
                <span class="kjs-step-label">Address & Info</span>
            </div>
            <div class="kjs-step">
                <span class="kjs-step-num">2</span>
                <span class="kjs-step-label">Payment</span>
            </div>
        </div>

        <div class="kjs-checkout-layout">
            <div>
                <!-- Step 1: Address -->
                <div class="kjs-step-content active">
                    <div class="kjs-card">
                        <div class="kjs-card-body">
                            <h3 style="margin:0 0 20px;color:var(--kjs-dark);font-size:18px;font-weight:700">Your Information</h3>
                            <div class="kjs-form-row">
                                <div class="kjs-form-group">
                                    <label>Full Name <span class="req">*</span></label>
                                    <input type="text" id="kjs_guest_name" placeholder="John Doe">
                                </div>
                                <div class="kjs-form-group">
                                    <label>Email <span class="req">*</span></label>
                                    <input type="email" id="kjs_guest_email" placeholder="john@example.com">
                                </div>
                            </div>
                            <div class="kjs-form-group">
                                <label>Phone <span class="req">*</span></label>
                                <input type="tel" id="kjs_guest_phone" placeholder="+1 234 567 890">
                            </div>

                            <h3 style="margin:24px 0 20px;color:var(--kjs-dark);font-size:18px;font-weight:700;padding-top:16px;border-top:1px solid var(--kjs-border)">Shipping Address</h3>
                            <div class="kjs-form-row">
                                <div class="kjs-form-group">
                                    <label>Recipient Name <span class="req">*</span></label>
                                    <input type="text" id="kjs_full_name" placeholder="John Doe">
                                </div>
                                <div class="kjs-form-group">
                                    <label>Phone <span class="req">*</span></label>
                                    <input type="tel" id="kjs_phone" placeholder="0123456789">
                                </div>
                            </div>
                            <div class="kjs-form-row">
                                <div class="kjs-form-group">
                                    <label>Flat / House No</label>
                                    <input type="text" id="kjs_flat_house" placeholder="9447 Glen Eagles Drive">
                                </div>
                                <div class="kjs-form-group">
                                    <label>Landmark</label>
                                    <input type="text" id="kjs_landmark" placeholder="Near Apollo Hospital">
                                </div>
                            </div>
                            <div class="kjs-form-row">
                                <div class="kjs-form-group">
                                    <label>City <span class="req">*</span></label>
                                    <input type="text" id="kjs_city" placeholder="New York">
                                </div>
                                <div class="kjs-form-group">
                                    <label>Pincode</label>
                                    <input type="text" id="kjs_pincode" placeholder="10001">
                                </div>
                            </div>
                            <div class="kjs-form-row">
                                <div class="kjs-form-group">
                                    <label>State</label>
                                    <input type="text" id="kjs_state" placeholder="California">
                                </div>
                                <div class="kjs-form-group">
                                    <label>Address Type</label>
                                    <select id="kjs_address_type">
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                    </select>
                                </div>
                            </div>
                            <button class="kjs-btn kjs-btn-primary kjs-btn-lg kjs-next-step" style="margin-top:8px">Continue to Payment →</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Payment -->
                <div class="kjs-step-content">
                    <div class="kjs-card">
                        <div class="kjs-card-body">
                            <div class="kjs-secure-header">
                                <h3>🔒 Secure Payment</h3>
                                <div class="kjs-card-logos">
                                    <svg viewBox="0 0 32 20" width="32" height="20" fill="none"><rect width="32" height="20" rx="2" fill="#fff"/><path d="M11 6h3l-2 8H9l2-8zm9.5 0h-2.5c-.8 0-1.4.6-1.4 1.4v.6h2v-1c0-.2.2-.4.4-.4h1c.2 0 .4.2.4.4v1c0 .2-.2.4-.4.4h-1c-.8 0-1.4.6-1.4 1.4v.6h-2v-1c0-.8.6-1.4 1.4-1.4h1c.2 0 .4-.2.4-.4v-1c0-.2-.2-.4-.4-.4zm-8.5 0h-2v8h2v-8zm8.5 4h-2.5c-.8 0-1.4.6-1.4 1.4v.6h2v-1c0-.2.2-.4.4-.4h1c.2 0 .4.2.4.4v1c0 .2-.2.4-.4.4h-1c-.8 0-1.4.6-1.4 1.4v.6h-2v-1c0-.8.6-1.4 1.4-1.4h1c.2 0 .4-.2.4-.4v-1c0-.2-.2-.4-.4-.4zm3-4h-2v8h3c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-1zm0 2h1c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1h-1V8z" fill="#1434CB"/></svg>
                                    <svg viewBox="0 0 32 20" width="32" height="20" fill="none"><rect width="32" height="20" rx="2" fill="#fff"/><circle cx="11.5" cy="10" r="5.5" fill="#EB001B"/><circle cx="20.5" cy="10" r="5.5" fill="#F79E1B"/><path d="M16 14.5A5.5 5.5 0 0116 5.5a5.5 5.5 0 010 9z" fill="#FF5F00"/></svg>
                                </div>
                            </div>

                            <label class="kjs-payment-option selected">
                                <input type="radio" name="kjs_payment" value="stripe" checked>
                                <div class="kjs-payment-label">
                                    <strong>💳 Credit / Debit Card</strong>
                                    <span>Secure payment via Stripe</span>
                                </div>
                            </label>

                            <div id="kjs-stripe-wrap">
                                <div id="kjs-stripe-element"></div>
                                <div id="kjs-card-errors" class="kjs-stripe-error"></div>
                            </div>

                            <label class="kjs-payment-option">
                                <input type="radio" name="kjs_payment" value="cod">
                                <div class="kjs-payment-label">
                                    <strong>💵 Cash On Delivery</strong>
                                    <span>Pay when you receive the order</span>
                                </div>
                            </label>

                            <div style="display:flex;gap:12px;margin-top:24px">
                                <button class="kjs-btn kjs-btn-outline kjs-prev-step">← Back</button>
                                <button class="kjs-btn kjs-btn-primary kjs-btn-lg kjs-next-step" id="kjs-place-order-btn" style="flex:1">🔒 Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="kjs-card kjs-summary-card">
                <div class="kjs-card-body" id="kjs-checkout-summary">
                    <div class="kjs-loader"><div class="kjs-spinner"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>jQuery(function(){ kjsInitCheckout(); });</script>

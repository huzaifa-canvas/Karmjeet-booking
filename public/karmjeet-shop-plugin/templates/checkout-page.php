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
                            <h3 style="margin:0 0 20px;color:var(--kjs-dark);font-size:18px;font-weight:700">Payment Method</h3>

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

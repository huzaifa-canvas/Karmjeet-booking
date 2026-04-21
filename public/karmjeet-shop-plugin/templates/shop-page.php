<div class="kjs-wrap" id="kjs-shop-container">
    <div class="kjs-container">
        <div class="kjs-page-header">
            <h1>Shop</h1>
            <p>Browse our premium collection of products</p>
        </div>

        <button class="kjs-btn kjs-btn-outline kjs-filter-toggle">☰ Filters</button>
        <div class="kjs-overlay"></div>

        <div class="kjs-shop-layout">
            <!-- Sidebar Filters -->
            <div class="kjs-sidebar">
                <div class="kjs-filter-card">
                    <div class="kjs-search-bar">
                        <input type="text" id="kjs-search" placeholder="Search products...">
                        <span class="kjs-search-icon">🔍</span>
                    </div>
                </div>

                <div class="kjs-filter-card">
                    <div class="kjs-filter-title">Price Range</div>
                    <ul class="kjs-filter-list" id="kjs-price-list">
                        <li><label><input type="radio" name="kjs-price" class="kjs-filter-radio" data-filter="price_range" value="" checked> All Prices</label></li>
                        <li><label><input type="radio" name="kjs-price" class="kjs-filter-radio" data-filter="price_range" value="0-10"> Under $10</label></li>
                        <li><label><input type="radio" name="kjs-price" class="kjs-filter-radio" data-filter="price_range" value="10-100"> $10 – $100</label></li>
                        <li><label><input type="radio" name="kjs-price" class="kjs-filter-radio" data-filter="price_range" value="100-500"> $100 – $500</label></li>
                        <li><label><input type="radio" name="kjs-price" class="kjs-filter-radio" data-filter="price_range" value="500+"> $500 &amp; Above</label></li>
                    </ul>
                </div>

                <div class="kjs-filter-card">
                    <div class="kjs-filter-title">Categories</div>
                    <ul class="kjs-filter-list" id="kjs-category-list">
                        <li><label>Loading...</label></li>
                    </ul>
                </div>

                <div class="kjs-filter-card">
                    <div class="kjs-filter-title">Brands</div>
                    <ul class="kjs-filter-list" id="kjs-brand-list">
                        <li><label>Loading...</label></li>
                    </ul>
                </div>
            </div>

            <!-- Products Area -->
            <div>
                <div class="kjs-toolbar">
                    <span class="kjs-results-count" id="kjs-results-count">Loading...</span>
                    <select class="kjs-sort-select" id="kjs-sort">
                        <option value="">Featured</option>
                        <option value="lowest">Price: Low to High</option>
                        <option value="highest">Price: High to Low</option>
                    </select>
                </div>

                <div class="kjs-product-grid" id="kjs-products">
                    <div class="kjs-loader"><div class="kjs-spinner"></div></div>
                </div>

                <div class="kjs-pagination" id="kjs-pagination"></div>
            </div>
        </div>
    </div>
</div>

<script>jQuery(function(){ kjsInitShop(); });</script>

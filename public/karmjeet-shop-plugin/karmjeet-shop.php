<?php
/**
 * Plugin Name: Karmjeet Shop
 * Plugin URI:  https://karmjeet.com
 * Description: E-Commerce shop powered by Karmjeet Laravel backend. Use shortcodes: [karmjeet_shop], [karmjeet_product], [karmjeet_cart], [karmjeet_checkout], [karmjeet_order_success]
 * Version:     1.0.0
 * Author:      Karmjeet
 * Text Domain: karmjeet-shop
 */

if (!defined('ABSPATH')) exit;

define('KJS_VERSION', '1.1.0');
define('KJS_PATH', plugin_dir_path(__FILE__));
define('KJS_URL', plugin_dir_url(__FILE__));

/**
 * ── Activation Hook: Auto Create Pages ────────────────────
 */
register_activation_hook(__FILE__, 'kjs_activate_plugin');

function kjs_activate_plugin() {
    $pages = [
        'shop'          => ['title' => 'Shop', 'shortcode' => '[karmjeet_shop]'],
        'product'       => ['title' => 'Product Details', 'shortcode' => '[karmjeet_product]'],
        'cart'          => ['title' => 'Shopping Cart', 'shortcode' => '[karmjeet_cart]'],
        'checkout'      => ['title' => 'Checkout', 'shortcode' => '[karmjeet_checkout]'],
        'order_success' => ['title' => 'Order Success', 'shortcode' => '[karmjeet_order_success]'],
    ];

    foreach ($pages as $slug => $pageData) {
        // Check if a page with this shortcode already exists
        global $wpdb;
        $exists = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE %s AND post_status='publish' AND post_type='page' LIMIT 1", '%' . $pageData['shortcode'] . '%'));

        if (!$exists) {
            // Create page. WordPress handles slug collision automatically (e.g., shop-2)
            wp_insert_post([
                'post_title'   => $pageData['title'],
                'post_content' => $pageData['shortcode'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
        }
    }
}

/**
 * ── Settings Page ─────────────────────────────────────────
 */
add_action('admin_menu', function () {
    add_options_page('Karmjeet Shop Settings', 'Karmjeet Shop', 'manage_options', 'karmjeet-shop', 'kjs_settings_page');
});

add_action('admin_init', function () {
    register_setting('kjs_settings', 'kjs_api_url');
    register_setting('kjs_settings', 'kjs_api_key');
});

function kjs_settings_page() {
    ?>
    <div class="wrap">
        <h1>Karmjeet Shop Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('kjs_settings'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="kjs_api_url">Laravel API Base URL</label></th>
                    <td>
                        <input type="url" id="kjs_api_url" name="kjs_api_url" value="<?php echo esc_attr(get_option('kjs_api_url', 'http://localhost')); ?>" class="regular-text" placeholder="http://karmjeet-booking.test" />
                        <p class="description">Enter the base URL of your Laravel application (without trailing slash).</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="kjs_api_key">Laravel API Key</label></th>
                    <td>
                        <input type="password" id="kjs_api_key" name="kjs_api_key" value="<?php echo esc_attr(get_option('kjs_api_key', '')); ?>" class="regular-text" placeholder="Enter WP_API_KEY from .env" />
                        <p class="description">This key secures the communication. Must match the WP_API_KEY in your Laravel .env file.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * ── Enqueue Assets ────────────────────────────────────────
 */
add_action('wp_enqueue_scripts', function () {
    // Google Font
    wp_enqueue_style('kjs-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', [], null);

    // Plugin CSS
    wp_enqueue_style('kjs-style', KJS_URL . 'assets/css/karmjeet-shop.css', [], KJS_VERSION);

    // Plugin JS
    wp_enqueue_script('kjs-script', KJS_URL . 'assets/js/karmjeet-shop.js', ['jquery'], KJS_VERSION, true);

    // Fetch tax rates from Laravel API (server-side, no CORS issues)
    $api_base = rtrim(get_option('kjs_api_url', 'http://localhost'), '/') . '/api/wp-shop';
    $api_key  = get_option('kjs_api_key', '');
    $gst_rate = 0;
    $pst_rate = 0;

    $config_response = wp_remote_get($api_base . '/config', [
        'headers' => ['X-API-Key' => $api_key],
        'timeout' => 5,
    ]);

    if (!is_wp_error($config_response)) {
        $config_body = json_decode(wp_remote_retrieve_body($config_response), true);
        if (!empty($config_body['success'])) {
            $gst_rate = floatval($config_body['gst_rate'] ?? 0);
            $pst_rate = floatval($config_body['pst_rate'] ?? 0);
        }
    }

    // Pass config to JS
    wp_localize_script('kjs-script', 'KJS_CONFIG', [
        'api_url'    => $api_base,
        'image_path' => rtrim(get_option('kjs_api_url', 'http://localhost'), '/'),
        'api_key'    => $api_key,
        'site_url'   => home_url(),
        'ajax_url'   => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('kjs_nonce'),
        'gst_rate'   => $gst_rate,
        'pst_rate'   => $pst_rate,
        // Page URLs (auto-detected from shortcode pages or customizable)
        'pages'    => [
            'shop'          => kjs_get_page_url('karmjeet_shop'),
            'product'       => kjs_get_page_url('karmjeet_product'),
            'cart'          => kjs_get_page_url('karmjeet_cart'),
            'checkout'      => kjs_get_page_url('karmjeet_checkout'),
            'order_success' => kjs_get_page_url('karmjeet_order_success'),
        ],
    ]);
});

/**
 * Helper: Find the URL of a page containing a specific shortcode.
 */
function kjs_get_page_url($shortcode) {
    global $wpdb;
    $page = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE '%[{$shortcode}%' AND post_status='publish' AND post_type='page' LIMIT 1");
    return $page ? get_permalink($page) : home_url();
}

/**
 * ── Shortcodes ────────────────────────────────────────────
 */

// [karmjeet_shop]
add_shortcode('karmjeet_shop', function () {
    ob_start();
    include KJS_PATH . 'templates/shop-page.php';
    return ob_get_clean();
});

// [karmjeet_product]
add_shortcode('karmjeet_product', function () {
    ob_start();
    include KJS_PATH . 'templates/product-detail.php';
    return ob_get_clean();
});

// [karmjeet_cart]
add_shortcode('karmjeet_cart', function () {
    ob_start();
    include KJS_PATH . 'templates/cart-page.php';
    return ob_get_clean();
});

// [karmjeet_checkout]
add_shortcode('karmjeet_checkout', function () {
    ob_start();
    include KJS_PATH . 'templates/checkout-page.php';
    return ob_get_clean();
});

// [karmjeet_order_success]
add_shortcode('karmjeet_order_success', function () {
    ob_start();
    include KJS_PATH . 'templates/order-success.php';
    return ob_get_clean();
});

/**
 * ── Cart Icon Shortcode ───────────────────────────────────
 */
add_shortcode('karmjeet_cart_icon', 'kjs_shortcode_cart_icon');
function kjs_shortcode_cart_icon() {
    $cart_url = site_url('/cart'); // Fallback if no specific page found, you can modify it via JS anyway
    // Try to find the cart page URL dynamically
    global $wpdb;
    $cart_page_id = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE '%[karmjeet_cart]%' AND post_status='publish' AND post_type='page' LIMIT 1");
    if ($cart_page_id) {
        $cart_url = get_permalink($cart_page_id);
    }
    
    return '<a href="' . esc_url($cart_url) . '" class="kjs-menu-cart">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <span class="kjs-cart-badge kjs-cart-count-global">0</span>
    </a>';
}

/**
 * ── Append Cart to Nav Menu ───────────────────────────────
 */
// add_filter('wp_nav_menu_items', 'kjs_add_cart_to_menu', 10, 2);
// function kjs_add_cart_to_menu($items, $args) {
//     // Optional: Only add to primary menu (uncomment and change 'primary' if needed)
//     // if ($args->theme_location == 'primary') {
//         $items .= '<li class="menu-item kjs-menu-item-cart">' . kjs_shortcode_cart_icon() . '</li>';
//     // }
//     return $items;
// }

/**
 * ── Stripe.js (only on checkout page) ─────────────────────
 */
add_action('wp_enqueue_scripts', function () {
    global $post;
    if ($post && has_shortcode($post->post_content, 'karmjeet_checkout')) {
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], null, true);
    }
});

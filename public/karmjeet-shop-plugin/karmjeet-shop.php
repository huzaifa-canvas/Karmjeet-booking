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

define('KJS_VERSION', '1.0.0');
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

    // Pass config to JS
    wp_localize_script('kjs-script', 'KJS_CONFIG', [
        'api_url'  => rtrim(get_option('kjs_api_url', 'http://localhost'), '/') . '/api/wp-shop',
        'api_key'  => get_option('kjs_api_key', ''),
        'site_url' => home_url(),
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('kjs_nonce'),
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
 * ── Stripe.js (only on checkout page) ─────────────────────
 */
add_action('wp_enqueue_scripts', function () {
    global $post;
    if ($post && has_shortcode($post->post_content, 'karmjeet_checkout')) {
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], null, true);
    }
});

<?php
/**
 * Plugin Name: WooCommerce Product Exporter
 * Description: A plugin to export WooCommerce products to a CSV file with ID, Title, SKU and Slug
 * Version: 1.0
 * Author: Fabio Baccaglioni
 * Text Domain: woocommerce-product-exporter
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * WC requires at least: 3.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'WC_PRODUCT_EXPORTER_VERSION', '1.0' );
define( 'WC_PRODUCT_EXPORTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_PRODUCT_EXPORTER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if WooCommerce is active
 */
function wc_product_exporter_is_woocommerce_active() {
    $active_plugins = (array) get_option( 'active_plugins', array() );
    if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
    }
    return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
}

/**
 * Show admin notice if WooCommerce is not active
 */
function wc_product_exporter_admin_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php esc_html_e( 'WooCommerce Product Exporter requires WooCommerce to be installed and activated.', 'woocommerce-product-exporter' ); ?></p>
    </div>
    <?php
}

/**
 * Initialize the plugin
 */
function wc_product_exporter_init() {
    // Check if WooCommerce is active
    if ( ! wc_product_exporter_is_woocommerce_active() ) {
        add_action( 'admin_notices', 'wc_product_exporter_admin_notice' );
        return;
    }

    // Include necessary files
    require_once WC_PRODUCT_EXPORTER_PATH . 'includes/class-wc-product-exporter.php';
    require_once WC_PRODUCT_EXPORTER_PATH . 'includes/class-wc-exporter-admin.php';

    // Initialize the admin interface
    $admin = new WC_Exporter_Admin();
    add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
}
add_action( 'plugins_loaded', 'wc_product_exporter_init' );
?>
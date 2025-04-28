<?php
/**
 * WooCommerce Product Exporter Admin Class
 *
 * Handles all admin-related functionality for the product exporter.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Exporter_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        // Register the AJAX handler for CSV export
        add_action( 'wp_ajax_wc_product_exporter_download', array( $this, 'handle_ajax_download' ) );
        add_action( 'wp_ajax_nopriv_wc_product_exporter_download', array( $this, 'handle_ajax_download' ) );
    }

    /**
     * Add admin menu item
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __( 'Product Exporter', 'woocommerce-product-exporter' ),
            __( 'Product Exporter', 'woocommerce-product-exporter' ),
            'manage_woocommerce',
            'wc-product-exporter',
            array( $this, 'render_export_page' )
        );
    }

    /**
     * Render the export page
     */
    public function render_export_page() {
        include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/export-page.php';
    }
    
    /**
     * Handle the AJAX request to download CSV data
     */
    public function handle_ajax_download() {
        // Verify nonce
        if ( 
            ! isset( $_GET['wc_product_export_nonce'] ) || 
            ! wp_verify_nonce( $_GET['wc_product_export_nonce'], 'wc_product_export' ) 
        ) {
            wp_die( __( 'Security check failed. Please try again.', 'woocommerce-product-exporter' ) );
        }

        // Check user permissions
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( __( 'You do not have sufficient permissions to export products.', 'woocommerce-product-exporter' ) );
        }

        // Clear any previous output
        if ( ob_get_level() ) {
            ob_end_clean();
        }
        
        // Generate CSV data
        $exporter = new WC_Product_Exporter();
        $csv_data = $exporter->generate_csv();

        // Set headers for CSV download
        nocache_headers();
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="woocommerce-products-export-' . date( 'Y-m-d' ) . '.csv"' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        // Output the CSV data
        echo $csv_data;
        exit;
    }
}
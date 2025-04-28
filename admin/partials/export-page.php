<?php
// This file contains the HTML structure for the export page where users can trigger the export of products.

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Export WooCommerce Products', 'woocommerce-product-exporter' ); ?></h1>
    
    <div class="notice notice-info">
        <p><?php esc_html_e( 'This tool allows you to export your WooCommerce products to a CSV file containing ID, Title, SKU, and Slug.', 'woocommerce-product-exporter' ); ?></p>
    </div>
    
    <div class="card">
        <form method="get" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <input type="hidden" name="action" value="wc_product_exporter_download">
            <?php wp_nonce_field( 'wc_product_export', 'wc_product_export_nonce' ); ?>
            <?php submit_button( esc_html__( 'Export Products', 'woocommerce-product-exporter' ), 'primary', 'submit', false ); ?>
        </form>
    </div>
</div>
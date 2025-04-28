<?php
/**
 * WooCommerce Product Exporter Class
 * 
 * Handles the export of products to CSV with ID, Title, SKU and Slug.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Product_Exporter {
    /**
     * Generate CSV with product data
     * 
     * @return string CSV content
     */
    public function generate_csv() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return 'WooCommerce is not active. Please activate it to export products.';
        }

        // Initialize CSV output
        $csv_output = '';
        
        // Add CSV headers
        $headers = array( 'ID', 'Title', 'SKU', 'Slug' );
        $csv_output .= implode( ',', $headers ) . "\n";
        
        // Get all products
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );
        
        $products = get_posts( $args );
        
        if ( ! empty( $products ) ) {
            foreach ( $products as $product ) {
                $product_obj = wc_get_product( $product->ID );
                
                if ( ! $product_obj ) {
                    continue;
                }
                
                // Get product data
                $product_id    = $product_obj->get_id();
                $product_title = $this->escape_csv( $product_obj->get_name() );
                $product_sku   = $this->escape_csv( $product_obj->get_sku() );
                $product_slug  = $this->escape_csv( $product->post_name );
                
                // Add product line to CSV
                $csv_output .= "{$product_id},{$product_title},{$product_sku},{$product_slug}\n";
            }
        }
        
        return $csv_output;
    }
    
    /**
     * Escape a string for CSV output
     * 
     * @param string $str String to escape
     * @return string Escaped string
     */
    private function escape_csv( $str ) {
        // If string contains comma, quote, or newline, wrap in quotes and escape internal quotes
        if ( strpos( $str, ',' ) !== false || strpos( $str, '"' ) !== false || strpos( $str, "\n" ) !== false ) {
            return '"' . str_replace( '"', '""', $str ) . '"';
        }
        return $str;
    }
}
?>
<?php
/**
 * Advanced Add To Cart Widget (initial minimal wrapper)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded yet to avoid fatals
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
    return;
}

class RS_Elementor_Widget_Advanced_Add_To_Cart extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rs_advanced_add_to_cart';
    }

    public function get_title() {
        return esc_html__( 'Advanced Add To Cart', 'rs-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-cart-medium';
    }

    public function get_categories() {
        return [ 'rs-woocommerce' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'product', 'cart', 'add to cart', 'button' ];
    }

    public function get_style_depends() {
        return [];
    }

    public function get_script_depends() {
        return [];
    }

    protected function register_controls() {
        // No controls for the initial minimal wrapper
    }

    protected function render() {
        // Ensure we have a product context
        global $product;

        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            // Try to get from the main query (single product)
            if ( function_exists( 'wc_get_product' ) ) {
                $queried = get_queried_object_id();
                if ( $queried ) {
                    $product = wc_get_product( $queried );
                }
            }
        }

        if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
            return; // No product context available
        }

        echo '<div class="rs-advanced-add-to-cart">';

        // Render WooCommerce's default add-to-cart area for the current product
        if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
            woocommerce_template_single_add_to_cart();
        }

        echo '</div>';
    }
}

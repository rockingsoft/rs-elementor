<?php
/**
 * Plugin Name: RS Elementor Widgets for WooCommerce
 * Description: Custom Elementor widgets for WooCommerce stores
 * Version: 1.0.0
 * Author: RS Development
 * Text Domain: rs-elementor-widgets
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * WC requires at least: 3.0
 * Elementor requires at least: 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main RS Elementor Widgets Class
 */
final class RS_Elementor_Widgets {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor is installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
            return;
        }

        // Check if WooCommerce is installed and activated
        if ( ! class_exists( 'WooCommerce' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_woocommerce' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Register widgets
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        
        // Register widget categories
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
        
        // Register widget styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        
        // Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
    }

    /**
     * Admin notice for missing Elementor
     */
    public function admin_notice_missing_elementor() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'rs-elementor-widgets' ),
            '<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'rs-elementor-widgets' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice for missing WooCommerce
     */
    public function admin_notice_missing_woocommerce() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: WooCommerce */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'rs-elementor-widgets' ),
            '<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'WooCommerce', 'rs-elementor-widgets' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice for minimum Elementor version
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-elementor-widgets' ),
            '<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'rs-elementor-widgets' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice for minimum PHP version
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-elementor-widgets' ),
            '<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'rs-elementor-widgets' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Add Elementor widget category
     */
    public function add_elementor_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'rs-woocommerce',
            [
                'title' => esc_html__( 'RS WooCommerce', 'rs-elementor-widgets' ),
                'icon' => 'fa fa-shopping-cart',
            ]
        );
    }

    /**
     * Register widgets
     */
    public function register_widgets( $widgets_manager ) {
        // Include Widget files
        require_once( __DIR__ . '/widgets/product-reviews.php' );
        
        // Register widgets
        $widgets_manager->register( new \RS_Elementor_Widget_Product_Reviews() );
    }

    /**
     * Register widget styles
     */
    public function widget_styles() {
        wp_register_style( 'rs-elementor-widgets', plugins_url( 'assets/css/rs-elementor-widgets.css', __FILE__ ) );
        wp_enqueue_style( 'rs-elementor-widgets' );
    }

    /**
     * Register widget scripts
     */
    public function widget_scripts() {
        // Currently, our scripts are embedded directly in the widget render method
        // This method is kept for future script registrations
        // Example of how to register scripts:
        // wp_register_script('rs-reviews-modal', plugins_url('assets/js/reviews-modal.js', __FILE__), ['jquery'], self::VERSION, true);
    }
}

// Initialize the plugin
RS_Elementor_Widgets::instance();

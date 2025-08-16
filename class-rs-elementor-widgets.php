<?php
/**
 * Plugin Name: RS Elementor Widgets for WooCommerce
 * Description: Custom Elementor widgets for WooCommerce stores.
 * Version: 1.1.0
 * Author: RS Development
 * Text Domain: rs-elementor-widgets
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * WC requires at least: 3.0
 * Elementor requires at least: 3.0.0
 *
 * @package RS_Elementor_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main RS Elementor Widgets Class.
 */
final class RS_Elementor_Widgets {

	/**
	 * Plugin Version
	 */
	const VERSION = '1.1.0';

	/**
	 * Minimum Elementor Version
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Singleton instance.
	 *
	 * @var RS_Elementor_Widgets|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return RS_Elementor_Widgets
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		// Check if Elementor is installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
			return;
		}

		// Check if WooCommerce is installed and activated.
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_woocommerce' ) );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Register widgets.
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Register widget categories.
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );

		// Register widget styles.
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'widget_styles' ) );

		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		// Also register on core front-end hook to ensure availability even if Elementor load order differs.
		add_action( 'wp_enqueue_scripts', array( $this, 'widget_scripts' ), 9 );
		// Failsafe: enqueue our key frontend script on WooCommerce contexts to guarantee it's present.
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_frontend_scripts' ), 11 );
	}

	/**
	 * Admin notice for missing Elementor.
	 */
	public function admin_notice_missing_elementor() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'rs-elementor-widgets' ),
			'<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'rs-elementor-widgets' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice for missing WooCommerce.
	 */
	public function admin_notice_missing_woocommerce() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: WooCommerce */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'rs-elementor-widgets' ),
			'<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'rs-elementor-widgets' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice for minimum Elementor version.
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-elementor-widgets' ),
			'<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'rs-elementor-widgets' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice for minimum PHP version.
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'rs-elementor-widgets' ),
			'<strong>' . esc_html__( 'RS Elementor Widgets', 'rs-elementor-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'rs-elementor-widgets' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Add Elementor widget category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager instance.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'rs-woocommerce',
			array(
				'title' => esc_html__( 'RS WooCommerce', 'rs-elementor-widgets' ),
				'icon'  => 'fa fa-shopping-cart',
			)
		);
	}

	/**
	 * Register widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		// Include Widget files.
		include_once __DIR__ . '/widgets/product-reviews.php';
		include_once __DIR__ . '/widgets/advanced-product-images.php';
		include_once __DIR__ . '/widgets/variation-chooser.php';
		include_once __DIR__ . '/widgets/advanced-add-to-cart.php';
		include_once __DIR__ . '/widgets/advanced-info-table.php';

		// Register widgets.
		$widgets_manager->register( new \RS_Elementor_Widget_Product_Reviews() );
		$widgets_manager->register( new \RS_Elementor_Widget_Advanced_Product_Images() );
		$widgets_manager->register( new \RS_Elementor_Widget_Variation_Chooser() );
		$widgets_manager->register( new \RS_Elementor_Widget_Advanced_Add_To_Cart() );
		$widgets_manager->register( new \RS_Elementor_Widget_Advanced_Info_Table() );
	}

	/**
	 * Register widget styles.
	 */
	public function widget_styles() {
		// Per-widget styles (registered only; enqueued via get_style_depends on widgets).
		$css_files = array(
			'rs-advanced-add-to-cart'   => 'assets/css/advanced-add-to-cart.css',
			'rs-advanced-product-images' => 'assets/css/advanced-product-images.css',
			'rs-product-reviews'        => 'assets/css/product-reviews.css',
			'rs-variation-chooser'      => 'assets/css/variation-chooser.css',
			'rs-advanced-info-table'    => 'assets/css/advanced-info-table.css',
		);
		foreach ( $css_files as $handle => $rel ) {
			$path = plugin_dir_path( __FILE__ ) . $rel;
			$ver  = file_exists( $path ) ? filemtime( $path ) : self::VERSION;
			wp_register_style( $handle, plugins_url( $rel, __FILE__ ), array(), $ver );
		}
	}

	/**
	 * Register widget scripts.
	 */
	public function widget_scripts() {
		// Per-widget scripts (registered only; enqueued via get_script_depends on widgets).
		$scripts = array(
			'rs-advanced-product-images' => array( 'rel' => 'assets/js/advanced-product-images.js', 'deps' => array(), 'in_footer' => true ),
			'rs-product-reviews'        => array( 'rel' => 'assets/js/product-reviews.js', 'deps' => array(), 'in_footer' => true ),
			'rs-variation-chooser'      => array( 'rel' => 'assets/js/variation-chooser.js', 'deps' => array( 'jquery' ), 'in_footer' => true ),
		);
		foreach ( $scripts as $handle => $data ) {
			$path = plugin_dir_path( __FILE__ ) . $data['rel'];
			$ver  = file_exists( $path ) ? filemtime( $path ) : self::VERSION;
			wp_register_script( $handle, plugins_url( $data['rel'], __FILE__ ), $data['deps'], $ver, $data['in_footer'] );
		}

		// Advanced add-to-cart helper: depends on Woo core scripts for AJAX + variations so it's fully compatible with carts like FunnelKit.
		$aatc_rel  = 'assets/js/advanced-add-to-cart.js';
		$aatc_path = plugin_dir_path( __FILE__ ) . $aatc_rel;
		$aatc_ver  = file_exists( $aatc_path ) ? filemtime( $aatc_path ) : self::VERSION;
		wp_register_script(
			'rs-advanced-add-to-cart',
			plugins_url( $aatc_rel, __FILE__ ),
			array( 'jquery', 'wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-cart-fragments' ),
			$aatc_ver,
			true
		);
	}

	/**
	 * Enqueue critical scripts on WooCommerce contexts as a failsafe.
	 */
	public function maybe_enqueue_frontend_scripts() {
		// Temporarily enqueue globally on frontend to guarantee availability while diagnosing load issues.
		if ( ! is_admin() ) {
			wp_enqueue_script( 'rs-advanced-add-to-cart' );
		}
	}
}

// Initialize the plugin.
RS_Elementor_Widgets::instance();

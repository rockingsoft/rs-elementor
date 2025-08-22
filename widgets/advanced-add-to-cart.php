<?php
/**
 * Advanced Add To Cart Widget (initial minimal wrapper).
 *
 * @package RS_Elementor_Widgets
 *
 * phpcs:disable WordPress.Files.FileName
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded yet to avoid fatals.
if ( ! class_exists( '\lementor\\Widget_Base' ) ) {
	return;
}

/**
 * Advanced Add To Cart widget.
 */
class RS_Elementor_Widget_Advanced_Add_To_Cart extends \Elementor\Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rs_advanced_add_to_cart';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Advanced Add To Cart', 'rs-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-cart-medium';
	}

	/**
	 * Widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'rs-woocommerce' );
	}

	/**
	 * Keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'product', 'cart', 'add to cart', 'button' );
	}

	/**
	 * Styles this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'rs-advanced-add-to-cart' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		// Load our helper which depends on WooCommerce core scripts for AJAX & variations.
		return array( 'rs-advanced-add-to-cart' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		// Content controls.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'disable_quantity',
			array(
				'label'        => esc_html__( 'Disable Quantity', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .quantity' => 'display: none !important;',
				),
			)
		);

		$this->add_control(
			'hide_stock_notices',
			array(
				'label'        => esc_html__( 'Hide Stock Notices', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		// Option: Hide native WooCommerce variations UI (useful when using our Variation Chooser widget).
		$this->add_control(
			'hide_wc_variations',
			array(
				'label'        => esc_html__( 'Hide Native Variations', 'rs-elementor-widgets' ),
				'description'  => esc_html__( 'Hides WooCommerce\'s default variation selectors inside this widget. Useful if you add the Variation Chooser widget.', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Hide', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'Show', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart.rs-hide-wc-variations .variations' => 'display: none !important;',
					'{{WRAPPER}} .rs-advanced-add-to-cart.rs-hide-wc-variations .reset_variations' => 'display: none !important;',
				),
			)
		);

		// Option: Hide "View cart" notice link after AJAX add-to-cart success.
		$this->add_control(
			'hide_view_cart_notice',
			array(
				'label'        => esc_html__( 'Hide "View cart" Notice Link', 'rs-elementor-widgets' ),
				'description'  => esc_html__( 'Hides the "View cart" link that appears in WooCommerce notices after a successful AJAX add to cart triggered from this widget.', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Hide', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'Show', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => array(
					// Hide common WooCommerce "View cart" links that appear next to the button after AJAX add.
					'{{WRAPPER}} .rs-advanced-add-to-cart a.added_to_cart' => 'display: none !important;',
					'{{WRAPPER}} .rs-advanced-add-to-cart a.wc-forward' => 'display: none !important;',
				),
			)
		);

		$this->end_controls_section();

		// Style: Alignment.
		$this->start_controls_section(
			'section_style_alignment',
			array(
				'label' => esc_html__( 'Alignment', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_full_width',
			array(
				'label'        => esc_html__( 'Full Width', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'selectors'    => array(
					// Ensure parent containers span full width.
					'{{WRAPPER}} .rs-advanced-add-to-cart, {{WRAPPER}} .rs-advanced-add-to-cart .single_variation_wrap, {{WRAPPER}} .rs-advanced-add-to-cart form.cart, {{WRAPPER}} .rs-advanced-add-to-cart .woocommerce-variation-add-to-cart' => 'width: 100%;',
					// Make button truly full width inside flex rows.
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'display: block; width: 100%; flex: 0 0 100%; align-self: stretch;',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'rs-elementor-widgets' ),
				'type'                 => \Elementor\Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'              => 'left',
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					// Simple products container.
					'{{WRAPPER}} .rs-advanced-add-to-cart form.cart' => 'display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; width: 100%; justify-content: {{VALUE}};',
					// Variable products button row.
					'{{WRAPPER}} .rs-advanced-add-to-cart .woocommerce-variation-add-to-cart' => 'display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; width: 100%; justify-content: {{VALUE}};',
					// Loop context: align single anchor/button inside our wrapper.
					'{{WRAPPER}} .rs-advanced-add-to-cart' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
					// Variation wrap (contains price/stock and button row).
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_variation_wrap' => 'display: flex; flex-direction: column; align-items: {{VALUE}}; width: 100%;',
					// Variation details (price/stock area) alignment.
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_variation' => 'align-self: stretch; text-align: {{VALUE}}; width: 100%;',
					'{{WRAPPER}} .rs-advanced-add-to-cart .woocommerce-variation' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
					// Stock message alignment.
					'{{WRAPPER}} .rs-advanced-add-to-cart .stock' => 'display: flex; width: 100%; justify-content: {{VALUE}};',
				),
				'condition'            => array(
					'button_full_width!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Style: Button.
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Button', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_text_align',
			array(
				'label'     => esc_html__( 'Text Align', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal.
		$this->start_controls_tab(
			'tab_button_normal',
			array( 'label' => esc_html__( 'Normal', 'rs-elementor-widgets' ) )
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'tab_button_hover',
			array( 'label' => esc_html__( 'Hover', 'rs-elementor-widgets' ) )
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_hover',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:hover',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:hover	',
			)
		);

		$this->end_controls_tab();

		// Active.
		$this->start_controls_tab(
			'tab_button_active',
			array( 'label' => esc_html__( 'Active', 'rs-elementor-widgets' ) )
		);

		$this->add_control(
			'button_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_active',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:focus',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_active',
				'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus, {{WRAPPER}} .rs-advanced-add-to-cart a.add_to_cart_button:focus',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		// Ensure we have a product context.
		global $product;

		// Failsafe: ensure our JS is enqueued when this widget renders (Elementor should handle via get_script_depends, but this guards against edge cases).
		if ( function_exists( 'wp_enqueue_script' ) ) {
			wp_enqueue_script( 'rs-advanced-add-to-cart' );
		}

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			// Try to get from the main query (single product).
			if ( function_exists( 'wc_get_product' ) ) {
				$queried = get_queried_object_id();
				if ( $queried ) {
					$product = wc_get_product( $queried );
				}
			}
		}

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return; // No product context available.
		}

		// Visibility of Woo native variations is now handled by the 'hide_wc_variations' control via dynamic CSS.

		// Build wrapper classes. Only hide native variations on single product if requested,
		// so that loop items can show selectors for variable products.
		$settings           = $this->get_settings_for_display();
		$hide_wc_variations = isset( $settings['hide_wc_variations'] ) && 'yes' === $settings['hide_wc_variations'];
		$wrapper_classes    = 'rs-advanced-add-to-cart';
		$wrapper_classes   .= ( function_exists( 'is_product' ) && is_product() ) ? ' rs-context-single' : ' rs-context-loop';
		if ( function_exists( 'is_product' ) && is_product() && $hide_wc_variations ) {
			$wrapper_classes .= ' rs-hide-wc-variations';
		}
		if ( 'yes' === $settings['hide_stock_notices'] ) {
			$wrapper_classes .= ' rs-hide-stock-notices';
		}

		echo '<div class="' . esc_attr( $wrapper_classes ) . '">';

		// Use the appropriate WooCommerce template depending on context so 3rd-party AJAX carts (e.g., FunnelKit)
		// can hook into expected markup/classes and open their slide cart on success.
		if ( function_exists( 'is_product' ) && is_product() ) {
			// Single product context: keep native behavior.
			if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
				woocommerce_template_single_add_to_cart();
			}
		} elseif ( $product && is_a( $product, 'WC_Product' ) && $product->is_type( 'variable' ) && function_exists( 'woocommerce_variable_add_to_cart' ) ) {
			// Render full variations form so users can pick variation and add via AJAX inline.
			woocommerce_variable_add_to_cart();
		} elseif ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			// Simple/other types: use standard loop add-to-cart (already AJAX-enabled for simples).
			woocommerce_template_loop_add_to_cart();
		} elseif ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
			// Fallback.
			woocommerce_template_single_add_to_cart();
		}

		echo '</div>';

		// CSS-only approach handled via control selectors above.
	}
}

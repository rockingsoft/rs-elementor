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
        // Content controls
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'disable_quantity',
            [
                'label'        => esc_html__( 'Disable Quantity', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .quantity' => 'display: none !important;',
                ],
            ]
        );

        $this->add_control(
            'hide_stock_notices',
            [
                'label'        => esc_html__( 'Hide Stock Notices', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .stock' => 'display: none !important;',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Alignment
        $this->start_controls_section(
            'section_style_alignment',
            [
                'label' => esc_html__( 'Alignment', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_alignment',
            [
                'label'   => esc_html__( 'Alignment', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'rs-elementor-widgets' ),   'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'rs-elementor-widgets' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'rs-elementor-widgets' ),  'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_full_width',
            [
                'label'        => esc_html__( 'Full Width', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button' => 'display: block; width: 100%;',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Button
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'Button', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        // Normal
        $this->start_controls_tab(
            'tab_button_normal',
            [ 'label' => esc_html__( 'Normal', 'rs-elementor-widgets' ) ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'rs-elementor-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_button_hover',
            [ 'label' => esc_html__( 'Hover', 'rs-elementor-widgets' ) ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_hover',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:hover',
            ]
        );

        $this->end_controls_tab();

        // Active
        $this->start_controls_tab(
            'tab_button_active',
            [ 'label' => esc_html__( 'Active', 'rs-elementor-widgets' ) ]
        );

        $this->add_control(
            'button_text_color_active',
            [
                'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_active',
            [
                'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_active',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow_active',
                'selector' => '{{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:active, {{WRAPPER}} .rs-advanced-add-to-cart .single_add_to_cart_button:focus',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
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

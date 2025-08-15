<?php
/**
 * Add To Cart Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded yet to avoid fatals
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
    return;
}

class RS_Elementor_Widget_Add_To_Cart extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rs_add_to_cart';
    }

    public function get_title() {
        return esc_html__( 'Add to Cart', 'rs-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-cart';
    }

    public function get_categories() {
        return [ 'rs-woocommerce' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'product', 'cart', 'button', 'add to cart' ];
    }

    public function get_style_depends() {
        return [ 'rs-add-to-cart' ];
    }

    public function get_script_depends() {
        return [ 'rs-add-to-cart' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Button text
        $this->add_control(
            'button_text',
            [
                'label'   => esc_html__( 'Button Text', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Add to cart', 'rs-elementor-widgets' ),
            ]
        );

        // Messages
        $this->add_control(
            'msg_select_variation',
            [
                'label'   => esc_html__( 'Message: Select a variation', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Please select a variation first.', 'rs-elementor-widgets' ),
            ]
        );
        $this->add_control(
            'msg_added',
            [
                'label'   => esc_html__( 'Message: Added to cart', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Added to cart!', 'rs-elementor-widgets' ),
            ]
        );
        $this->add_control(
            'msg_error',
            [
                'label'   => esc_html__( 'Message: Error', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Something went wrong. Please try again.', 'rs-elementor-widgets' ),
            ]
        );

        // Woo messages toggles
        $this->add_control(
            'show_success_notice',
            [
                'label'        => esc_html__( 'Show Woo success notice', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Controls default WooCommerce "Added to cart" notice.', 'rs-elementor-widgets' ),
            ]
        );
        $this->add_control(
            'show_view_cart_link',
            [
                'label'        => esc_html__( 'Show "View cart" link', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'show_success_notice' => 'yes' ],
            ]
        );

        // Icon controls
        $this->add_control(
            'show_icon',
            [
                'label'        => esc_html__( 'Show Icon', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'   => esc_html__( 'Icon', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-shopping-cart',
                    'library' => 'fa-solid',
                ],
                'condition' => [ 'show_icon' => 'yes' ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label'   => esc_html__( 'Icon Position', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'  => [ 'title' => esc_html__( 'Left', 'rs-elementor-widgets' ), 'icon' => 'eicon-h-align-left' ],
                    'right' => [ 'title' => esc_html__( 'Right', 'rs-elementor-widgets' ), 'icon' => 'eicon-h-align-right' ],
                ],
                'default' => 'left',
                'toggle'  => false,
                'condition' => [ 'show_icon' => 'yes' ],
            ]
        );

        // Loading icon
        $this->add_control(
            'loading_icon',
            [
                'label'   => esc_html__( 'Loading Icon', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-cog fa-spin',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Behavior: always follows WooCommerce's AJAX setting (no overrides)

        $this->end_controls_section();

        // Style controls
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Button', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .rs-atc-btn',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .rs-atc-btn' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-atc-btn .rs-atc-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rs-atc-btn .rs-atc-loading-icon' => 'color: {{VALUE}};',
                ],
                'description' => esc_html__( 'Leave empty to inherit the button text color.', 'rs-elementor-widgets' ),
            ]
        );
        $this->add_control(
            'bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .rs-atc-btn' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .rs-atc-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .rs-atc-btn',
            ]
        );

        $this->add_control(
            'hover_heading',
            [ 'type' => \Elementor\Controls_Manager::HEADING, 'label' => esc_html__( 'Hover', 'rs-elementor-widgets' ) ]
        );
        $this->add_control(
            'hover_text_color',
            [
                'label'     => esc_html__( 'Text Color (Hover)', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .rs-atc-btn:hover:not([disabled])' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'hover_bg_color',
            [
                'label'     => esc_html__( 'Background (Hover)', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .rs-atc-btn:hover:not([disabled])' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'disabled_heading',
            [ 'type' => \Elementor\Controls_Manager::HEADING, 'label' => esc_html__( 'Disabled/Loading', 'rs-elementor-widgets' ), 'separator' => 'before' ]
        );
        $this->add_control(
            'disabled_opacity',
            [
                'label' => esc_html__( 'Opacity', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.1 ] ],
                'default' => [ 'size' => 0.6 ],
                'selectors' => [ '{{WRAPPER}} .rs-atc-btn[disabled]' => 'opacity: {{SIZE}};' ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_product_context() {
        global $product;
        $ctx = [ 'id' => 0, 'type' => 'simple' ];
        if ( $product instanceof \WC_Product ) {
            $ctx['id'] = $product->get_id();
            $ctx['type'] = $product->is_type( 'variable' ) ? 'variable' : 'simple';
            return $ctx;
        }
        // Outside product context, do not render
        return [ 'id' => 0, 'type' => 'simple' ];
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        $ctx = $this->get_product_context();
        if ( empty( $ctx['id'] ) ) { return; }

        // Follow WooCommerce setting for AJAX add to cart
        $mode = ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) ? 'ajax' : 'post';

        $btn_id = 'rs-atc-' . esc_attr( $this->get_id() );

        $icon_html = '';
        $loading_icon_html = '';
        if ( ! empty( $settings['show_icon'] ) && 'yes' === $settings['show_icon'] && ! empty( $settings['icon'] ) ) {
            ob_start();
            \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true', 'class' => 'rs-atc-icon' ], 'i' );
            $icon_html = (string) ob_get_clean();
        }
        if ( ! empty( $settings['loading_icon'] ) ) {
            ob_start();
            \Elementor\Icons_Manager::render_icon( $settings['loading_icon'], [ 'aria-hidden' => 'true', 'class' => 'rs-atc-loading-icon' ], 'i' );
            $loading_icon_html = (string) ob_get_clean();
        }
        if ( '' === $loading_icon_html ) {
            $loading_icon_html = '<i class="rs-atc-loading-icon fas fa-cog fa-spin" aria-hidden="true"></i>';
        }

        $icon_pos = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'left';

        $wrapper_classes = 'rs-add-to-cart-wrapper';
        $is_variable = ( 'variable' === $ctx['type'] );

        $product_url = get_permalink( $ctx['id'] );
        echo '<div class="' . esc_attr( $wrapper_classes ) . '" data-product-id="' . esc_attr( $ctx['id'] ) . '" data-product-type="' . esc_attr( $ctx['type'] ) . '" data-product-url="' . esc_url( $product_url ) . '">';
        echo '<button id="' . esc_attr( $btn_id ) . '" class="rs-atc-btn" type="button"'
            . ' data-mode="' . esc_attr( $mode ) . '"'
            . ' data-msg-select="' . esc_attr( $settings['msg_select_variation'] ) . '"'
            . ' data-msg-added="' . esc_attr( $settings['msg_added'] ) . '"'
            . ' data-msg-error="' . esc_attr( $settings['msg_error'] ) . '"'
            . ' data-show-success="' . ( ! empty( $settings['show_success_notice'] ) && 'yes' === $settings['show_success_notice'] ? '1' : '0' ) . '"'
            . ' data-show-view-cart="' . ( ! empty( $settings['show_view_cart_link'] ) && 'yes' === $settings['show_view_cart_link'] ? '1' : '0' ) . '">';

        // Content with icon
        $label = ! empty( $settings['button_text'] ) ? $settings['button_text'] : esc_html__( 'Add to cart', 'rs-elementor-widgets' );
        if ( $icon_html && 'left' === $icon_pos ) {
            echo $icon_html . '<span class="rs-atc-label">' . esc_html( $label ) . '</span>';
        } elseif ( $icon_html && 'right' === $icon_pos ) {
            echo '<span class="rs-atc-label">' . esc_html( $label ) . '</span>' . $icon_html;
        } else {
            echo '<span class="rs-atc-label">' . esc_html( $label ) . '</span>';
        }

        // Loading icon template (hidden initially)
        echo '<span class="rs-atc-loading" aria-hidden="true" style="display:none">' . $loading_icon_html . '</span>';

        echo '</button>';

        // Helper note for editor when variable
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $is_variable ) {
            echo '<div class="rs-atc-note" style="margin-top:6px;font-size:12px;opacity:.7;">' . esc_html__( 'This product has variations. Use the Variation Chooser widget to select one before adding to cart.', 'rs-elementor-widgets' ) . '</div>';
        }

        echo '</div>';
    }
}

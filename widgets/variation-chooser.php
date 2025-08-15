<?php
/**
 * Variation Chooser Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded yet to avoid fatals
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
    return;
}

class RS_Elementor_Widget_Variation_Chooser extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rs_variation_chooser';
    }

    public function get_title() {
        return esc_html__( 'Variation Chooser', 'rs-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-product-variations';
    }

    public function get_categories() {
        return [ 'rs-woocommerce' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'product', 'variation', 'attribute', 'dropdown', 'thumbnails' ];
    }

    public function get_style_depends() {
        return [ 'rs-variation-chooser' ];
    }

    public function get_script_depends() {
        return [ 'rs-variation-chooser' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'style_type',
            [
                'label'   => esc_html__( 'Style', 'rs-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'dropdown'   => esc_html__( 'Dropdown', 'rs-elementor-widgets' ),
                    'thumbnails' => esc_html__( 'Thumbnails', 'rs-elementor-widgets' ),
                ],
                'default' => 'dropdown',
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label'        => esc_html__( 'Show Label', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'label_text',
            [
                'label'     => esc_html__( 'Label Text', 'rs-elementor-widgets' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Choose an option', 'rs-elementor-widgets' ),
                'condition' => [ 'show_label' => 'yes' ],
            ]
        );

        $this->add_control(
            'include_product_name',
            [
                'label'        => esc_html__( 'Include Product Name in Variation Labels', 'rs-elementor-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'thumb_gap',
            [
                'label' => esc_html__( 'Thumbnail Gap', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-varc-thumbs' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        // Thumbnail appearance
        $this->add_control(
            'thumb_bg_color',
            [
                'label' => esc_html__( 'Thumb Background', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_control(
            'thumb_text_color',
            [
                'label' => esc_html__( 'Label Color', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb-name' => 'color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_control(
            'thumb_border_color',
            [
                'label' => esc_html__( 'Border Color', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_responsive_control(
            'thumb_border_width',
            [
                'label' => esc_html__( 'Border Width', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 8 ] ],
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_responsive_control(
            'thumb_padding',
            [
                'label' => esc_html__( 'Thumb Padding', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        // Hover state
        $this->add_control(
            'thumb_hover_bg_color',
            [
                'label' => esc_html__( 'Hover Background', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_control(
            'thumb_hover_border_color',
            [
                'label' => esc_html__( 'Hover Border', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        // Active state
        $this->add_control(
            'thumb_active_bg_color',
            [
                'label' => esc_html__( 'Active Background', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb.is-active' => 'background-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->add_control(
            'thumb_active_border_color',
            [
                'label' => esc_html__( 'Active Border', 'rs-elementor-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-variation-chooser .rs-varc-thumb.is-active' => 'border-color: {{VALUE}};',
                ],
                'condition' => [ 'style_type' => 'thumbnails' ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_product_context( $settings ) {
        global $product;

        // If we are on a single product with a product context
        if ( $product instanceof WC_Product ) {
            return $product;
        }

        // Otherwise attempt to load preview product
        $preview_id = ! empty( $settings['preview_product_id'] ) ? absint( $settings['preview_product_id'] ) : 0;
        if ( $preview_id ) {
            $prod = wc_get_product( $preview_id );
            if ( $prod ) {
                return $prod;
            }
        }

        return null;
    }

    private function get_variation_label( $variation_id, $include_product_name = false ) {
        $variation = wc_get_product( $variation_id );
        if ( $variation && $variation instanceof WC_Product_Variation ) {
            
            $variation_name = $variation->get_name();
            if ( !$include_product_name ) {
                $parts = explode( ' - ', $variation_name );
                if ( count( $parts ) > 1 ) {
                    $variation_name = $parts[1];
                }
            }
            return wp_strip_all_tags( $variation_name );
        }
        return '#' . absint( $variation_id );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $product  = $this->get_product_context( $settings );

        if ( ! $product || ! $product->is_type( 'variable' ) ) {
            // Do not render anything when product is not variable
            return;
        }

        /** @var WC_Product_Variable $product */
        $available = $product->get_available_variations();
        if ( empty( $available ) ) {
            // Do not render anything when there are no variations
            return;
        }

        $wrapper_id = 'rs-varc-' . esc_attr( $this->get_id() );
        $input_name = 'variation_id';
        $label      = ! empty( $settings['label_text'] ) ? $settings['label_text'] : '';
        $show_label = ( isset( $settings['show_label'] ) && 'yes' === $settings['show_label'] );
        $style_type = $settings['style_type'];
        $include_name = ( isset( $settings['include_product_name'] ) && 'yes' === $settings['include_product_name'] );
        // Default to syncing ON if control isn't present
        $sync_with_form = ( ! isset( $settings['sync_with_variations_form'] ) ) || ( 'yes' === $settings['sync_with_variations_form'] );

        // Build variations mapping for JS syncing
        $mapping = [];
        foreach ( $available as $var ) {
            $vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
            if ( ! $vid || empty( $var['attributes'] ) || ! is_array( $var['attributes'] ) ) { continue; }
            $mapping[ $vid ] = $var['attributes'];
        }
        $mapping_json = esc_attr( wp_json_encode( $mapping ) );

        echo '<div id="' . $wrapper_id . '" class="rs-variation-chooser" data-sync="' . ( $sync_with_form ? '1' : '0' ) . '" data-variations="' . $mapping_json . '">';

        if ( $show_label && $label ) {
            echo '<label class="rs-varc-label" for="' . $wrapper_id . '-select">' . esc_html( $label ) . '</label>';
        }

        // Hidden input to store the selected value (so other scripts can read it)
        echo '<input type="hidden" name="' . esc_attr( $input_name ) . '" class="rs-varc-input" value="" />';

        if ( 'dropdown' === $style_type ) {
            echo '<select class="rs-varc-select" id="' . $wrapper_id . '-select">';
            // Placeholder to allow "no selection"
            echo '<option value="">' . esc_html__( '— Select an option —', 'rs-elementor-widgets' ) . '</option>';
            foreach ( $available as $var ) {
                $vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
                if ( ! $vid ) { continue; }
                $name = $this->get_variation_label( $vid, $include_name );
                echo '<option value="' . esc_attr( $vid ) . '">' . esc_html( $name ) . '</option>';
            }
            echo '</select>';
        } else {
            echo '<div class="rs-varc-thumbs" role="list">';
            foreach ( $available as $var ) {
                $vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
                if ( ! $vid ) { continue; }
                $name = $this->get_variation_label( $vid, $include_name );
                $img  = '';
                if ( ! empty( $var['image']['url'] ) ) {
                    $img = esc_url( $var['image']['url'] );
                } else {
                    $img_id = $product->get_image_id();
                    if ( $img_id ) {
                        $img = esc_url( wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) );
                    }
                }
                echo '<button type="button" class="rs-varc-thumb" role="listitem" data-value="' . esc_attr( $vid ) . '" aria-pressed="false">';
                if ( $img ) {
                    echo '<img src="' . $img . '" alt="' . esc_attr( $name ) . '" />';
                } else {
                    echo '<span class="rs-varc-thumb-fallback">' . esc_html( $name ) . '</span>';
                }
                echo '<span class="rs-varc-thumb-name">' . esc_html( $name ) . '</span>';
                echo '</button>';
            }
            echo '</div>';
        }

        echo '</div>';
    }
}

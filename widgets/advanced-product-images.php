<?php
/**
 * Advanced Product Images Widget.
 *
 * @package RS_Elementor_Widgets
 *
 * phpcs:disable WordPress.Files.FileName
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded.
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

/**
 * Advanced Product Images widget.
 */
class RS_Elementor_Widget_Advanced_Product_Images extends \Elementor\Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rs_advanced_product_images';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Advanced Product Images', 'rs-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
		return array( 'woocommerce', 'product', 'gallery', 'images', 'thumbnails', 'lightbox' );
	}

	/**
	 * Styles this widget depends on.
	 */
	public function get_style_depends() {
		return array( 'rs-advanced-product-images' );
	}

	/**
	 * Scripts this widget depends on.
	 */
	public function get_script_depends() {
		return array( 'rs-advanced-product-images' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'thumbs_position',
			array(
				'label'          => esc_html__( 'Thumbnails Position', 'rs-elementor-widgets' ),
				'type'           => \Elementor\Controls_Manager::CHOOSE,
				'options'        => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'    => array(
						'title' => esc_html__( 'Top', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-h-align-right',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'rs-elementor-widgets' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'        => 'left',
				'tablet_default' => 'bottom',
				'mobile_default' => 'bottom',
				'toggle'         => false,
			)
		);

		$this->add_control(
			'thumb_size',
			array(
				'label'   => esc_html__( 'Thumbnail Size (px)', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 40,
				'max'     => 200,
				'step'    => 2,
				'default' => 80,
			)
		);

		$this->add_control(
			'thumb_gap',
			array(
				'label'   => esc_html__( 'Thumbnail Gap (px)', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 40,
				'step'    => 1,
				'default' => 8,
			)
		);

		$this->add_control(
			'thumbs_nowrap',
			array(
				'label'        => esc_html__( 'Single Line Thumbnails', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'If enabled, thumbnails will be displayed in a single, scrollable line instead of wrapping.', 'rs-elementor-widgets' ),
				'prefix_class' => 'rs-thumbs-nowrap-',
			)
		);

		$this->add_control(
			'hide_thumbnails',
			array(
				'label'        => esc_html__( 'Hide Thumbnails', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Hide the thumbnails strip. Main image remains and stays in sync with variations.', 'rs-elementor-widgets' ),
			)
		);

		$this->add_control(
			'include_variation_images',
			array(
				'label'        => esc_html__( 'Include Variation Images', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'If enabled, adds each variation image to the gallery and syncs selection to show that image.', 'rs-elementor-widgets' ),
			)
		);

		$this->add_control(
			'inline_navigation',
			array(
				'label'        => esc_html__( 'Inline Navigation (Prev/Next)', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Show small Previous/Next arrows alongside the main image (outside the image).', 'rs-elementor-widgets' ),
			)
		);

		$this->end_controls_section();

		// Styles: Modal buttons (colors via CSS variables) with tabs.
		$this->start_controls_section(
			'section_style_modal',
			array(
				'label' => esc_html__( 'Modal', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Editor preview helpers.
		$this->add_control(
			'modal_preview_heading',
			array(
				'label'     => esc_html__( 'Editor Preview', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'modal_preview_open',
			array(
				'label'        => esc_html__( 'Force open modal in editor', 'rs-elementor-widgets' ),
				'description'  => esc_html__( 'Editor-only: opens the modal in the preview so you can style it.', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'modal_buttons_heading',
			array(
				'label'     => esc_html__( 'Modal Buttons', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Modal navigation icons (use Elementor icon chooser).
		$this->add_control(
			'modal_prev_icon',
			array(
				'label'   => esc_html__( 'Previous Icon', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'modal_next_icon',
			array(
				'label'   => esc_html__( 'Next Icon', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'modal_close_icon',
			array(
				'label'   => esc_html__( 'Close Icon', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_modal_buttons' );

		// Normal state.
		$this->start_controls_tab(
			'tab_modal_buttons_normal',
			array( 'label' => esc_html__( 'Normal', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'modal_btn_bg',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-bg: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'modal_btn_color',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Hover state.
		$this->start_controls_tab(
			'tab_modal_buttons_hover',
			array( 'label' => esc_html__( 'Hover', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'modal_btn_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-bg-hover: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'modal_btn_color_hover',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-color-hover: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Active state.
		$this->start_controls_tab(
			'tab_modal_buttons_active',
			array( 'label' => esc_html__( 'Active', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'modal_btn_bg_active',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-bg-active: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'modal_btn_color_active',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-modal-btn-color-active: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Behavior: Click action for main image.
		$this->start_controls_section(
			'section_behaviour',
			array(
				'label' => esc_html__( 'Behavior', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'click_action',
			array(
				'label'   => esc_html__( 'On Click', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'modal',
				'options' => array(
					'modal' => esc_html__( 'Open Modal (default)', 'rs-elementor-widgets' ),
					'link'  => esc_html__( 'Go to URL', 'rs-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'click_link',
			array(
				'label'       => esc_html__( 'Click URL', 'rs-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array( 'active' => true ),
				'condition'   => array( 'click_action' => 'link' ),
			)
		);

		$this->end_controls_section();

		// Styles: Inline navigation.
		$this->start_controls_section(
			'section_style_inline_nav',
			array(
				'label' => esc_html__( 'Inline Navigation', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'inline_prev_icon',
			array(
				'label'   => esc_html__( 'Previous Icon', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'inline_next_icon',
			array(
				'label'   => esc_html__( 'Next Icon', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'inline_buttons_heading',
			array(
				'label'     => esc_html__( 'Inline Navigation Buttons', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_inline_buttons' );

		// Normal state.
		$this->start_controls_tab(
			'tab_inline_buttons_normal',
			array( 'label' => esc_html__( 'Normal', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'inline_nav_bg',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-bg: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'inline_nav_color',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Hover state.
		$this->start_controls_tab(
			'tab_inline_buttons_hover',
			array( 'label' => esc_html__( 'Hover', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'inline_nav_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-bg-hover: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'inline_nav_color_hover',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-color-hover: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Disabled state.
		$this->start_controls_tab(
			'tab_inline_buttons_disabled',
			array( 'label' => esc_html__( 'Disabled', 'rs-elementor-widgets' ) )
		);
		$this->add_control(
			'inline_nav_bg_disabled',
			array(
				'label'     => esc_html__( 'Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-bg-disabled: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'inline_nav_color_disabled',
			array(
				'label'     => esc_html__( 'Text/Icon', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--rs-inline-btn-color-disabled: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Styles: Main Image.
		$this->start_controls_section(
			'section_style_main',
			array(
				'label' => esc_html__( 'Main Image', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'main_height',
			array(
				'label'      => esc_html__( 'Fixed Height', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--rs-main-image-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'main_fit',
			array(
				'label'     => esc_html__( 'When image is larger', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'contain',
				'options'   => array(
					'contain'    => esc_html__( 'Scale to fit (contain)', 'rs-elementor-widgets' ),
					'cover'      => esc_html__( 'Crop to fill (cover)', 'rs-elementor-widgets' ),
					'scale-down' => esc_html__( 'Only scale down', 'rs-elementor-widgets' ),
					'fill'       => esc_html__( 'Stretch to fill', 'rs-elementor-widgets' ),
					'none'       => esc_html__( 'No scaling', 'rs-elementor-widgets' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-main-img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'main_object_position',
			array(
				'label'     => esc_html__( 'Focal Position', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'center center',
				'options'   => array(
					'center center' => esc_html__( 'Center Center', 'rs-elementor-widgets' ),
					'top left'      => esc_html__( 'Top Left', 'rs-elementor-widgets' ),
					'top center'    => esc_html__( 'Top Center', 'rs-elementor-widgets' ),
					'top right'     => esc_html__( 'Top Right', 'rs-elementor-widgets' ),
					'center left'   => esc_html__( 'Center Left', 'rs-elementor-widgets' ),
					'center right'  => esc_html__( 'Center Right', 'rs-elementor-widgets' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'rs-elementor-widgets' ),
					'bottom center' => esc_html__( 'Bottom Center', 'rs-elementor-widgets' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'rs-elementor-widgets' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-main-img' => 'object-position: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Styles: Thumbnails.
		$this->start_controls_section(
			'section_style_thumbs',
			array(
				'label' => esc_html__( 'Thumbnails', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'thumb_border',
				'label'    => esc_html__( 'Border', 'rs-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .rs-adv-thumb',
			)
		);

		$this->add_control(
			'thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-adv-thumb'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rs-adv-modal-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumb_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color (Active)', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#0073aa',
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-thumb.is-active' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'thumb_border_border!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__( 'WooCommerce is required for this widget.', 'rs-elementor-widgets' ) . '</div>';
			return;
		}

		$settings                 = $this->get_settings_for_display();
		$thumb_size               = isset( $settings['thumb_size'] ) ? (int) $settings['thumb_size'] : 80;
		$thumb_gap                = isset( $settings['thumb_gap'] ) ? (int) $settings['thumb_gap'] : 8;
		$include_variation_images = ( ! isset( $settings['include_variation_images'] ) ) || ( 'yes' === $settings['include_variation_images'] );
		$hide_thumbnails          = isset( $settings['hide_thumbnails'] ) && 'yes' === $settings['hide_thumbnails'];

		// Resolve product in both frontend and Elementor editor preview.
		global $product;
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			$maybe_product = wc_get_product( get_the_ID() );
			if ( $maybe_product && is_a( $maybe_product, 'WC_Product' ) ) {
				$product = $maybe_product;
			}
		}
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No product found for preview. Please set a Product in the Preview Settings.', 'rs-elementor-widgets' ) . '</div>';
			return;
		}

		$main_id     = $product->get_image_id();
		$gallery_ids = $product->get_gallery_image_ids();

		// Build images array: put main image first if exists.
		$image_ids = array();
		if ( $main_id ) {
			$image_ids[] = $main_id;
		}
		if ( ! empty( $gallery_ids ) ) {
			foreach ( $gallery_ids as $gid ) {
				// Avoid duplicates.
				if ( $gid && $gid !== $main_id ) {
					$image_ids[] = $gid;
				}
			}
		}

		// Include variation images if enabled.
		$variation_index_map = array();
		if ( $include_variation_images && $product && $product->is_type( 'variable' ) ) {
			/**
			 * Product variable instance.
			 *
			 * @var WC_Product_Variable $product
			 */
			$avail = $product->get_available_variations();
			if ( ! empty( $avail ) && is_array( $avail ) ) {
				foreach ( $avail as $var ) {
					$vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
					if ( ! $vid ) {
						continue;
					}
					$img_id = 0;
					if ( ! empty( $var['image_id'] ) ) {
						$img_id = (int) $var['image_id'];
					} elseif ( ! empty( $var['image']['id'] ) ) {
						$img_id = (int) $var['image']['id'];
					}
					if ( $img_id ) {
						// Ensure image id is present in gallery list.
						if ( ! in_array( $img_id, $image_ids, true ) ) {
							$image_ids[] = $img_id;
						}
					}
				}
				// After finalizing image_ids, compute index map (variation_id -> index in $image_ids).
				foreach ( $avail as $var ) {
					$vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
					if ( ! $vid ) {
						continue;
					}
					$img_id = 0;
					if ( ! empty( $var['image_id'] ) ) {
						$img_id = (int) $var['image_id'];
					} elseif ( ! empty( $var['image']['id'] ) ) {
						$img_id = (int) $var['image']['id'];
					}
					if ( $img_id ) {
						$idx = array_search( $img_id, $image_ids, true );
						if ( false !== $idx ) {
							$variation_index_map[ (string) $vid ] = (int) $idx;
						}
					}
				}
			}
		}

		if ( empty( $image_ids ) ) {
			echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No images for this product.', 'rs-elementor-widgets' ) . '</div>';
			return;
		}

		// Prepare URLs.
		$images = array();
		foreach ( $image_ids as $aid ) {
			$full     = wp_get_attachment_image_src( $aid, 'full' );
			$large    = wp_get_attachment_image_src( $aid, 'large' );
			$thumb    = wp_get_attachment_image_src( $aid, 'woocommerce_gallery_thumbnail' );
			$images[] = array(
				'id'    => $aid,
				'full'  => $full ? $full[0] : '',
				'large' => $large ? $large[0] : '',
				'thumb' => $thumb ? $thumb[0] : '',
				'alt'   => esc_attr( get_post_meta( $aid, '_wp_attachment_image_alt', true ) ),
			);
		}

		$widget_id         = 'rs-adv-images-' . $this->get_id();
		$container_classes = array( 'rs-adv-images' );
		if ( $hide_thumbnails ) {
			$container_classes[] = 'hide-thumbs';
		}

		// Responsive classes for thumbnail position.
		$positions = array(
			'desktop' => ! empty( $settings['thumbs_position'] ) ? $settings['thumbs_position'] : 'left',
			'tablet'  => ! empty( $settings['thumbs_position_tablet'] ) ? $settings['thumbs_position_tablet'] : '',
			'mobile'  => ! empty( $settings['thumbs_position_mobile'] ) ? $settings['thumbs_position_mobile'] : '',
		);

		$container_classes[] = 'layout-desktop-' . $positions['desktop'];
		if ( $positions['tablet'] ) {
			$container_classes[] = 'layout-tablet-' . $positions['tablet'];
		}
		if ( $positions['mobile'] ) {
			$container_classes[] = 'layout-mobile-' . $positions['mobile'];
		}

		$container_classes = implode( ' ', $container_classes );
		$var_map_json      = wp_json_encode( $variation_index_map );
		$click_action      = isset( $settings['click_action'] ) ? $settings['click_action'] : 'modal';
		$click_url         = '';
		$click_new_tab     = '0';
		$click_nofollow    = '0';
		if ( isset( $settings['click_link'] ) && is_array( $settings['click_link'] ) ) {
			$click_url      = ! empty( $settings['click_link']['url'] ) ? $settings['click_link']['url'] : '';
			$click_new_tab  = ( ! empty( $settings['click_link']['is_external'] ) ) ? '1' : '0';
			$click_nofollow = ( ! empty( $settings['click_link']['nofollow'] ) ) ? '1' : '0';
		}
		// Backward compatibility with previous fields if present.
		if ( empty( $click_url ) && ! empty( $settings['click_url'] ) ) {
			$click_url = trim( $settings['click_url'] );
		}
		if ( '0' === $click_new_tab && isset( $settings['click_new_tab'] ) && 'yes' === $settings['click_new_tab'] ) {
			$click_new_tab = '1';
		}
		// Build rel attribute value safely.
		$rel_parts = array();
		if ( '1' === $click_new_tab ) {
			$rel_parts[] = 'noopener';
			$rel_parts[] = 'noreferrer';
		}
		if ( '1' === $click_nofollow ) {
			$rel_parts[] = 'nofollow';
		}
		$rel_attr = implode( ' ', array_unique( $rel_parts ) );
		?>
		<div id="<?php echo esc_attr( $widget_id ); ?>" class="<?php echo esc_attr( $container_classes ); ?>" data-variation-map="<?php echo esc_attr( $var_map_json ); ?>" data-click-action="<?php echo esc_attr( $click_action ); ?>" data-click-url="<?php echo esc_url( $click_url ); ?>" data-click-new-tab="<?php echo esc_attr( $click_new_tab ); ?>">
			<div class="rs-adv-images-inner">
				<div class="rs-adv-thumbs-wrap"<?php echo $hide_thumbnails ? ' aria-hidden="true"' : ''; ?>>
					<button type="button" class="rs-adv-thumbs-nav rs-adv-thumbs-prev" aria-label="<?php echo esc_attr__( 'Scroll thumbnails previous', 'rs-elementor-widgets' ); ?>" tabindex="0">
						<i class="fas fa-chevron-left icon-h" aria-hidden="true"></i>
						<i class="fas fa-chevron-up icon-v" aria-hidden="true"></i>
					</button>
					<div class="rs-adv-thumbs" style="--thumb-size: <?php echo (int) $thumb_size; ?>px; --thumb-gap: <?php echo (int) $thumb_gap; ?>px;">
						<?php foreach ( $images as $index => $img ) : ?>
							<button type="button" class="rs-adv-thumb<?php echo ( 0 === (int) $index ) ? ' is-active' : ''; ?>" data-index="<?php echo (int) $index; ?>" data-full="<?php echo esc_url( $img['full'] ); ?>" data-large="<?php echo esc_url( $img['large'] ); ?>">
								<?php
								$thumb_src = $img['thumb'] ? $img['thumb'] : ( $img['large'] ? $img['large'] : $img['full'] );
								?>
								<img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>"/>
							</button>
						<?php endforeach; ?>
					</div>
					<button type="button" class="rs-adv-thumbs-nav rs-adv-thumbs-next" aria-label="<?php echo esc_attr__( 'Scroll thumbnails next', 'rs-elementor-widgets' ); ?>" tabindex="0">
						<i class="fas fa-chevron-right icon-h" aria-hidden="true"></i>
						<i class="fas fa-chevron-down icon-v" aria-hidden="true"></i>
					</button>
				</div>
				<?php if ( ! empty( $settings['inline_navigation'] ) && 'yes' === $settings['inline_navigation'] ) : ?>
					<div class="rs-adv-main-wrap">
						<button type="button" class="rs-adv-inline-btn rs-adv-inline-prev" aria-label="<?php echo esc_attr__( 'Previous image', 'rs-elementor-widgets' ); ?>" aria-disabled="true">
							<?php
							if ( ! empty( $settings['inline_prev_icon'] ) && ! empty( $settings['inline_prev_icon']['value'] ) ) {
								\Elementor\Icons_Manager::render_icon( $settings['inline_prev_icon'], array( 'aria-hidden' => 'true' ) );
							} else {
								$prev_class = $settings['inline_prev_icon_class'] ?? 'fas fa-chevron-left';
								echo '<i class="' . esc_attr( $prev_class ) . '" aria-hidden="true"></i>';
							}
							?>
						</button>
						<div class="rs-adv-main" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Open image in fullscreen', 'rs-elementor-widgets' ); ?>">
							<?php
							$main_src = $images[0]['large'] ? $images[0]['large'] : $images[0]['full'];
							?>
							<?php if ( 'link' === $click_action && ! empty( $click_url ) ) : ?>
								<a class="rs-adv-main-link" href="<?php echo esc_url( $click_url ); ?>"<?php echo ( '1' === $click_new_tab ) ? ' target="_blank"' : ''; ?><?php echo ( '' !== $rel_attr ) ? ' rel="' . esc_attr( $rel_attr ) . '"' : ''; ?>>
									<img class="rs-adv-main-img" src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $images[0]['alt'] ); ?>"/>
								</a>
							<?php else : ?>
								<img class="rs-adv-main-img" src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $images[0]['alt'] ); ?>"/>
							<?php endif; ?>
						</div>
						<button type="button" class="rs-adv-inline-btn rs-adv-inline-next" aria-label="<?php echo esc_attr__( 'Next image', 'rs-elementor-widgets' ); ?>" aria-disabled="<?php echo count( $images ) > 1 ? 'false' : 'true'; ?>">
							<?php
							if ( ! empty( $settings['inline_next_icon'] ) && ! empty( $settings['inline_next_icon']['value'] ) ) {
								\Elementor\Icons_Manager::render_icon( $settings['inline_next_icon'], array( 'aria-hidden' => 'true' ) );
							} else {
								$next_class = $settings['inline_next_icon_class'] ?? 'fas fa-chevron-right';
								echo '<i class="' . esc_attr( $next_class ) . '" aria-hidden="true"></i>';
							}
							?>
						</button>
					</div>
				<?php else : ?>
					<div class="rs-adv-main" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Open image in fullscreen', 'rs-elementor-widgets' ); ?>">
						<?php
						$main_src = $images[0]['large'] ? $images[0]['large'] : $images[0]['full'];
						?>
						<?php if ( 'link' === $click_action && ! empty( $click_url ) ) : ?>
							<a class="rs-adv-main-link" href="<?php echo esc_url( $click_url ); ?>"<?php echo ( '1' === $click_new_tab ) ? ' target="_blank"' : ''; ?><?php echo ( '' !== $rel_attr ) ? ' rel="' . esc_attr( $rel_attr ) . '"' : ''; ?>>
								<img class="rs-adv-main-img" src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $images[0]['alt'] ); ?>"/>
							</a>
						<?php else : ?>
							<img class="rs-adv-main-img" src="<?php echo esc_url( $main_src ); ?>" alt="<?php echo esc_attr( $images[0]['alt'] ); ?>"/>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php
			$in_editor = false;
			if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance ) && \Elementor\Plugin::$instance ) {
				$editor = \Elementor\Plugin::$instance->editor ?? null;
				if ( $editor && method_exists( $editor, 'is_edit_mode' ) ) {
					$in_editor = (bool) $editor->is_edit_mode();
				}
			}
			$force_open = $in_editor && ! empty( $settings['modal_preview_open'] ) && 'yes' === $settings['modal_preview_open'];
			?>
		<div class="rs-adv-modal<?php echo $force_open ? ' is-preview-open' : ''; ?>" aria-hidden="<?php echo $force_open ? 'false' : 'true'; ?>">
				<div class="rs-adv-modal-backdrop"></div>
				<div class="rs-adv-modal-content" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Product image viewer', 'rs-elementor-widgets' ); ?>">
					<button type="button" class="rs-adv-modal-close" aria-label="<?php echo esc_attr__( 'Close', 'rs-elementor-widgets' ); ?>">
						<?php
						if ( ! empty( $settings['modal_close_icon'] ) && ! empty( $settings['modal_close_icon']['value'] ) ) {
							\Elementor\Icons_Manager::render_icon( $settings['modal_close_icon'], array( 'aria-hidden' => 'true' ) );
						} else {
							// Fallback to a default Font Awesome icon or a typographic ×.
							echo '<i class="fas fa-times" aria-hidden="true"></i>';
						}
						?>
					</button>
					<button type="button" class="rs-adv-nav rs-adv-prev" aria-label="<?php echo esc_attr__( 'Previous image', 'rs-elementor-widgets' ); ?>">
						<?php
						if ( ! empty( $settings['modal_prev_icon'] ) && ! empty( $settings['modal_prev_icon']['value'] ) ) {
							\Elementor\Icons_Manager::render_icon( $settings['modal_prev_icon'], array( 'aria-hidden' => 'true' ) );
						} else {
							echo '<i class="fas fa-chevron-left" aria-hidden="true"></i>';
						}
						?>
					</button>
					<img class="rs-adv-modal-img" src="<?php echo esc_url( $images[0]['full'] ); ?>" alt=""/>
					<button type="button" class="rs-adv-nav rs-adv-next" aria-label="<?php echo esc_attr__( 'Next image', 'rs-elementor-widgets' ); ?>">
						<?php
						if ( ! empty( $settings['modal_next_icon'] ) && ! empty( $settings['modal_next_icon']['value'] ) ) {
							\Elementor\Icons_Manager::render_icon( $settings['modal_next_icon'], array( 'aria-hidden' => 'true' ) );
						} else {
							echo '<i class="fas fa-chevron-right" aria-hidden="true"></i>';
						}
						?>
					</button>
				</div>
			</div>
		</div>
		<?php
	}
}

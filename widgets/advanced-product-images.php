<?php
/**
 * Advanced Product Images Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

class RS_Elementor_Widget_Advanced_Product_Images extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rs_advanced_product_images';
	}

	public function get_title() {
		return esc_html__( 'Advanced Product Images', 'rs-elementor-widgets' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'rs-woocommerce' );
	}

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

	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'thumbs_position',
			array(
				'label'   => esc_html__( 'Thumbnails Position', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'   => esc_html__( 'Left', 'rs-elementor-widgets' ),
					'right'  => esc_html__( 'Right', 'rs-elementor-widgets' ),
					'top'    => esc_html__( 'Top', 'rs-elementor-widgets' ),
					'bottom' => esc_html__( 'Bottom', 'rs-elementor-widgets' ),
				),
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

		$this->end_controls_section();

		// Styles: Main Image
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
					'{{WRAPPER}} .rs-adv-main' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rs-adv-main .rs-adv-main-img' => 'height: 100%;',
				),
			)
		);

		$this->add_responsive_control(
			'main_max_height',
			array(
				'label'      => esc_html__( 'Max Height', 'rs-elementor-widgets' ),
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
					'{{WRAPPER}} .rs-adv-main' => '--rs-main-max: {{SIZE}}{{UNIT}};',
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

		// Styles: Thumbnails
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

	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__( 'WooCommerce is required for this widget.', 'rs-elementor-widgets' ) . '</div>';
			return;
		}

		$settings                 = $this->get_settings_for_display();
		$thumbs_position          = $settings['thumbs_position'];
		$thumb_size               = isset( $settings['thumb_size'] ) ? (int) $settings['thumb_size'] : 80;
		$thumb_gap                = isset( $settings['thumb_gap'] ) ? (int) $settings['thumb_gap'] : 8;
		$include_variation_images = ( ! isset( $settings['include_variation_images'] ) ) || ( $settings['include_variation_images'] === 'yes' );

		// Resolve product in both frontend and Elementor editor preview
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

		// Build images array: put main image first if exists
		$image_ids = array();
		if ( $main_id ) {
			$image_ids[] = $main_id;
		}
		if ( ! empty( $gallery_ids ) ) {
			foreach ( $gallery_ids as $gid ) {
				// Avoid duplicates
				if ( $gid && $gid !== $main_id ) {
					$image_ids[] = $gid;
				}
			}
		}

		// Include variation images if enabled
		$variation_index_map = array();
		if ( $include_variation_images && $product && $product->is_type( 'variable' ) ) {
			/** @var WC_Product_Variable $product */
			$avail = $product->get_available_variations();
			if ( ! empty( $avail ) && is_array( $avail ) ) {
				foreach ( $avail as $var ) {
					$vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
					if ( ! $vid ) {
						continue; }
					$img_id = 0;
					if ( ! empty( $var['image_id'] ) ) {
						$img_id = (int) $var['image_id'];
					} elseif ( ! empty( $var['image']['id'] ) ) {
						$img_id = (int) $var['image']['id'];
					}
					if ( $img_id ) {
						// Ensure image id is present in gallery list
						if ( ! in_array( $img_id, $image_ids, true ) ) {
							$image_ids[] = $img_id;
						}
					}
				}
				// After finalizing image_ids, compute index map (variation_id -> index in $image_ids)
				foreach ( $avail as $var ) {
					$vid = isset( $var['variation_id'] ) ? (int) $var['variation_id'] : 0;
					if ( ! $vid ) {
						continue; }
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

		// Prepare URLs
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
		$allowed_positions = array( 'left', 'right', 'top', 'bottom' );
		$pos               = in_array( $thumbs_position, $allowed_positions, true ) ? $thumbs_position : 'left';
		$container_classes = 'rs-adv-images layout-' . $pos;
		$var_map_json      = esc_attr( wp_json_encode( $variation_index_map ) );
		?>
		<div id="<?php echo esc_attr( $widget_id ); ?>" class="<?php echo esc_attr( $container_classes ); ?>" data-variation-map="<?php echo $var_map_json; ?>">
			<div class="rs-adv-images-inner">
				<div class="rs-adv-thumbs" style="--thumb-size: <?php echo (int) $thumb_size; ?>px; --thumb-gap: <?php echo (int) $thumb_gap; ?>px;">
					<?php foreach ( $images as $index => $img ) : ?>
						<button type="button" class="rs-adv-thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $index; ?>" data-full="<?php echo esc_url( $img['full'] ); ?>" data-large="<?php echo esc_url( $img['large'] ); ?>">
							<img src="<?php echo esc_url( $img['thumb'] ?: $img['large'] ?: $img['full'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>"/>
						</button>
					<?php endforeach; ?>
				</div>
				<div class="rs-adv-main" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Open image in fullscreen', 'rs-elementor-widgets' ); ?>">
					<img class="rs-adv-main-img" src="<?php echo esc_url( $images[0]['large'] ?: $images[0]['full'] ); ?>" alt="<?php echo esc_attr( $images[0]['alt'] ); ?>"/>
				</div>
			</div>

			<div class="rs-adv-modal" aria-hidden="true">
				<div class="rs-adv-modal-backdrop"></div>
				<div class="rs-adv-modal-content" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Product image viewer', 'rs-elementor-widgets' ); ?>">
					<button type="button" class="rs-adv-modal-close" aria-label="<?php echo esc_attr__( 'Close', 'rs-elementor-widgets' ); ?>">&times;</button>
					<button type="button" class="rs-adv-nav rs-adv-prev" aria-label="<?php echo esc_attr__( 'Previous image', 'rs-elementor-widgets' ); ?>">
						<i class="fas fa-chevron-left" aria-hidden="true"></i>
					</button>
					<img class="rs-adv-modal-img" src="<?php echo esc_url( $images[0]['full'] ); ?>" alt=""/>
					<button type="button" class="rs-adv-nav rs-adv-next" aria-label="<?php echo esc_attr__( 'Next image', 'rs-elementor-widgets' ); ?>">
						<i class="fas fa-chevron-right" aria-hidden="true"></i>
					</button>
				</div>
			</div>
		</div>
		<?php
	}
}

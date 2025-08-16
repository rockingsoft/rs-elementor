<?php
/**
 * Product Reviews Widget.
 *
 * @package RS_Elementor_Widgets
 *
 * phpcs:disable WordPress.Files.FileName
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail out early if Elementor isn't loaded yet to avoid fatal errors.
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

/**
 * Elementor widget to display WooCommerce product reviews.
 *
 * Provides configurable display of initial reviews and a modal with all reviews.
 *
 * @package RS_Elementor_Widgets
 * @since 1.0.0
 * @extends \Elementor\Widget_Base
 */
class RS_Elementor_Widget_Product_Reviews extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'rs_product_reviews';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Reviews', 'rs-elementor-widgets' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-review';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'rs-woocommerce' );
	}

	/**
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'product', 'reviews', 'comments', 'ratings' );
	}

	/**
	 * Styles this widget depends on.
	 */
	public function get_style_depends() {
		return array( 'rs-product-reviews' );
	}

	/**
	 * Scripts this widget depends on.
	 */
	public function get_script_depends() {
		return array( 'rs-product-reviews' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// Content section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'Hide', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'rs-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Product Reviews', 'rs-elementor-widgets' ),
				'placeholder' => esc_html__( 'Enter your title', 'rs-elementor-widgets' ),
				'condition'   => array(
					'show_title' => 'yes',
				),
			)
		);

		// Note: Product selection has been removed as the widget now always uses the current product.

		$this->add_control(
			'initial_reviews',
			array(
				'label'       => esc_html__( 'Initial Reviews to Show', 'rs-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 10,
				'step'        => 1,
				'default'     => 3,
				'description' => esc_html__( 'Number of reviews to show initially before clicking "Show All"', 'rs-elementor-widgets' ),
			)
		);

		$this->add_control(
			'show_all_button_text',
			array(
				'label'   => esc_html__( 'Show All Button Text', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Show All Reviews', 'rs-elementor-widgets' ),
			)
		);

		$this->add_control(
			'modal_title',
			array(
				'label'   => esc_html__( 'Modal Title', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'All Product Reviews', 'rs-elementor-widgets' ),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Initial Order By', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'   => esc_html__( 'Date', 'rs-elementor-widgets' ),
					'rating' => esc_html__( 'Rating', 'rs-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Initial Order', 'rs-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'rs-elementor-widgets' ),
					'desc' => esc_html__( 'DESC', 'rs-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Show Rating', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => esc_html__( 'Show Date', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'        => esc_html__( 'Show Avatar', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_verified',
			array(
				'label'        => esc_html__( 'Show Verified Badge', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Modal style section.
		$this->start_controls_section(
			'section_modal_style',
			array(
				'label' => esc_html__( 'Modal', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'modal_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.rs-reviews-modal-content' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'modal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.rs-reviews-modal-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'modal_close_color',
			array(
				'label'     => esc_html__( 'Close Button Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.rs-reviews-modal-close' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'modal_overlay_color',
			array(
				'label'     => esc_html__( 'Overlay Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.8)',
				'selectors' => array(
					'.rs-reviews-modal' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'show_all_button_color',
			array(
				'label'     => esc_html__( 'Show All Button Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-show-all-reviews' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'show_all_button_bg_color',
			array(
				'label'     => esc_html__( 'Show All Button Background', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-show-all-reviews' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// Style section - Title.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-reviews-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .rs-reviews-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-reviews-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style section - Reviews.
		$this->start_controls_section(
			'section_reviews_style',
			array(
				'label' => esc_html__( 'Reviews', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'review_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-item' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_author_color',
			array(
				'label'     => esc_html__( 'Author Name Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-author' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_date_color',
			array(
				'label'     => esc_html__( 'Date Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-date' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-rating .star-rating span::before' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_control(
			'empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-review-rating .star-rating::before' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'review_border',
				'selector' => '{{WRAPPER}} .rs-review-item',
			)
		);

		$this->add_responsive_control(
			'review_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_padding',
			array(
				'label'      => esc_html__( 'Padding', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_margin',
			array(
				'label'      => esc_html__( 'Margin Between Reviews', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style section - Avatar.
		$this->start_controls_section(
			'section_avatar_style',
			array(
				'label'     => esc_html__( 'Avatar', 'rs-elementor-widgets' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'avatar_size',
			array(
				'label'      => esc_html__( 'Size', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'avatar_border',
				'selector' => '{{WRAPPER}} .rs-review-avatar img',
			)
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '50',
					'right'    => '50',
					'bottom'   => '50',
					'left'     => '50',
					'unit'     => '%',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'avatar_margin',
			array(
				'label'      => esc_html__( 'Margin', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-review-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style section - Verified Badge.
		$this->start_controls_section(
			'section_verified_style',
			array(
				'label'     => esc_html__( 'Verified Badge', 'rs-elementor-widgets' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_verified' => 'yes',
				),
			)
		);

		$this->add_control(
			'verified_color',
			array(
				'label'     => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rs-verified-badge' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'verified_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#4CAF50',
				'selectors' => array(
					'{{WRAPPER}} .rs-verified-badge' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'verified_typography',
				'selector' => '{{WRAPPER}} .rs-verified-badge',
			)
		);

		$this->add_responsive_control(
			'verified_padding',
			array(
				'label'      => esc_html__( 'Padding', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => '2',
					'right'    => '8',
					'bottom'   => '2',
					'left'     => '8',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rs-verified-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'verified_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '3',
					'right'    => '3',
					'bottom'   => '3',
					'left'     => '3',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rs-verified-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'verified_margin',
			array(
				'label'      => esc_html__( 'Margin', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-verified-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get products
	 */
	private function get_products() {
		$products = array();

		if ( class_exists( 'WooCommerce' ) ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);

			$products_query = new WP_Query( $args );

			if ( $products_query->have_posts() ) {
				while ( $products_query->have_posts() ) {
					$products_query->the_post();
					$products[ get_the_ID() ] = get_the_title();
				}
				wp_reset_postdata();
			}
		}

		return $products;
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		// Dashicons not needed; Elementor includes Font Awesome.

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<div class="elementor-alert elementor-alert-warning">';
			echo esc_html__( 'WooCommerce is not installed or activated. Please install and activate WooCommerce to use this widget.', 'rs-elementor-widgets' );
			echo '</div>';
			return;
		}

		$settings = $this->get_settings_for_display();

		$show_title           = 'yes' === $settings['show_title'];
		$title                = $settings['title'];
		$initial_reviews      = $settings['initial_reviews'];
		$show_all_button_text = $settings['show_all_button_text'];
		$modal_title          = $settings['modal_title'];
		$order_by             = $settings['order_by'];
		$order                = $settings['order'];
		$show_rating          = 'yes' === $settings['show_rating'];
		$show_date            = 'yes' === $settings['show_date'];
		$show_avatar          = 'yes' === $settings['show_avatar'];
		$show_verified        = 'yes' === $settings['show_verified'];

		// Generate a unique ID for this widget instance.
		$widget_id = 'rs-reviews-' . $this->get_id();

		// Get the current product.
		$product_id = 0;
		if ( is_product() ) {
			global $product;
			$product_id = $product->get_id();
		}

		// If we don't have a product ID, try to provide a preview in Elementor editor mode.
		if ( empty( $product_id ) ) {
			$is_editor = class_exists( '\\Elementor\\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();

			if ( $is_editor ) {
				// Fallback to a recent published product for preview.
				if ( function_exists( 'wc_get_products' ) ) {
					$sample_ids = wc_get_products(
						array(
							'status'  => 'publish',
							'limit'   => 1,
							'orderby' => 'date',
							'order'   => 'DESC',
							'return'  => 'ids',
						)
					);
					if ( ! empty( $sample_ids ) ) {
						$product_id = (int) $sample_ids[0];
					}
				}

				// If we still couldn't find a product, inform the user in the editor.
				if ( empty( $product_id ) ) {
					echo '<div class="elementor-alert elementor-alert-info">';
					echo esc_html__( 'No products found to preview. Please create a WooCommerce product or place this widget on a product template.', 'rs-elementor-widgets' );
					echo '</div>';
					return;
				}
			} else {
				// Front-end: keep current behavior.
				echo '<div class="elementor-alert elementor-alert-info">';
				echo esc_html__( 'This widget can only be used on product pages.', 'rs-elementor-widgets' );
				echo '</div>';
				return;
			}
		}

		// Get the initial reviews.
		$args = array(
			'post_type' => 'product',
			'post_id'   => $product_id,
			'number'    => $initial_reviews,
			'status'    => 'approve',
		);

		// Set the order.
		if ( 'rating' === $order_by ) {
			$args['meta_key'] = 'rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$args['orderby']  = 'meta_value_num';
		} else {
			$args['orderby'] = 'date';
		}

		$args['order'] = $order;

		$initial_comments = get_comments( $args );

		// Get all reviews for the modal.
		$all_args     = array(
			'post_type' => 'product',
			'post_id'   => $product_id,
			'status'    => 'approve',
		);
		$all_comments = get_comments( $all_args );

		// If no reviews, show a message.
		if ( empty( $all_comments ) ) {
			echo '<div class="rs-product-reviews">';
			if ( $title ) {
				echo '<h2 class="rs-reviews-title">' . esc_html( $title ) . '</h2>';
			}
			echo '<p>' . esc_html__( 'No reviews found for this product.', 'rs-elementor-widgets' ) . '</p>';
			echo '</div>';
			return;
		}

		// Display the reviews.
		?>
		<div class="rs-product-reviews" id="<?php echo esc_attr( $widget_id ); ?>">
			<?php if ( $show_title && $title ) : ?>
				<h2 class="rs-reviews-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<div class="rs-reviews-list">
				<?php
				if ( ! empty( $initial_comments ) ) :
					foreach ( $initial_comments as $comment ) :
						$rating   = get_comment_meta( $comment->comment_ID, 'rating', true );
						$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
						?>
					<div class="rs-review-item">
						<div class="rs-review-header">
							<?php if ( $show_avatar ) : ?>
								<div class="rs-review-avatar">
									<?php echo get_avatar( $comment, 50 ); ?>
								</div>
							<?php endif; ?>

							<div class="rs-review-meta">
								<span class="rs-review-author"><?php echo esc_html( $comment->comment_author ); ?></span>
								
								<?php if ( $show_verified && $verified ) : ?>
									<span class="rs-verified-badge"><?php echo esc_html__( 'Verified Purchase', 'rs-elementor-widgets' ); ?></span>
								<?php endif; ?>
								
								<?php if ( $show_date ) : ?>
									<span class="rs-review-date">
										<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) ); ?>
									</span>
								<?php endif; ?>
								
								<?php if ( $show_rating && $rating ) : ?>
									<div class="rs-review-rating">
										<?php echo wc_get_rating_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="rs-review-content">
							<?php echo wpautop( wp_kses_post( $comment->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
						<?php
					endforeach;
				endif;
				?>
			</div>

			<?php if ( count( $all_comments ) > count( $initial_comments ) ) : ?>
				<div class="rs-show-all-reviews-container">
					<button class="rs-show-all-reviews" data-widget-id="<?php echo esc_attr( $widget_id ); ?>">
						<?php echo esc_html( $show_all_button_text ); ?>
					</button>
				</div>
			<?php endif; ?>

			<!-- Modal for all reviews -->
			<div id="<?php echo esc_attr( $widget_id ); ?>-modal" class="rs-reviews-modal">
				<div class="rs-reviews-modal-content">
					<span class="rs-reviews-modal-close">&times;</span>
					<h2 class="rs-reviews-modal-title"><?php echo esc_html( $modal_title ); ?></h2>
					
					<div class="rs-reviews-modal-sorting">
						<span class="rs-sort-label"><?php echo esc_html__( 'Sort by Rating:', 'rs-elementor-widgets' ); ?></span>
						<button type="button" class="rs-sort-toggle" data-sort="rating-desc" title="<?php echo esc_attr__( 'Highest to Lowest', 'rs-elementor-widgets' ); ?>" aria-label="<?php echo esc_attr__( 'Sort reviews', 'rs-elementor-widgets' ); ?>">
							<i class="fas fa-sort-amount-down" aria-hidden="true"></i>
						</button>
					</div>
					
					<div class="rs-reviews-modal-list">
						<?php
						// Render all reviews in the modal on initial load (no AJAX).
						foreach ( $all_comments as $comment ) :
							$rating   = get_comment_meta( $comment->comment_ID, 'rating', true );
							$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
							echo $this->get_review_html( $comment, $rating, $verified, $show_avatar, $show_verified, $show_date, $show_rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						endforeach;
						?>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Generate HTML for a single review.
	 *
	 * @param WP_Comment $comment       The comment object for the review.
	 * @param int|string $rating        The numeric rating (1-5) stored in comment meta.
	 * @param bool       $verified      Whether the reviewer is a verified owner.
	 * @param bool       $show_avatar   Whether to show the avatar.
	 * @param bool       $show_verified Whether to show the verified badge.
	 * @param bool       $show_date     Whether to show the review date.
	 * @param bool       $show_rating   Whether to show the star rating.
	 *
	 * @return string The rendered review HTML.
	 */
	private function get_review_html( $comment, $rating, $verified, $show_avatar, $show_verified, $show_date, $show_rating ) {
		ob_start();
		?>
		<div class="rs-review-item" data-rating="<?php echo esc_attr( $rating ); ?>">
			<div class="rs-review-header">
				<?php if ( $show_avatar ) : ?>
					<div class="rs-review-avatar">
						<?php echo get_avatar( $comment, 50 ); ?>
					</div>
				<?php endif; ?>

				<div class="rs-review-meta">
					<span class="rs-review-author"><?php echo esc_html( $comment->comment_author ); ?></span>
					
					<?php if ( $show_verified && $verified ) : ?>
						<span class="rs-verified-badge"><?php echo esc_html__( 'Verified Purchase', 'rs-elementor-widgets' ); ?></span>
					<?php endif; ?>
					
					<?php if ( $show_date ) : ?>
						<span class="rs-review-date">
							<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) ); ?>
						</span>
					<?php endif; ?>
					
					<?php if ( $show_rating && $rating ) : ?>
						<div class="rs-review-rating">
							<?php echo wc_get_rating_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="rs-review-content">
				<?php echo wpautop( wp_kses_post( $comment->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

// AJAX and REST loading removed: all reviews are rendered on initial page load and sorted client-side only.

<?php
/**
 * Product Reviews Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Bail out early if Elementor isn't loaded yet to avoid fatal errors
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
    return;
}

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
        return [ 'rs-woocommerce' ];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'product', 'reviews', 'comments', 'ratings' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show Title', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'rs-elementor-widgets' ),
                'label_off' => esc_html__( 'Hide', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Product Reviews', 'rs-elementor-widgets' ),
                'placeholder' => esc_html__( 'Enter your title', 'rs-elementor-widgets' ),
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        // Note: Product selection has been removed as the widget now always uses the current product

        $this->add_control(
            'initial_reviews',
            [
                'label' => esc_html__( 'Initial Reviews to Show', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
                'description' => esc_html__( 'Number of reviews to show initially before clicking "Show All"', 'rs-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'show_all_button_text',
            [
                'label' => esc_html__( 'Show All Button Text', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Show All Reviews', 'rs-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'modal_title',
            [
                'label' => esc_html__( 'Modal Title', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'All Product Reviews', 'rs-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__( 'Initial Order By', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__( 'Date', 'rs-elementor-widgets' ),
                    'rating' => esc_html__( 'Rating', 'rs-elementor-widgets' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Initial Order', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => esc_html__( 'ASC', 'rs-elementor-widgets' ),
                    'desc' => esc_html__( 'DESC', 'rs-elementor-widgets' ),
                ],
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label' => esc_html__( 'Show Rating', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off' => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__( 'Show Date', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off' => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_avatar',
            [
                'label' => esc_html__( 'Show Avatar', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off' => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_verified',
            [
                'label' => esc_html__( 'Show Verified Badge', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'rs-elementor-widgets' ),
                'label_off' => esc_html__( 'No', 'rs-elementor-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Modal Style Section
        $this->start_controls_section(
            'section_modal_style',
            [
                'label' => esc_html__( 'Modal', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'modal_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.rs-reviews-modal-content' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'modal_text_color',
            [
                'label' => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.rs-reviews-modal-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'modal_close_color',
            [
                'label' => esc_html__( 'Close Button Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.rs-reviews-modal-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'modal_overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.8)',
                'selectors' => [
                    '.rs-reviews-modal' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'show_all_button_color',
            [
                'label' => esc_html__( 'Show All Button Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-show-all-reviews' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'show_all_button_bg_color',
            [
                'label' => esc_html__( 'Show All Button Background', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-show-all-reviews' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-reviews-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .rs-reviews-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-reviews-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Reviews
        $this->start_controls_section(
            'section_reviews_style',
            [
                'label' => esc_html__( 'Reviews', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'review_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'review_text_color',
            [
                'label' => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'review_author_color',
            [
                'label' => esc_html__( 'Author Name Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-author' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'review_date_color',
            [
                'label' => esc_html__( 'Date Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-date' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-rating .star-rating span::before' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_rating' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => esc_html__( 'Empty Star Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rs-review-rating .star-rating::before' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_rating' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'review_border',
                'selector' => '{{WRAPPER}} .rs-review-item',
            ]
        );

        $this->add_responsive_control(
            'review_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'review_padding',
            [
                'label' => esc_html__( 'Padding', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'review_margin',
            [
                'label' => esc_html__( 'Margin Between Reviews', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Avatar
        $this->start_controls_section(
            'section_avatar_style',
            [
                'label' => esc_html__( 'Avatar', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_avatar' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => esc_html__( 'Size', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .rs-review-avatar img',
            ]
        );

        $this->add_responsive_control(
            'avatar_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_margin',
            [
                'label' => esc_html__( 'Margin', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-review-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Verified Badge
        $this->start_controls_section(
            'section_verified_style',
            [
                'label' => esc_html__( 'Verified Badge', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_verified' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'verified_color',
            [
                'label' => esc_html__( 'Text Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .rs-verified-badge' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'verified_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4CAF50',
                'selectors' => [
                    '{{WRAPPER}} .rs-verified-badge' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'verified_typography',
                'selector' => '{{WRAPPER}} .rs-verified-badge',
            ]
        );

        $this->add_responsive_control(
            'verified_padding',
            [
                'label' => esc_html__( 'Padding', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '2',
                    'right' => '8',
                    'bottom' => '2',
                    'left' => '8',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-verified-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'verified_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => '3',
                    'right' => '3',
                    'bottom' => '3',
                    'left' => '3',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-verified-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'verified_margin',
            [
                'label' => esc_html__( 'Margin', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-verified-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get products
     */
    private function get_products() {
        $products = [];
        
        if (class_exists('WooCommerce')) {
            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            ];
            
            $products_query = new WP_Query($args);
            
            if ($products_query->have_posts()) {
                while ($products_query->have_posts()) {
                    $products_query->the_post();
                    $products[get_the_ID()] = get_the_title();
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
        // Dashicons not needed; Elementor includes Font Awesome
        
        if (!class_exists('WooCommerce')) {
            echo '<div class="elementor-alert elementor-alert-warning">';
            echo esc_html__('WooCommerce is not installed or activated. Please install and activate WooCommerce to use this widget.', 'rs-elementor-widgets');
            echo '</div>';
            return;
        }

        $settings = $this->get_settings_for_display();

        $show_title = $settings['show_title'] === 'yes';
        $title = $settings['title'];
        $initial_reviews = $settings['initial_reviews'];
        $show_all_button_text = $settings['show_all_button_text'];
        $modal_title = $settings['modal_title'];
        $order_by = $settings['order_by'];
        $order = $settings['order'];
        $show_rating = $settings['show_rating'] === 'yes';
        $show_date = $settings['show_date'] === 'yes';
        $show_avatar = $settings['show_avatar'] === 'yes';
        $show_verified = $settings['show_verified'] === 'yes';

        // Generate a unique ID for this widget instance
        $widget_id = 'rs-reviews-' . $this->get_id();

        // Get the current product
        $product_id = 0;
        if (is_product()) {
            global $product;
            $product_id = $product->get_id();
        }

        // If we don't have a product ID, show a message
        if (empty($product_id)) {
            echo '<div class="elementor-alert elementor-alert-info">';
            echo esc_html__('This widget can only be used on product pages.', 'rs-elementor-widgets');
            echo '</div>';
            return;
        }

        // Get the initial reviews
        $args = [
            'post_type' => 'product',
            'post_id' => $product_id,
            'number' => $initial_reviews,
            'status' => 'approve',
        ];

        // Set the order
        if ($order_by === 'rating') {
            $args['meta_key'] = 'rating';
            $args['orderby'] = 'meta_value_num';
        } else {
            $args['orderby'] = 'date';
        }
        
        $args['order'] = $order;

        $initial_comments = get_comments($args);

        // Get all reviews for the modal
        $all_args = [
            'post_type' => 'product',
            'post_id' => $product_id,
            'status' => 'approve',
        ];
        $all_comments = get_comments($all_args);

        // If no reviews, show a message
        if (empty($all_comments)) {
            echo '<div class="rs-product-reviews">';
            if ($title) {
                echo '<h2 class="rs-reviews-title">' . esc_html($title) . '</h2>';
            }
            echo '<p>' . esc_html__('No reviews found for this product.', 'rs-elementor-widgets') . '</p>';
            echo '</div>';
            return;
        }

        // Display the reviews
        ?>
        <div class="rs-product-reviews" id="<?php echo esc_attr($widget_id); ?>">
            <?php if ($show_title && $title) : ?>
                <h2 class="rs-reviews-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div class="rs-reviews-list">
                <?php 
                if (!empty($initial_comments)) :
                    foreach ($initial_comments as $comment) : 
                        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                        $verified = wc_review_is_from_verified_owner($comment->comment_ID);
                ?>
                    <div class="rs-review-item">
                        <div class="rs-review-header">
                            <?php if ($show_avatar) : ?>
                                <div class="rs-review-avatar">
                                    <?php echo get_avatar($comment, 50); ?>
                                </div>
                            <?php endif; ?>

                            <div class="rs-review-meta">
                                <span class="rs-review-author"><?php echo esc_html($comment->comment_author); ?></span>
                                
                                <?php if ($show_verified && $verified) : ?>
                                    <span class="rs-verified-badge"><?php echo esc_html__('Verified Purchase', 'rs-elementor-widgets'); ?></span>
                                <?php endif; ?>
                                
                                <?php if ($show_date) : ?>
                                    <span class="rs-review-date">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($comment->comment_date))); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($show_rating && $rating) : ?>
                                    <div class="rs-review-rating">
                                        <?php echo wc_get_rating_html($rating); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="rs-review-content">
                            <?php echo wpautop(wp_kses_post($comment->comment_content)); ?>
                        </div>
                    </div>
                <?php 
                    endforeach; 
                endif;
                ?>
            </div>

            <?php if (count($all_comments) > count($initial_comments)) : ?>
                <div class="rs-show-all-reviews-container">
                    <button class="rs-show-all-reviews" data-widget-id="<?php echo esc_attr($widget_id); ?>">
                        <?php echo esc_html($show_all_button_text); ?>
                    </button>
                </div>
            <?php endif; ?>

             <!-- Modal for all reviews -->
            <div id="<?php echo esc_attr($widget_id); ?>-modal" class="rs-reviews-modal">
                <div class="rs-reviews-modal-content">
                    <span class="rs-reviews-modal-close">&times;</span>
                    <h2 class="rs-reviews-modal-title"><?php echo esc_html($modal_title); ?></h2>
                    
                    <div class="rs-reviews-modal-sorting">
                        <span class="rs-sort-label"><?php echo esc_html__('Sort by Rating:', 'rs-elementor-widgets'); ?></span>
                        <button type="button" class="rs-sort-toggle" data-sort="rating-desc" title="<?php echo esc_attr__('Highest to Lowest', 'rs-elementor-widgets'); ?>" aria-label="<?php echo esc_attr__('Sort reviews', 'rs-elementor-widgets'); ?>">
                            <i class="fas fa-sort-amount-down" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                    <div class="rs-reviews-modal-list">
                        <?php 
                        // Render all reviews in the modal on initial load (no AJAX)
                        foreach ($all_comments as $comment) :
                            $rating   = get_comment_meta($comment->comment_ID, 'rating', true);
                            $verified = wc_review_is_from_verified_owner($comment->comment_ID);
                            echo $this->get_review_html($comment, $rating, $verified, $show_avatar, $show_verified, $show_date, $show_rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Base styles */
            .rs-product-reviews {
                margin-bottom: 30px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            
            .rs-reviews-title {
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: 600;
            }
            
            /* Review item styles */
            .rs-review-item {
                margin-bottom: 20px;
                padding: 15px;
                border: 1px solid #e5e5e5;
                border-radius: 5px;
                background-color: #fff;
                transition: all 0.3s ease;
            }
            
            .rs-review-item:hover {
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            }
            
            .rs-review-header {
                display: flex;
                align-items: flex-start;
                margin-bottom: 10px;
            }
            
            .rs-review-avatar {
                margin-right: 15px;
                flex-shrink: 0;
            }
            
            .rs-review-avatar img {
                border-radius: 50%;
                width: 50px;
                height: 50px;
            }
            
            .rs-review-meta {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
            }
            
            .rs-review-author {
                font-weight: bold;
                margin-right: 10px;
                width: 100%;
                margin-bottom: 5px;
            }
            
            .rs-verified-badge {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                font-size: 12px;
                padding: 2px 8px;
                border-radius: 3px;
                margin-right: 10px;
            }
            
            .rs-review-date {
                color: #777;
                font-size: 0.9em;
                margin-right: 10px;
            }
            
            .rs-review-rating {
                margin-top: 5px;
            }
            
            .rs-review-rating .star-rating {
                float: none;
                display: inline-block;
            }
            
            .rs-review-content {
                margin-top: 10px;
                line-height: 1.6;
            }
            
            .rs-review-content p:last-child {
                margin-bottom: 0;
            }
            
            /* Show All Button */
            .rs-show-all-reviews-container {
                text-align: center;
                margin-top: 20px;
            }
            
            .rs-show-all-reviews {
                background-color: #f7f7f7;
                color: #333;
                border: 1px solid #ddd;
                padding: 10px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            
            .rs-show-all-reviews:hover {
                background-color: #ebebeb;
            }
            
            /* Modal Styles */
            .rs-reviews-modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.8);
            }
            
            .rs-reviews-modal-content {
                background-color: #fff;
                margin: 5% auto;
                padding: 20px;
                border-radius: 5px;
                width: 80%;
                max-width: 800px;
                max-height: 80vh;
                overflow-y: auto;
                position: relative;
            }
            
            .rs-reviews-modal-close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
                line-height: 1;
            }
            
            .rs-reviews-modal-close:hover,
            .rs-reviews-modal-close:focus {
                color: #000;
                text-decoration: none;
            }
            
            .rs-reviews-modal-title {
                margin-top: 0;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            
            .rs-reviews-modal-sorting {
                margin-bottom: 20px;
                display: flex;
                align-items: center;
            }
            
            .rs-sort-label {
                margin-right: 10px;
                font-weight: 600;
            }
            
            .rs-sort-toggle {
                background: #f7f7f7;
                border: 1px solid #ddd;
                padding: 6px 12px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                border-radius: 4px;
            }

            .rs-sort-toggle:hover {
                background: #ebebeb;
            }

            .rs-sort-toggle .fa,
            .rs-sort-toggle .fas {
                font-size: 18px;
                width: 18px;
                height: 18px;
                line-height: 18px;
            }

            .rs-reviews-modal-list .rs-loading,
            .rs-reviews-modal-list .rs-error {
                padding: 12px 0;
                color: #666;
            }
            
            /* Responsive styles */
            @media (max-width: 767px) {
                .rs-reviews-modal-content {
                    width: 95%;
                    margin: 10% auto;
                    padding: 15px;
                }
                
                .rs-review-header {
                    flex-direction: column;
                }
                
                .rs-review-avatar {
                    margin-right: 0;
                    margin-bottom: 10px;
                }
                
                .rs-review-meta {
                    width: 100%;
                }
            }
        </style>

        <script type="text/javascript">
            (function() {
                document.addEventListener('DOMContentLoaded', function() {
                    // Get elements
                    var widgetId = '<?php echo esc_js($widget_id); ?>';
                    var showAllBtn = document.querySelector('#' + widgetId + ' .rs-show-all-reviews');
                    var modal = document.getElementById(widgetId + '-modal');
                    var closeBtn = modal.querySelector('.rs-reviews-modal-close');
                    var modalList = modal.querySelector('.rs-reviews-modal-list');
                    var sortToggle = modal.querySelector('.rs-sort-toggle');

                    // Client-side sorting (no AJAX). Sort .rs-review-item nodes by data-rating
                    function sortReviews(sortType) {
                        var items = Array.prototype.slice.call(modalList.querySelectorAll('.rs-review-item'));
                        if (!items.length) return;
                        var desc = sortType === 'rating-desc';
                        items.sort(function(a, b) {
                            var ra = parseFloat(a.getAttribute('data-rating')) || 0;
                            var rb = parseFloat(b.getAttribute('data-rating')) || 0;
                            return desc ? (rb - ra) : (ra - rb);
                        });
                        // Re-append in sorted order
                        items.forEach(function(el){ modalList.appendChild(el); });
                    }

                    // Show modal when clicking the button
                    if (showAllBtn) {
                        showAllBtn.addEventListener('click', function() {
                            modal.style.display = 'block';
                            // Apply default client-side sort
                            sortReviews('rating-desc');
                            document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
                        });
                    }
                    
                    // Close modal when clicking the close button
                    closeBtn.addEventListener('click', function() {
                        modal.style.display = 'none';
                        document.body.style.overflow = ''; // Restore scrolling
                    });
                    
                    // Close modal when clicking outside of it
                    window.addEventListener('click', function(event) {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = ''; // Restore scrolling
                        }
                    });
                    
                    // Toggle sort order with a single button (client-side)
                    if (sortToggle) {
                        sortToggle.addEventListener('click', function() {
                            var current = this.getAttribute('data-sort') || 'rating-desc';
                            var next = current === 'rating-desc' ? 'rating-asc' : 'rating-desc';
                            this.setAttribute('data-sort', next);
                            // Update icon and title
                            var icon = this.querySelector('i');
                            if (icon) {
                                if (next === 'rating-desc') {
                                    icon.className = 'fas fa-sort-amount-down';
                                    this.title = '<?php echo esc_js(__('Highest to Lowest', 'rs-elementor-widgets')); ?>';
                                } else {
                                    icon.className = 'fas fa-sort-amount-up';
                                    this.title = '<?php echo esc_js(__('Lowest to Highest', 'rs-elementor-widgets')); ?>';
                                }
                            }
                            // Sort in the browser
                            sortReviews(next);
                        });
                    }
                    // Ensure initial ordering matches default and correct icon state
                    sortReviews('rating-desc');
                });
            })();
        </script>
        <?php
    }
    
    /**
     * Generate HTML for a single review
     */
    private function get_review_html($comment, $rating, $verified, $show_avatar, $show_verified, $show_date, $show_rating) {
        ob_start();
        ?>
        <div class="rs-review-item" data-rating="<?php echo esc_attr($rating); ?>">
            <div class="rs-review-header">
                <?php if ($show_avatar) : ?>
                    <div class="rs-review-avatar">
                        <?php echo get_avatar($comment, 50); ?>
                    </div>
                <?php endif; ?>

                <div class="rs-review-meta">
                    <span class="rs-review-author"><?php echo esc_html($comment->comment_author); ?></span>
                    
                    <?php if ($show_verified && $verified) : ?>
                        <span class="rs-verified-badge"><?php echo esc_html__('Verified Purchase', 'rs-elementor-widgets'); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($show_date) : ?>
                        <span class="rs-review-date">
                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($comment->comment_date))); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($show_rating && $rating) : ?>
                        <div class="rs-review-rating">
                            <?php echo wc_get_rating_html($rating); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rs-review-content">
                <?php echo wpautop(wp_kses_post($comment->comment_content)); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// AJAX and REST loading removed: all reviews are rendered on initial page load and sorted client-side only.

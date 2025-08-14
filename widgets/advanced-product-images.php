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
        return [ 'rs-woocommerce' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'product', 'gallery', 'images', 'thumbnails', 'lightbox' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'rs-elementor-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'thumbs_position',
            [
                'label' => esc_html__( 'Thumbnails Position', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left', 'rs-elementor-widgets' ),
                    'right' => esc_html__( 'Right', 'rs-elementor-widgets' ),
                    'top' => esc_html__( 'Top', 'rs-elementor-widgets' ),
                    'bottom' => esc_html__( 'Bottom', 'rs-elementor-widgets' ),
                ],
            ]
        );

        $this->add_control(
            'thumb_size',
            [
                'label' => esc_html__( 'Thumbnail Size (px)', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 40,
                'max' => 200,
                'step' => 2,
                'default' => 80,
            ]
        );

        $this->add_control(
            'thumb_gap',
            [
                'label' => esc_html__( 'Thumbnail Gap (px)', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'default' => 8,
            ]
        );

        $this->end_controls_section();


        // Styles: Main Image
        $this->start_controls_section(
            'section_style_main',
            [
                'label' => esc_html__( 'Main Image', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'main_height',
            [
                'label' => esc_html__( 'Fixed Height', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 100, 'max' => 1200 ],
                    'vh' => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-main' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rs-adv-main .rs-adv-main-img' => 'height: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'main_max_height',
            [
                'label' => esc_html__( 'Max Height', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 100, 'max' => 1200 ],
                    'vh' => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-main' => '--rs-main-max: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'main_fit',
            [
                'label' => esc_html__( 'When image is larger', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    'contain' => esc_html__( 'Scale to fit (contain)', 'rs-elementor-widgets' ),
                    'cover' => esc_html__( 'Crop to fill (cover)', 'rs-elementor-widgets' ),
                    'scale-down' => esc_html__( 'Only scale down', 'rs-elementor-widgets' ),
                    'fill' => esc_html__( 'Stretch to fill', 'rs-elementor-widgets' ),
                    'none' => esc_html__( 'No scaling', 'rs-elementor-widgets' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-main-img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_object_position',
            [
                'label' => esc_html__( 'Focal Position', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'rs-elementor-widgets' ),
                    'top left' => esc_html__( 'Top Left', 'rs-elementor-widgets' ),
                    'top center' => esc_html__( 'Top Center', 'rs-elementor-widgets' ),
                    'top right' => esc_html__( 'Top Right', 'rs-elementor-widgets' ),
                    'center left' => esc_html__( 'Center Left', 'rs-elementor-widgets' ),
                    'center right' => esc_html__( 'Center Right', 'rs-elementor-widgets' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'rs-elementor-widgets' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'rs-elementor-widgets' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'rs-elementor-widgets' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-main-img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Styles: Thumbnails
        $this->start_controls_section(
            'section_style_thumbs',
            [
                'label' => esc_html__( 'Thumbnails', 'rs-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'thumb_border',
                'label' => esc_html__( 'Border', 'rs-elementor-widgets' ),
                'selector' => '{{WRAPPER}} .rs-adv-thumb',
            ]
        );

        $this->add_control(
            'thumb_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .rs-adv-modal-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumb_active_border_color',
            [
                'label' => esc_html__( 'Border Color (Active)', 'rs-elementor-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0073aa',
                'selectors' => [
                    '{{WRAPPER}} .rs-adv-thumb.is-active' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'thumb_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__( 'WooCommerce is required for this widget.', 'rs-elementor-widgets' ) . '</div>';
            return;
        }

        $settings = $this->get_settings_for_display();
        $thumbs_position = $settings['thumbs_position'];
        $thumb_size = isset($settings['thumb_size']) ? (int)$settings['thumb_size'] : 80;
        $thumb_gap = isset($settings['thumb_gap']) ? (int)$settings['thumb_gap'] : 8;

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

        $main_id = $product->get_image_id();
        $gallery_ids = $product->get_gallery_image_ids();

        // Build images array: put main image first if exists
        $image_ids = [];
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

        if ( empty( $image_ids ) ) {
            echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No images for this product.', 'rs-elementor-widgets' ) . '</div>';
            return;
        }

        // Prepare URLs
        $images = [];
        foreach ( $image_ids as $aid ) {
            $full = wp_get_attachment_image_src( $aid, 'full' );
            $large = wp_get_attachment_image_src( $aid, 'large' );
            $thumb = wp_get_attachment_image_src( $aid, 'woocommerce_gallery_thumbnail' );
            $images[] = [
                'id' => $aid,
                'full' => $full ? $full[0] : '',
                'large' => $large ? $large[0] : '',
                'thumb' => $thumb ? $thumb[0] : '',
                'alt' => esc_attr( get_post_meta( $aid, '_wp_attachment_image_alt', true ) ),
            ];
        }

        $widget_id = 'rs-adv-images-' . $this->get_id();
        $allowed_positions = [ 'left', 'right', 'top', 'bottom' ];
        $pos = in_array( $thumbs_position, $allowed_positions, true ) ? $thumbs_position : 'left';
        $container_classes = 'rs-adv-images layout-' . $pos;
        ?>
        <div id="<?php echo esc_attr( $widget_id ); ?>" class="<?php echo esc_attr( $container_classes ); ?>">
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

        <style>
            #<?php echo esc_js( $widget_id ); ?>.rs-adv-images { --thumb-size: <?php echo (int) $thumb_size; ?>px; --thumb-gap: <?php echo (int) $thumb_gap; ?>px; }
            .rs-adv-images { width: 100%; }
            .rs-adv-images .rs-adv-images-inner { display: flex; gap: 12px; align-items: stretch; }

            /* Flex layouts per side */
            .rs-adv-images.layout-left .rs-adv-images-inner { flex-direction: row; }
            .rs-adv-images.layout-right .rs-adv-images-inner { flex-direction: row-reverse; }
            .rs-adv-images.layout-top .rs-adv-images-inner { flex-direction: column; }
            .rs-adv-images.layout-bottom .rs-adv-images-inner { flex-direction: column; }

            /* Thumbs sizing and flow */
            .rs-adv-thumbs { display: flex; gap: var(--thumb-gap); }
            .rs-adv-main { flex: 1 1 auto; min-width: 0; }

            /* Vertical thumbs for left/right */
            .rs-adv-images.layout-left .rs-adv-thumbs,
            .rs-adv-images.layout-right .rs-adv-thumbs { flex: 0 0 auto; flex-direction: column; max-height: 480px; overflow-y: auto; }
            .rs-adv-images.layout-left .rs-adv-thumbs { margin-right: 0; }
            .rs-adv-images.layout-right .rs-adv-thumbs { margin-left: 0; }

            /* Horizontal thumbs for top/bottom */
            .rs-adv-images.layout-top .rs-adv-thumbs,
            .rs-adv-images.layout-bottom .rs-adv-thumbs { flex-direction: row; overflow-x: auto; }

            .rs-adv-thumb { padding: 0; border: 0; border-radius: 4px; background: #fff; cursor: pointer; width: var(--thumb-size); height: var(--thumb-size); display: inline-flex; align-items: center; justify-content: center; overflow: hidden; box-sizing: border-box; line-height: 0; }
            .rs-adv-thumb img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; border-radius: inherit; }
            .rs-adv-thumb.is-active { box-shadow: none; }

            .rs-adv-main { position: relative; border: 1px solid #eee; border-radius: 6px; overflow: hidden; cursor: zoom-in; display: flex; align-items: center; justify-content: center; background: #fff; min-height: 300px; max-height: var(--rs-main-max, none); }
            .rs-adv-main-img { width: 100%; height: auto; max-height: 100%; display: block; }

            /* Modal */
            .rs-adv-modal { display: none; position: fixed; inset: 0; z-index: 9999; }
            .rs-adv-modal.is-open { display: block; }
            .rs-adv-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
            .rs-adv-modal-content { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 24px; }
            .rs-adv-modal-img { max-width: 90vw; max-height: 85vh; box-shadow: 0 10px 30px rgba(0,0,0,0.35); border-radius: 6px; background: #000; }
            .rs-adv-modal-close { position: absolute; top: 12px; right: 18px; font-size: 28px; color: #fff; background: transparent; border: 0; cursor: pointer; line-height: 1; }

            .rs-adv-nav { position: absolute; top: 50%; transform: translateY(-50%); padding: 10px; color: #fff; background: rgba(0,0,0,0.4); border: 0; cursor: pointer; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; }
            .rs-adv-prev { left: 16px; }
            .rs-adv-next { right: 16px; }
            .rs-adv-nav .fa, .rs-adv-nav .fas { font-size: 20px; }

            @media (max-width: 767px) {
                /* Stack on mobile: main first, then thumbs; thumbs horizontal */
                .rs-adv-images.layout-left .rs-adv-images-inner,
                .rs-adv-images.layout-right .rs-adv-images-inner,
                .rs-adv-images.layout-top .rs-adv-images-inner,
                .rs-adv-images.layout-bottom .rs-adv-images-inner { flex-direction: column !important; }
                .rs-adv-main { order: 1; width: 100%; }
                .rs-adv-thumbs { order: 2; width: 100%; flex-direction: row !important; overflow-x: auto; max-height: none; }
            }
        </style>

        <script>
            (function(){
                document.addEventListener('DOMContentLoaded', function(){
                    var root = document.getElementById('<?php echo esc_js( $widget_id ); ?>');
                    if (!root) return;
                    var thumbs = Array.prototype.slice.call(root.querySelectorAll('.rs-adv-thumb'));
                    var mainImg = root.querySelector('.rs-adv-main-img');
                    var mainArea = root.querySelector('.rs-adv-main');
                    var modal = root.querySelector('.rs-adv-modal');
                    var modalImg = root.querySelector('.rs-adv-modal-img');
                    var btnClose = root.querySelector('.rs-adv-modal-close');
                    var btnPrev = root.querySelector('.rs-adv-prev');
                    var btnNext = root.querySelector('.rs-adv-next');

                    var current = 0;
                    function setCurrent(index) {
                        if (index < 0 || index >= thumbs.length) return;
                        current = index;
                        thumbs.forEach(function(t){ t.classList.remove('is-active'); });
                        var active = thumbs[index];
                        active.classList.add('is-active');
                        var large = active.getAttribute('data-large') || active.getAttribute('data-full');
                        if (large) {
                            mainImg.src = large;
                        }
                    }

                    function openModal(index) {
                        if (index < 0 || index >= thumbs.length) return;
                        var target = thumbs[index];
                        var full = target.getAttribute('data-full') || target.getAttribute('data-large');
                        if (full) {
                            modalImg.src = full;
                        }
                        modal.classList.add('is-open');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeModal() {
                        modal.classList.remove('is-open');
                        modal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    }

                    // Thumb click: select image; clicking the active thumb opens modal
                    thumbs.forEach(function(btn, idx){
                        btn.addEventListener('click', function(){
                            if (idx === current) {
                                openModal(current);
                            } else {
                                setCurrent(idx);
                            }
                        });
                    });

                    // Main area click opens modal
                    mainArea.addEventListener('click', function(){ openModal(current); });
                    mainArea.addEventListener('keypress', function(e){ if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(current); } });

                    // Modal controls
                    btnClose.addEventListener('click', closeModal);
                    modal.addEventListener('click', function(e){ if (e.target.classList.contains('rs-adv-modal-backdrop')) closeModal(); });

                    function showPrev(){ setCurrent((current - 1 + thumbs.length) % thumbs.length); openModal(current); }
                    function showNext(){ setCurrent((current + 1) % thumbs.length); openModal(current); }
                    btnPrev.addEventListener('click', showPrev);
                    btnNext.addEventListener('click', showNext);

                    window.addEventListener('keydown', function(e){
                        if (!modal.classList.contains('is-open')) return;
                        if (e.key === 'Escape') { closeModal(); }
                        if (e.key === 'ArrowLeft') { showPrev(); }
                        if (e.key === 'ArrowRight') { showNext(); }
                    });

                    // Initialize
                    setCurrent(0);
                });
            })();
        </script>
        <?php
    }
}

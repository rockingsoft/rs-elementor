<?php
/**
 * Advanced Info Table Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Bail if Elementor isn't loaded yet to avoid fatals
if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

class RS_Elementor_Widget_Advanced_Info_Table extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rs_advanced_info_table';
	}

	public function get_title() {
		return esc_html__( 'Advanced Info Table', 'rs-elementor-widgets' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return array( 'rs-woocommerce' );
	}

	public function get_keywords() {
		return array( 'woocommerce', 'product', 'attributes', 'table', 'specs', 'details' );
	}

	public function get_style_depends() {
		return array( 'rs-advanced-info-table' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {
		// Content settings
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'                => esc_html__( 'Columns', 'rs-elementor-widgets' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'default'              => '1',
				'options'              => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors_dictionary' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'            => array(
					'{{WRAPPER}} .rs-adv-info-table' => '--rs-columns: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'include_weight',
			array(
				'label'        => esc_html__( 'Include Shipping Weight', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'include_dimensions',
			array(
				'label'        => esc_html__( 'Include Shipping Dimensions', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_empty',
			array(
				'label'        => esc_html__( 'Show Empty Values', 'rs-elementor-widgets' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rs-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'rs-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'If enabled, shows attributes with empty values.', 'rs-elementor-widgets' ),
			)
		);

		$this->end_controls_section();

		// Style: Table
		$this->start_controls_section(
			'section_style_table',
			array(
				'label' => esc_html__( 'Table', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Row Gap', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-info-table' => '--rs-row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .rs-adv-info-table .rs-info-item',
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'rs-elementor-widgets' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rs-adv-info-table .rs-info-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Labels
		$this->start_controls_section(
			'section_style_labels',
			array(
				'label' => esc_html__( 'Labels', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'selector' => '{{WRAPPER}} .rs-adv-info-table .rs-info-label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-info-table .rs-info-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Values
		$this->start_controls_section(
			'section_style_values',
			array(
				'label' => esc_html__( 'Values', 'rs-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typo',
				'selector' => '{{WRAPPER}} .rs-adv-info-table .rs-info-value',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'rs-elementor-widgets' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rs-adv-info-table .rs-info-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Ensure product context
		global $product;

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			if ( function_exists( 'wc_get_product' ) ) {
				$queried = get_queried_object_id();
				if ( $queried ) {
					$product = wc_get_product( $queried );
				}
			}
		}

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return; // No product
		}

		$settings           = $this->get_settings_for_display();
		$columns            = max( 1, min( 4, intval( $settings['columns'] ?? 1 ) ) );
		$show_empty         = ! empty( $settings['show_empty'] );
		$include_weight     = ! empty( $settings['include_weight'] );
		$include_dimensions = ! empty( $settings['include_dimensions'] );

		$items = array();

		// 1) Product attributes (honor visibility)
		$attributes = $product->get_attributes();
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute ) {
				// WC_Product_Attribute
				if ( ! $attribute->get_visible() ) {
					continue; // honor "Visible on the product page"
				}

				$name  = $attribute->get_name();
				$label = wc_attribute_label( $name );
				$value = '';

				if ( $attribute->is_taxonomy() ) {
					$terms      = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );
					$term_names = array();
					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$term_names[] = esc_html( $term->name );
						}
					}
					$value = implode( ', ', $term_names );
				} else {
					// Custom product attribute (pipe-separated)
					$options = $attribute->get_options();
					if ( is_array( $options ) ) {
						$clean = array_map( 'wp_kses_post', $options );
						$value = implode( ', ', $clean );
					} else {
						$value = wp_kses_post( (string) $options );
					}
				}

				if ( '' === trim( $value ) && ! $show_empty ) {
					continue;
				}

				$items[] = array(
					'label' => $label,
					'value' => $value,
				);
			}
		}

		// 2) Shipping Weight
		if ( $include_weight ) {
			$weight_html = wc_format_weight( $product->get_weight() );
			if ( $weight_html || $show_empty ) {
				$items[] = array(
					'label' => esc_html__( 'Weight', 'rs-elementor-widgets' ),
					'value' => $weight_html,
				);
			}
		}

		// 3) Shipping Dimensions
		if ( $include_dimensions ) {
			$dimensions_html = wc_format_dimensions( $product->get_dimensions( false ) );
			if ( $dimensions_html || $show_empty ) {
				$items[] = array(
					'label' => esc_html__( 'Dimensions', 'rs-elementor-widgets' ),
					'value' => $dimensions_html,
				);
			}
		}

		if ( empty( $items ) ) {
			echo '<div class="elementor-alert elementor-alert-info">' . esc_html__( 'No product attributes to display.', 'rs-elementor-widgets' ) . '</div>';
			return;
		}

		$wrapper_id = 'rs-adv-info-' . $this->get_id();
		$alt_rows   = ! empty( $settings['alt_rows'] );
		$classes    = 'rs-adv-info-table' . ( $alt_rows ? ' has-alt-rows' : '' );
		// Output
		?>
		<div id="<?php echo esc_attr( $wrapper_id ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="--rs-columns: <?php echo (int) $columns; ?>;">
			<?php foreach ( $items as $index => $row ) : ?>
				<div class="rs-info-item">
					<div class="rs-info-label"><?php echo esc_html( $row['label'] ); ?></div>
					<div class="rs-info-value"><?php echo wp_kses_post( $row['value'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		
		<?php
	}
}

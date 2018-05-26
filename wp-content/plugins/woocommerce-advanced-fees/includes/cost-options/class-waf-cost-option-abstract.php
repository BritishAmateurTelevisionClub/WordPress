<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WAF_Cost_Option_Abstract.
 *
 * Abstract class to add new WAF Cost Option.
 *
 * @class       WAF_Cost_Option_Abstract
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Fees
 * @version		1.1.0
 */
abstract class WAF_Cost_Option_Abstract {


	/**
	 * Cost option ID.
	 *
	 * @since 1.1.0
	 * @var string $id ID of the cost option.
	 */
	public $id;


	/**
	 * Cost option name.
	 *
	 * @since 1.1.0
	 * @var string $name Name of the pricing option.
	 */
	public $name;


	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {

		// Register option.
		add_filter( 'woocommerce_advanced_fees_cost_options', array( $this, 'add_cost_option' ) );

		// Save settings
		add_action( 'woocommerce_advanced_fees_save_meta_boxes', array( $this, 'save_advanced_costs' ) );

	}


	/**
	 * Add cost option.
	 *
	 * Add the cost option to the registered options.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $options List of existing cost options.
	 * @return array          List of modified cost options.
	 */
	public function add_cost_option( $options ) {

		$options[ $this->id ] = $this->name;

		return $options;

	}


	/**
	 * Get the pricing rules.
	 *
	 * Get the setup pricing rules for the current post.
	 *
	 * @since 1.0.0
	 *
	 * @param  int   $post_id Post ID to get the pricing options for.
	 * @return mixed
	 */
	public function get_cost_options( $post_id = null ) {

		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		return get_post_meta( $post_id, 'advanced_costs_' . esc_attr( $this->id ), true );

	}


	/**
	 * Output settings.
	 *
	 * Output the settings related to this cost option.
	 *
	 * @since 1.1.0
	 */
	abstract function output();


	/**
	 * Save settings.
	 *
	 * Save the advanced cost settings.
	 *
	 * @since 1.1.0
	 *
	 * @param int $post_id ID of the post being saved.
	 */
	public function save_advanced_costs( $post_id ) {

		$name       = 'advanced_costs_' . esc_attr( $this->id );
		$save_value = isset( $_POST[ $name ] ) ? $_POST[ $name ] : array();

		// Remove template row
		if ( isset( $save_value[9999] ) ) {
			unset( $save_value[9999] );
		}

		array_walk_recursive( $save_value, 'sanitize_text_field' );
		update_post_meta( $post_id, 'advanced_costs_' . esc_attr( $this->id ), $save_value );

	}


	/**
	 * Apply cost.
	 *
	 * Apply the advanced pricing cost to the fee.
	 *
	 * @since 1.1.0
	 *
	 * @param  float $fee_amount Current fee amount.
	 * @param  int   $fee_id     Post ID of the fee being processed.
	 * @return float             Modified fee amount.
	 */
	public function apply_cost_option_cost( $fee_amount, $fee_id ) {

		if ( ! WC()->cart->get_cart() ) :
			return $fee_amount;
		endif;

		$meta = get_post_meta( $fee_id, 'advanced_costs_' . esc_attr( $this->id ), true );

		// Loop through fee cost options
		if ( is_array( $meta ) ) :
			foreach ( $meta as $values ) :

				$shipping_class          = isset( $values['condition']['shipping_class'] ) ? esc_attr( $values['condition']['shipping_class'] ) : null;
				$min                     = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : null;
				$max                     = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : null;
				$cost                    = isset( $values['action']['cost'] ) ? esc_attr( $values['action']['cost'] ) : null;

				$shipping_class_quantity = $this->get_quantity( $shipping_class );
				$shipping_class_subtotal = $this->get_quantity( $shipping_class, '$' );
				$min_compare_quantity    = $this->get_quantity( $shipping_class, $min );
				$max_compare_quantity    = $this->get_quantity( $shipping_class, $max );

				$min = str_replace( array( 'w', '$' ), '', $min );
				$max = str_replace( array( 'w', '$' ), '', $max );

				// Bail if cost is not set
				if ( is_null( $cost ) ) :
					continue;
				endif;

				// Bail if minimum is not set, or item qty is not met
				if ( is_null( $min ) || ( ! empty( $min ) && $min_compare_quantity < $min ) ) :
					continue;
				endif;

				// Bail if maximum is not set, or item qty is not met
				if ( is_null( $max ) || ( ! empty( $max ) && $max_compare_quantity > $max ) ) :
					continue;
				endif;

				$fee_amount += waf_apply_fee_amount( $cost, $shipping_class_quantity, $shipping_class_subtotal );

			endforeach;
		endif;

		return $fee_amount;

	}


	/**
	 * Get quantity.
	 *
	 * Get the quantity to compare the min/max values against. There are some
	 * special characters that can be used as listed below.
	 *
	 * - Use a 'w' in the min and/or max field to set the requirement based on weight of all the related products.
	 * - use a '$' sign in the min and/or max field to set the requirement based on subtotal of all the related products.
	 * - By default, the min/max field requirement is based on the quantity of the related products.
	 *
	 * @param $value
	 * @param null $qty
	 * @return int
	 */
	public function get_quantity( $value = null, $qty = null ) {

		$quantity = 0;

		foreach ( $this->get_related_products( $value ) as $cart_key => $item )  :

			if ( strpos( $qty, 'w' ) !== false ) :
				$quantity += $item['data']->get_weight() * $item['quantity'];
			elseif ( strpos( $qty, '$' ) !== false ) :
				$quantity += $item['data']->get_price() * $item['quantity'];
			else :
				$quantity += $item['quantity'];
			endif;

		endforeach;

		return $quantity;

	}


	/**
	 * Get related products.
	 *
	 * Get the related products from the cart where the cost should be applied to.
	 *
	 * @since 1.1.8
	 *
	 * @param string $value Set value for the advanced option.
	 * @return array List of related products
	 */
	public function get_related_products( $value = null ) {
		return WC()->cart->get_cart();
	}


}

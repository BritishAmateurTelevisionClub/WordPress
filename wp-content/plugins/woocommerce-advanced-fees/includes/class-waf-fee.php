<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WAF_Fee {

	/**
	 * @var int $post_id ID of the post that represents the fee.
	 */
	private $post_id;


	/**
	 * WAF_Fee constructor.
	 *
	 * @since 1.2.1
	 *
	 * @param int $post_id ID of the post that holds the fee settings.
	 */
	public function __construct( $post_id ) {
		$this->post_id = absint( $post_id );
	}


	/**
	 * Get the Fee ID.
	 */
	public function get_id() {
		return $this->post_id;
	}


	/**
	 * Get fee title.
	 *
	 * Get the title related to the fee.
	 *
	 * @since 1.2.1
	 *
	 * @return string Title of the fee.
	 */
	public function get_title() {
		$fee_title = get_post_meta( $this->post_id, 'fee_title', true );
		return apply_filters( 'woocommerce_advanced_fees_get_fee_title', $fee_title, $this->post_id );
	}


	/**
	 * Get the fee amount.
	 *
	 * Get the amount related to the fee. This can be a positive or negative amount.
	 *
	 * @since 1.2.1
	 *
	 * @return mixed|void
	 */
	public function get_fee_amount() {
		$cart_quantities = WC()->cart->get_cart_item_quantities();
		$cart_quantity   = array_sum( array_values( $cart_quantities ) );
		$fee_amount      = get_post_meta( $this->post_id, 'fee_amount', true );
		$fee_amount      = esc_attr( $fee_amount );
		$fee_amount      = waf_apply_fee_amount( $fee_amount, $cart_quantity );

		// Apply advanced cost
		foreach ( waf_get_cost_options() as $key => $name ) :
			$option     = waf_get_cost_option( $key );
			$fee_amount = $option->apply_cost_option_cost( $fee_amount, $this->post_id );
		endforeach;

		return apply_filters( 'woocommerce_advanced_fees_get_fee_amount', $fee_amount, $this->post_id );
	}


	/**
	 * Get fee tax status.
	 *
	 * Get the fee tax status.
	 *
	 * @since 1.2.1
	 *
	 * @return string|bool Tax class or false when fee doesn't exist.
	 */
	public function get_tax_class() {

		$fee_tax_class = get_post_meta( $this->post_id, 'fee_tax_class', true );

		if ( 'not_taxable' == $fee_tax_class ) :
			$fee_tax_class = false;
		endif;

		return apply_filters( 'woocommerce_advanced_fees_get_fee_tax_class', $fee_tax_class, $this->post_id );

	}


	/**
	 * Get the fee conditions.
	 *
	 * Get the conditions related to the fee.
	 *
	 * @since 1.2.1
	 *
	 * @return array List of fee conditions.
	 */
	public function get_conditions() {
		return get_post_meta( $this->post_id, 'conditions', true );;
	}


	/**
	 * Check if conditions match.
	 *
	 * Return whether or not the fee conditions are being matched.
	 *
	 * @since 1.2.1
	 *
	 * @return bool True when the conditions match, false otherwise.
	 */
	public function match_conditions() {
		return wpc_match_conditions( $this->get_conditions(), array( 'id' => $this->get_id()) );
	}

}
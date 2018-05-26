<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get fees.
 *
 * Get a list of all the fees that are set.
 *
 * @since 1.0.0
 * @since 1.1.6 - Add $args parameter.
 *
 * @param  array $args List of arguments to merge with the default args.
 * @return array       List of 'advanced_fee' posts.
 */
function waf_get_fee_posts( $args = array() ) {

	$query_args = wp_parse_args( $args, array(
		'post_type'              => 'advanced_fee',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'orderby'                => 'menu_order',
		'order'                  => 'ASC',
		'update_post_term_cache' => false,
		'no_found_rows'          => true,
	) );
	$fee_query  = new WP_Query();
	$fees       = $fee_query->query( $query_args );

	return apply_filters( 'woocommerce_advanced_fees_get_fees', $fees );

}


/**
 * Apply fee amount.
 *
 * Calculate and apply a fee amount based on the $applying_fee_amount.
 * This function calculated the percentage/cost per quantity etc.
 *
 * @since 1.0.0
 *
 * @param  string $fee_amount Fee to calculate.
 * @param  int $quantity Quantity to multiply with when there's a * in the amount.
 * @param float $group_subtotal A group (such as category, class, product) subtotal amount. Used to be able to take a percentage off.
 * @return float New fee amount.
 */
function waf_apply_fee_amount( $fee_amount, $quantity = 1, $group_subtotal = 0.00 ) {

	$cart_subtotal = apply_filters( 'woocommerce_advanced_fees_get_fee_amount_subtotal', ( WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() ) );

	$raw_fee_amount = $fee_amount;
	$fee_amount = str_replace( array( '-', '*', '%' ), '', $fee_amount );
	$fee_amount = str_replace( ',', '.', $fee_amount );

	// Group subtotal percentage
	if ( strstr( $raw_fee_amount, '%%' ) ) :
		$percent = str_replace( '%%', '', $fee_amount );
		$fee_amount = ( ( $group_subtotal / 100 ) * $percent );

	// Cart subtotal percentage
	elseif ( strstr( $raw_fee_amount, '%' ) ) :
		$percent = str_replace( '%', '', $fee_amount );
		$fee_amount = ( $cart_subtotal / 100 ) * $percent;
	endif;

	// Multiply
	if ( strstr( $raw_fee_amount, '*' ) ) :
		$fee_amount = $fee_amount * $quantity;
	endif;

	// 'per X' (rounding up) - EXPERIMENTAL (subject to change or even removal)
	if ( strstr( $raw_fee_amount, '/' ) ) :
		$per  = str_replace( '/', '', strstr( $fee_amount, '/' ) );
		$fee_amount = substr( $fee_amount, 0, strpos( $fee_amount, '/' ) ); // Get the cost before the '/'
		$fee_amount = $fee_amount * ceil( $quantity / $per );
	endif;

	// 'per X' (rounding down) - EXPERIMENTAL (subject to change or even removal)
	if ( strstr( $raw_fee_amount, '\\' ) ) :
		$per = str_replace( '\\', '', strstr( $fee_amount, '\\' ) );
		$fee_amount = substr( $fee_amount, 0, strpos( $fee_amount, '\\' ) );  // Get the cost before the '\'
		$fee_amount = $fee_amount * floor( $quantity / $per );
	endif;

	// Negative
	if ( strstr( $raw_fee_amount, '-' ) ) :
		$fee_amount = - $fee_amount;
	endif;

	return (float) $fee_amount;

}


/**
 * Apply fees.
 *
 * Apply the fees to the cart/order after they are cleared by the conditions.
 * This is THE function.
 *
 * @since 1.0.0
 *
 * @param $cart \WC_Cart
 */
function waf_apply_fees( $cart ) {

	// Bail if cart not ready or Fees are not enabled.
	if ( ! WC()->cart || 'no' == get_option( 'enable_woocommerce_advanced_fees', 'yes' ) ) :
		return;
	endif;

	$fees = waf_get_fee_posts();
	foreach ( $fees as $fee ) :
		$waf_fee = new WAF_Fee( $fee->ID );

		if ( $waf_fee->match_conditions() ) :

			if ( $waf_fee->get_title() && $waf_fee->get_fee_amount() ) :
				WC()->cart->fees_api()->add_fee( array(
					'id'        => $waf_fee->get_id(),
					'name'      => $waf_fee->get_title(),
					'amount'    => (float) $waf_fee->get_fee_amount(),
					'taxable'   => ( false !== $waf_fee->get_tax_class() ? true : false ),
					'tax_class' => $waf_fee->get_tax_class(),
				) );
			endif;

		endif;

	endforeach;

}
add_action( 'woocommerce_cart_calculate_fees', 'waf_apply_fees' );


/**
 * Get available conditions.
 *
 * Get a list of the available conditions for the plugin.
 *
 * @since 1.2.1
 *
 * @return array List of available conditions.
 */
function waf_get_available_conditions() {

	$conditions = array(
		__( 'Cart', 'woocommerce-advanced-fees' ) => array(
			'subtotal'                => __( 'Subtotal', 'woocommerce-advanced-fees' ),
			'subtotal_ex_tax'         => __( 'Subtotal ex. taxes', 'woocommerce-advanced-fees' ),
			'tax'                     => __( 'Tax', 'woocommerce-advanced-fees' ),
			'quantity'                => __( 'Quantity', 'woocommerce-advanced-fees' ),
			'contains_product'        => __( 'Contains product', 'woocommerce-advanced-fees' ),
			'coupon'                  => __( 'Coupon', 'woocommerce-advanced-fees' ),
			'weight'                  => __( 'Weight', 'woocommerce-advanced-fees' ),
			'contains_shipping_class' => __( 'Contains shipping class', 'woocommerce-advanced-fees' ),
			'contains_category'       => __( 'Contains category', 'woocommerce-advanced-fees' ),
			'payment_gateway'         => __( 'Payment gateway', 'woocommerce-advanced-fees' ),
			'shipping_method'         => __( 'Shipping Method', 'woocommerce-advanced-fees' ),
		),
		__( 'User Details', 'woocommerce-advanced-fees' ) => array(
			'zipcode' => __( 'Zipcode', 'woocommerce-advanced-fees' ),
			'city'    => __( 'City', 'woocommerce-advanced-fees' ),
			'state'   => __( 'State', 'woocommerce-advanced-fees' ),
			'country' => __( 'Country', 'woocommerce-advanced-fees' ),
			'role'    => __( 'User role', 'woocommerce-advanced-fees' ),
		),
	);
	$conditions = apply_filters( 'woocommerce_advanced_fees_conditions', $conditions );

	return $conditions;

}


/**
 * Trigger update on payment change.
 *
 * Make sure the checkout updates on payment gateway change. This ensures
 * that the 'payment gateway' condition works.
 *
 * @since 1.0.0
 * @since 1.1.2 - More specific selector, prevents unintended refreshes with some gateways.
 */
function waf_trigger_checkout_update_on_payment_method_change() {
	if ( is_checkout() ) {
		wc_enqueue_js( "
			$( document.body ).on( 'change', '.payment_methods > li > input[type=radio]', function() {
				$( document.body ).trigger( 'update_checkout' );
			} );"
		);
	}
}
add_action( 'init', 'waf_trigger_checkout_update_on_payment_method_change' );


/**************************************************************
 * Cost options
 *************************************************************/


/**
 * Get cost options.
 *
 * Get the available/registered cost options.
 *
 * @since 1.0.0
 *
 * @return array List of available cost options.
 */
function waf_get_cost_options() {

	require_once plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . 'includes/cost-options/class-waf-cost-option-abstract.php';
	require_once plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . 'includes/cost-options/class-waf-cost-option-cost-per-weight.php';
	require_once plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . 'includes/cost-options/class-waf-cost-option-cost-per-shipping-class.php';
	require_once plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . 'includes/cost-options/class-waf-cost-option-cost-per-category.php';
	require_once plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . 'includes/cost-options/class-waf-cost-option-cost-per-product.php';

	$registered_cost_options = apply_filters( 'woocommerce_advanced_fees_registered_cost_options', array(
		'cost_per_weight' => new WAF_Cost_Option_Cost_Per_Weight(),
		'cost_per_shipping_class' => new WAF_Cost_Option_Cost_Per_Shipping_Class(),
		'cost_per_category' => new WAF_Cost_Option_Cost_Per_Category(),
		'cost_per_product' => new WAF_Cost_Option_Cost_Per_Product(),
	) );

	return $registered_cost_options;

}


/**
 * Get cost option object.
 *
 * Get the object for a cost option type.
 *
 * @since 1.1.6
 *
 * @param  string $type Cost option type to get.
 * @return WAF_Cost_Option_Abstract
 */
function waf_get_cost_option( $type ) {
	$cost_options = waf_get_cost_options();

	if ( isset( $cost_options[ $type ] ) ) {
		return $cost_options[ $type ];
	}

	return false;
}


/**
 * Check if a fee is from Advanced Fees.
 *
 * @since 1.3.0
 *
 * @param $fee
 * @return bool
 */
function is_waf_fee( $fee ) {
	if ( is_object( $fee ) && is_numeric( $fee->id ) && $post = get_post( $fee->id ) ) {
		return $post->post_type === 'advanced_fee';
	}

	return false;
}

/**************************************************************
 * Backwards compatibility
 *************************************************************/

/**
 * Add filter for condition values for backwards compatibility.
 *
 * @since 1.2.1
 */
function waf_add_bc_filter_condition_values( $condition ) {
	return apply_filters( 'woocommerce_advanced_fees_condition_values', $condition );
}
add_action( 'wp-conditions\condition', 'waf_add_bc_filter_condition_values' );


/**
 * Add the filters required for backwards-compatibility for the matching functionality.
 *
 * @since 1.2.1
 */
function waf_add_bc_filter_condition_match( $match, $condition, $operator, $value ) {
	if ( has_filter( 'woocommerce_advanced_fees_match_condition_' . $condition ) ) {
		$match = apply_filters( 'woocommerce_advanced_fees_match_condition_' . $condition, $match, $operator, $value );
	}

	return $match;
}
add_action( 'wp-conditions\condition\match', 'waf_add_bc_filter_condition_match', 10, 4 );

/**
 * Add condition descriptions of custom conditions.
 *
 * @since 1.2.1
 */
function waf_add_bc_filter_condition_descriptions( $descriptions ) {
	return apply_filters( 'woocommerce_advanced_fees_description', $descriptions );
}
add_filter( 'wp-conditions\condition_descriptions', 'waf_add_bc_filter_condition_descriptions' );

/**
 * Add custom field BC.
 *
 * @since 1.2.1
 */
function waf_add_bc_action_custom_fields( $type, $args ) {
	if ( has_action( 'woocommerce_advanced_fees_condition_value_field_type_' . $type ) ) {
		do_action( 'woocommerce_advanced_fees_condition_value_field_type_' . $args['type'], $args );
	}
}
add_action( 'wp-conditions\html_field_hook', 'waf_add_bc_action_custom_fields', 10, 2 );

/**************************************************************
 * Deprecated
 *************************************************************/

/**
 * Get fee title.
 *
 * Get the title of the fee based on the Fee ID.
 *
 * @since 1.0.0
 * @deprecated 1.2.1
 *
 * @param  int         $fee_id ID of the fee to get the amount for.
 * @return string|bool         Title of the fee or false when fee doesn't exist.
 */
function waf_get_fee_title( $fee_id ) {
	_deprecated_function( __FUNCTION__, '1.2.1', 'WAF_Fee->get_title()' );

	$fee = new WAF_Fee( $fee_id );
	return $fee->get_title();

}


/**
 * Get fee amount.
 *
 * Get the amount of the fee based on the Fee ID and the
 * cart contents total.
 *
 * @since 1.0.0
 * @deprecated 1.2.1
 *
 * @param  int        $fee_id ID of the fee to get the amount for.
 * @return float|bool         Amount of the fee or false when fee doesn't exist.
 */
function waf_get_fee_amount( $fee_id ) {
	_deprecated_function( __FUNCTION__, '1.2.1', 'WAF_Fee->get_fee_amount()' );

	$fee = new WAF_Fee( $fee_id );
	return $fee->get_fee_amount();

}


/**
 * Get fee tax status.
 *
 * Get the fee tax status.
 *
 * @since 1.0.0
 * @deprecated 1.2.1
 *
 * @param  int         $fee_id ID of the fee to get the amount for.
 * @return string|bool         Tax class or false when fee doesn't exist.
 */
function waf_get_fee_tax_class( $fee_id ) {
	_deprecated_function( __FUNCTION__, '1.2.1', 'WAF_Fee->get_tax_class()' );

	$fee = new WAF_Fee( $fee_id );
	return $fee->get_tax_class();

}


/**
 * Match conditions.
 *
 * Check if conditions match, if all conditions in one condition group
 * matches it will return TRUE and the fee will be applied.
 *
 * @since 1.0.0
 * @deprecated 1.2.1
 *
 * @param  array $condition_groups List of condition groups containing their conditions.
 * @return BOOL                    TRUE if all the conditions in one of the condition groups matches true.
 */
function waf_match_conditions( $condition_groups = array() ) {
	_deprecated_function( __FUNCTION__, '1.2.1', 'wpc_match_conditions()' );
	return wpc_match_conditions( $condition_groups );
}

/**
 * Get cost option class name.
 *
 * Get the class name of a cost option based on the cost type (ID).
 *
 * @since 1.0.0
 * @deprecated 1.2.1
 *
 * @param  string $type Cost option type (same as ID).
 * @return mixed        Cost option class name when it exists, false otherwise.
 */
function waf_cost_option_class_name_from_type( $type ) {
	_doing_it_wrong( __FUNCTION__, 'from version 1.2.1 waf_get_cost_options() will return a associative array with the cost option object. Using this function should no longer be needed', '1.2.1' );
	$class_name = 'WAF_Cost_Option_' . implode( '_', array_map( 'ucfirst', explode( '_', $type ) ) );

	if ( ! class_exists( $class_name ) ) :
		return false;
	endif;

	return $class_name;

}

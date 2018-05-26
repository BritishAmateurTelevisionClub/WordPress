<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Check if WooCommerce is active
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
if ( ! is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) && ! class_exists( 'WC_Subscriptions' ) ) {
	return;
}


/**
 * Show custom settings.
 *
 * @param $post_id
 */
function waf_wcs_show_recurring_fee_settings( $post_id ) {

	$selected = get_post_meta( $post_id, '_recurring', true );
	?><p class='waf-option'>

		<label for='fee_tax_class'><?php _e( 'Recurring', 'woocommerce-advanced-fees' ); ?></label>
		<select name='_recurring' id="recurring-fee" style='width: 189px;'>
			<option value='yes' <?php selected( $selected ); ?>><?php _e( 'Yes', 'woocommerce-advanced-fees' ); ?></option>
			<option value='no' <?php selected( ! $selected ); ?>><?php _e( 'No', 'woocommerce-advanced-fees' ); ?></option>
		</select>

	</p><?php

}
add_action( 'woocommerce_advanced_fees_after_meta_box_settings', 'waf_wcs_show_recurring_fee_settings' );

/**
 * Save the custom settings.
 *
 * @param $post_id
 */
function waf_wcs_save_recurring_settings( $post_id ) {
	update_post_meta( $post_id, '_recurring', ( isset( $_POST['_recurring'] ) && $_POST['_recurring'] === 'yes' ) );
}
add_action( 'woocommerce_advanced_fees_save_meta_boxes', 'waf_wcs_save_recurring_settings' );


/**
 * Determine is a fee is recurring.
 *
 * @param $is_recurring
 * @param $fee
 * @param $cart
 * @return bool
 */
function waf_wcs_is_recurring_fee( $is_recurring, $fee, $cart ) {
	if ( is_waf_fee( $fee ) && get_post_meta( $fee->id, '_recurring', true ) ) {
		$is_recurring = true;
	}

	return $is_recurring;
}
add_filter( 'woocommerce_subscriptions_is_recurring_fee', 'waf_wcs_is_recurring_fee', 10, 3 );
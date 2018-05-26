<?php
/**
 * Plugin Name: 	WooCommerce Redirect Thank You
 * Plugin URI: 		https://shopplugins.com/plugins/woocommerce-redirect-thank-you/
 * Description: 	This plugin allows the WooCommerce store owner to redirect the customer to a different Thank You page based on products purchased.
 * Author: 			Shop Plugins
 * Author URI: 		https://shopplugins.com
 * Version: 		1.0.7
 * Text Domain: 	woocommerce-redirect-thank-you
 * Domain Path: 	/languages/
*/
/**
 * Copyright 2017  Daniel Espinoza  (email: daniel@shopplugins.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

define( 'WC_REDIRECT_THANK_YOU_FILE', plugin_basename( __FILE__ ) );
define( 'WC_REDIRECT_THANK_YOU_SHOP_PLUGINS_URL', 'https://shopplugins.com' );
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/includes/updater/EDD_SL_Plugin_Updater.php' );
}
function edd_sl_woocommerce_redirect_thank_you_updater() {
	$license_key = trim( get_option( 'woocommerce_redirect_thank_you_sl_key' ) );
	$edd_updater = new EDD_SL_Plugin_Updater( WC_REDIRECT_THANK_YOU_SHOP_PLUGINS_URL, __FILE__, array(
			'version' 	=> '1.0.7', 							// current version number
			'license' 	=> $license_key, 						// license key (used get_option above to retrieve from DB)
			'item_name' => 'WooCommerce Redirect Thank You', 	// name of this plugin
			'author' 	=> 'Shop Plugins'						// author of this plugin
		)
	);
}
add_action( 'admin_init', 'edd_sl_woocommerce_redirect_thank_you_updater', 0 );

// Check items in order to see
add_filter('woocommerce_get_checkout_order_received_url','growdev_get_checkout_order_received_url', 10, 2);
/**
 * Filter the redirect URL and return the custom thank you page URL if a product has it set.
 *
 * @param string $order_received_url
 * @param WC_Order $order
 * @return string
 */
function growdev_get_checkout_order_received_url( $order_received_url, $order ) {

	$items = $order->get_items();
	$redirect_page_id = 0;

	if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
		$order_id  = $order->get_id();
		$order_key = $order->get_order_key();
	} else {
		$order_id  = $order->id;
		$order_key = $order->order_key;
	}

	foreach ( $items as $item ) {
		$_id = absint( get_post_meta( $item['product_id'], '_redirect_page_id', true ) );
		if ( 0 < $_id )
			$redirect_page_id = $_id;
	}

	if ( 0 < $redirect_page_id ) {
		$order_received_url = add_query_arg( array( 'order' => $order_id, 'key'=> $order_key ),  get_permalink( $redirect_page_id ) );
	} elseif ( 0 < $global_thank_you_page_id = get_option( 'woocommerce_redirect_thank_you_global', false ) ) {
		$order_received_url = add_query_arg( array( 'order'=> $order_id, 'key' => $order_key ), get_permalink( $global_thank_you_page_id ) );
	}

	return $order_received_url;
}


// Add meta box to the product page for the Custom Thank You page
add_action( 'admin_init', 'growdev_include_post_type_handlers');
/**
 * Include the meta boxes if in the WordPress admin
 *
 * @return void
 */
function growdev_include_post_type_handlers (){
	include( 'includes/meta-boxes/class-wcrty-meta-box-redirect.php' );
	include( 'includes/class-wcrty-admin-meta-boxes.php' );
}


// Define shortcode to put on pages to display the order details
// [growdev_order_details]
add_shortcode( 'growdev_order_details' , 'growdev_shortcode_order_details' );
/**
 * Shortcode definition to output the order details on the custom thank you pages.
 *
 * @param $atts
 * @return string
 */
function growdev_shortcode_order_details( $atts ) {

	if ( ! is_admin() ) {
		wc_print_notices();
		$order = false;

		$order_id = empty( $_GET['order'] ) ? '' : wc_clean( $_GET['order'] );

		// Get the order
		$order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $order_id ) );
		$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

		if ( $order_id > 0 ) {
			$order = new WC_Order( $order_id );
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				if ( $order->get_order_key() != $order_key ) {
					unset( $order );
				}
			} else {
				if ( $order->order_key != $order_key ) {
					unset( $order );
				}
			}
		}

		// Empty awaiting payment session
		unset( WC()->session->order_awaiting_payment );

		// Empty cart
		// this is normally called in wc-cart-functions.php, but we are bypassing the
		// 'order-received' endpoint so need to do this ourselves.
		WC()->cart->empty_cart();

		// Payment gateways are not auto loaded yet so need to do that now
		WC()->payment_gateways();

		ob_start();
		if ( isset( $order ) ) {
			wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
		}

		return ob_get_clean();
	}
}

if ( is_admin() ) {

	/**
	 * Admin settings
	 */
	require_once 'includes/admin/class-wcrty-admin.php';
	$admin = new WCRTY_Admin();

}

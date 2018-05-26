<?PHP
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCRTY_Admin.
 *
 * Admin settings class.
 *
 * @class       WCRTY_Admin
 * @version     1.0.0
 * @author      Shop Plugins
 */
class WCRTY_Admin {

	/**
	 * __construct function.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->hooks();

	}


	/**
	 * Class hooks.
	 *
	 * All initial hooks used in this class.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		// Plugins page links
		add_filter( 'plugin_action_links_' . WC_REDIRECT_THANK_YOU_FILE, array( $this, 'plugin_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta'), 10, 2 );

		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woocommerce_settings_tab' ), 40 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_redirect_thank_you', array( $this, 'woocommerce_settings_page' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_redirect_thank_you', array( $this, 'woocommerce_update_options' ) );

		/** License actions */
		// License field
		add_action( 'woocommerce_admin_field_wcrty_license', array( $this, 'generate_wcrty_license_html' ) );

		// Activate/deactivate license action
		add_action( 'admin_init', array( $this, 'activate_deactivate_license' ) );

		// Delete status on option change
		add_action( 'pre_update_option_woocommerce_redirect_thank_you_sl_key', array( $this, 'update_license_status_on_key_change' ), 10, 2 );
		/** End license actions */

	}


	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param $tabs
	 * @return array All WC settings tabs including newly added.
	 */
	public function woocommerce_settings_tab( $tabs) {

		$tabs['redirect_thank_you'] = __( 'Thank You', 'woocommerce-redirect-thank-you' );

		return $tabs;

	}


	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_get_settings() {

		$settings = apply_filters( 'woocommerce_redirect_thank_you_data_settings', array(

			array(
				'title' 	=> __( 'WooCommerce Redirect Thank You settings', 'woocommerce-redirect-thank-you' ),
				'type' 		=> 'title',
				'desc' 		=> '',
				'id' 		=> 'wcrty_general'
			),

			array(
				'title'   	=> __( 'Global Thank You Page', 'woocommerce-redirect-thank-you' ),
				'desc' 	  	=> __( 'Use this setting to override the WooCommerce order-received endpoint.', 'woocommerce-redirect-thank-you' ),
				'id' 	  	=> 'woocommerce_redirect_thank_you_global',
				'type' 	  	=> 'single_select_page',
				'default' 	=> '',
				'class'     => 'wc-enhanced-select-nostd',
				'css'       => 'min-width:300px;',
				'desc_tip'  => true,
			),

			array(
				'title'   	=> __( 'License key', 'woocommerce-redirect-thank-you' ),
				'desc' 	  	=> '',
				'id' 	  	=> 'woocommerce_redirect_thank_you_sl_key',
				'default' 	=> '',
				'type' 	  	=> 'wcrty_license',
				'autoload'	=> false
			),

			array(
				'type' 		=> 'sectionend',
				'id' 		=> 'wcrty_general'
			)

		) );



		return $settings;

	}


	/**
	 * License field.
	 *
	 * Print the HTML formatted license field.
	 *
	 * @since 1.0.0
	 */
	public function generate_wcrty_license_html() {

		$license 	= get_option( 'woocommerce_redirect_thank_you_sl_key' );
		$status 	= get_option( 'woocommerce_redirect_thank_you_sl_status' );

		?><tr valign='top'>

			<th scope='row' class='titledesc'>
				<label for='woocommerce_redirect_thank_you_sl_key'><?php _e( 'License key', 'woocommerce-redirect-thank-you' ); ?></label>
			</th>
			<td class='forminp forminp-text'>
				<input name='woocommerce_redirect_thank_you_sl_key' id='woocommerce_redirect_thank_you_sl_key' type='text' style='' value='<?php echo $license; ?>' class=''>
				<span class='description'><?php
					_e( 'Enter the license key, found in your <a target="_blank" href="https://shopplugins.com/account/">Shop Plugins dashboard</a>.' );
				?></span>
			</td>

		</tr><?php

		 if ( false !== $license ) :

			wp_nonce_field( 'wcrty_nonce_action', 'wcrty_nonce' );
			?><tr valign='top'>

				<th scope='row' valign='top'><?php
					_e('License status');
				?></th>
				<td><?php
					if ( $status !== false && $status == 'valid' ) :
/* -- Deactivate button
<input type='submit' class='button-secondary' name='wcrty_license_deactivate' style='vertical-align:middle; margin-right: 10px;'
							value='<?php _e( 'Deactivate License', 'woocommerce-redirect-thank-you' ); ?>'/>
*/
						?><span style='color:green;'><?php _e( 'Active', 'woocommerce-redirect-thank-you' ); ?></span><?php
					else :
						?><input type='submit' class='button-secondary' name='wcrty_license_activate' style='vertical-align:middle; margin-right: 10px;'
							value='<?php _e( 'Activate License', 'woocommerce-redirect-thank-you' ); ?>'/>
						<span style='color:#A00;'><?php _e( 'License not yet activated', 'woocommerce-redirect-thank-you' ); ?></span><?php
					endif;
				?></td>

			</tr><?php

		 endif;

	}


	/**
	 * Delete status.
	 *
	 * Delete the license status when the license key changes. This
	 * forces the user to re-activate the license.
	 *
	 * @since 1.0.0
	 *
	 * @param 	mixed 	$new_value 	New value to be saved.
	 * @param 	mixed	$old_value	Current value, about to be overwritten
	 * @return	mixed				The new value.
	 */
	public function update_license_status_on_key_change( $new_value, $old_value ) {

		if ( $old_value && $old_value != $new_value ) :
			delete_option( 'woocommerce_redirect_thank_you_sl_status' );
		endif;

		return $new_value;

	}


	/**
	 * Activate/Deactivate license.
	 *
	 * Send a API request to activate/deactivate the current site.
	 *
	 * @since 1.0.0
	 */
	public function activate_deactivate_license() {

		// Bail if not activating license
		if ( ! isset( $_POST['wcrty_license_activate'] ) && ! isset( $_POST['wcrty_license_deactivate'] ) ) :
			return;
		endif;

		// Verify nonce
		if ( ! isset( $_POST['wcrty_nonce'] ) || ! wp_verify_nonce( $_POST['wcrty_nonce'], 'wcrty_nonce_action' ) ) :
			return;
		endif;


		// data to send in our API request
		$api_params = array(
			'edd_action'	=> isset( $_POST['wcrty_license_activate'] ) ? 'activate_license' : 'deactivate_license',
			'license'		=> trim( get_option( 'woocommerce_redirect_thank_you_sl_key', '' ) ),
			'item_name'		=> urlencode( 'WooCommerce Redirect Thank You' ),
			'url'			=> home_url(),
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WC_REDIRECT_THANK_YOU_SHOP_PLUGINS_URL ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) :
			return false;
		endif;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( 'woocommerce_redirect_thank_you_sl_status', $license_data->license );

	}


	/**
	 * Settings page content.
	 *
	 * Output settings page content via WooCommerce output_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_settings_page() {

		WC_Admin_Settings::output_fields( $this->woocommerce_get_settings() );

	}

	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_update_options() {

		WC_Admin_Settings::save_fields( $this->woocommerce_get_settings() );

	}

	/**
	 * Plugin page links
	 *
	 * @param $links
	 * @return array
	 */
	function plugin_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=redirect_thank_you' ) . '">' . __( 'Settings', 'woocommerce-redirect-thank-you' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	public function add_plugin_row_meta( $links, $file ) {

		if ( $file == WC_REDIRECT_THANK_YOU_FILE ) {

			$links[] = '<a href="https://shopplugins.com/support">' . __( 'Support', 'woocommerce-redirect-thank-you' ) . '</a>';
			$links[] = '<a href="http://docs.shopplugins.com/article/15-woocommerce-redirect-thank-you" target="_blank">' . __( 'Docs', 'woocommerce-redirect-thank-you' ) . '</a>';
			$links[] = '<a href="https://shopplugins.com/plugins/category/woocommerce/" target="_blank">' . __( 'WooCommerce Plugins', 'woocommerce-redirect-thank-you' ) . '</a>';

		}

		return $links;

	}

}

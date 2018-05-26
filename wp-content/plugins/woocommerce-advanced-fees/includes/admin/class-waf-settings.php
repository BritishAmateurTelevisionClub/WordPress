<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin settings.
 *
 * Handle functions for admin settings page.
 *
 * @author		Jeroen Sormani
 * @version		1.0.0
 */
class WAF_Settings {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'settings_tab' ), 60 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_advanced_fees', array( $this, 'settings_page' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_advanced_fees', array( $this, 'update_options' ) );

		// Table field type
		add_action( 'woocommerce_admin_field_advanced_fees_table', array( $this, 'generate_table_field' ) );

	}


	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the 'Fees' settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $tabs Default tabs used in WC.
	 * @return array       All WC settings tabs including newly added.
	 */
	public function settings_tab( $tabs ) {

		$tabs['advanced_fees'] = __( 'Fees', 'woocommerce-advanced-fees' );

		return $tabs;

	}


	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = apply_filters( 'woocommerce_advanced_fees_settings', array(

			array(
				'title' => __( 'Advanced Fees', 'woocommerce-advanced-fees' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'waf_general',
			),

			array(
				'title'    => __( 'Enable/Disable', 'woocommerce-advanced-fees' ),
				'desc'     => __( 'When disabled you will still be able to manage fees, but none will be applied to customers.', 'woocommerce-advanced-fees' ),
				'id'       => 'enable_woocommerce_advanced_fees',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false
			),

			array(
				'title' => __( 'Advanced Fees', 'woocommerce-advanced-fees' ),
				'type'  => 'advanced_fees_table',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'waf_end'
			),

		) );

		return $settings;

	}


	/**
	 * Settings page content.
	 *
	 * Output settings page content via WooCommerce output_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function settings_page() {

		WC_Admin_Settings::output_fields( $this->get_settings() );

	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function update_options() {

		WC_Admin_Settings::save_fields( $this->get_settings() );

	}


	/**
	 * Table field type.
	 *
	 * Load and render table as a field type.
	 *
	 * @return string
	 */
	public function generate_table_field() {

		// Fees table
		require_once plugin_dir_path( __FILE__ ) . 'views/html-advanced-fees-table.php';

	}


}

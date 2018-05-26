<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Admin class.
 *
 * Handle all admin related functions.
 *
 * @author     	Jeroen Sormani
 * @version		1.0.0
 */
class WAF_Admin {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Initialize class
		add_action( 'admin_init', array( $this, 'init' ) );

		// Auto updater function
		add_action( 'admin_init', array( $this, 'auto_updater' ) );

	}


	/**
	 * Initialise hooks.
	 *
	 * @since 1.1.6
	 */
	public function init() {

		// Add to WC Screen IDs to load scripts.
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_screen_ids' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Keep WC menu open while in post edit screen
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

		global $pagenow;
		if ( 'plugins.php' == $pagenow ) :
			add_filter( 'plugin_action_links_' . plugin_basename( WooCommerce_Advanced_Fees()->file ), array( $this, 'add_plugin_action_links' ), 10, 2 );
		endif;

	}


	/**
	 * Screen IDs.
	 *
	 * Add CPT to the screen IDs so the WooCommerce scripts are loaded.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $screen_ids List of existing screen IDs.
	 * @return array             List of modified screen IDs.
	 */
	public function add_screen_ids( $screen_ids ) {

		$screen_ids[] = 'advanced_fee';

		return $screen_ids;

	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue style and java scripts.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Style script
		wp_register_style( 'woocommerce-advanced-fees', plugins_url( 'assets/admin/css/woocommerce-advanced-fees.min.css', WooCommerce_Advanced_Fees()->file ), array(), WooCommerce_Advanced_Fees()->version );

		// Javascript
		wp_register_script( 'woocommerce-advanced-fees', plugins_url( 'assets/admin/js/woocommerce-advanced-fees' . $suffix . '.js', WooCommerce_Advanced_Fees()->file ), array( 'jquery', 'jquery-ui-sortable' ), WooCommerce_Advanced_Fees()->version, true );

		// Only load scripts on relevant pages
		if (
			( isset( $_REQUEST['post'] ) && 'advanced_fee' == get_post_type( $_REQUEST['post'] ) ) ||
			( isset( $_REQUEST['post_type'] ) && 'advanced_fee' == $_REQUEST['post_type'] ) ||
			( isset( $_REQUEST['tab'] ) && 'advanced_fees' == $_REQUEST['tab'] )
		) :

			wp_localize_script( 'wp-conditions', 'wpc2', array(
				'action_prefix' => 'waf_',
			) );

			wp_enqueue_style( 'woocommerce-advanced-fees' );
			wp_enqueue_script( 'woocommerce-advanced-fees' );
			wp_enqueue_script( 'wp-conditions' );

			wp_dequeue_script( 'autosave' );

		endif;

	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.0
	 */
	public function menu_highlight() {

		global $parent_file, $submenu_file, $post_type;

		if ( 'advanced_fee' == $post_type ) :
			$parent_file  = 'woocommerce';
			$submenu_file = 'wc-settings';
		endif;

	}


	/**
	 * Plugin action links.
	 *
	 * Add links to the plugins.php page below the plugin name
	 * and besides the 'activate', 'edit', 'delete' action links.
	 *
	 * @since 1.1.8
	 *
	 * @param  array  $links List of existing links.
	 * @param  string $file  Name of the current plugin being looped.
	 * @return array         List of modified links.
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( $file == plugin_basename( WooCommerce_Advanced_Fees()->file ) ) :
			$links = array_merge( array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=wc-settings&tab=advanced_fees' ) ) . '">' . __( 'Settings', 'woocommerce-advanced-fees' ) . '</a>'
			), $links );
		endif;

		return $links;

	}


	/**
	 * Updater.
	 *
	 * Function to get automatic updates.
	 *
	 * @since 1.0.0
	 */
	public function auto_updater() {

		// Updater
		if ( ! class_exists( '\JeroenSormani\WP_Updater\WPUpdater' ) ) {
			require plugin_dir_path( WooCommerce_Advanced_Fees()->file ) . '/libraries/wp-updater/wp-updater.php';
		}
		new \JeroenSormani\WP_Updater\WPUpdater( array(
			'file'    => WooCommerce_Advanced_Fees()->file,
			'name'    => 'WooCommerce Advanced Fees',
			'version' => WooCommerce_Advanced_Fees()->version,
			'api_url' => 'https://aceplugins.com',
		) );

	}


}

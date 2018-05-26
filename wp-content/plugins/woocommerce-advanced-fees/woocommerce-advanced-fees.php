<?php
/**
 * Plugin Name: 	Advanced Fees for WooCommerce
 * Plugin URI: 		http://jeroensormani.com/plugins/advanced-fees-woocommerce/
 * Description: 	Advanced Fees for WooCommerce allows you to add extra fees to a customers' order via <strong>conditional logic!</strong>
 * Version: 		1.3.0
 * Author: 			Jeroen Sormani
 * Author URI: 		http://jeroensormani.com/
 * Text Domain: 	woocommerce-advanced-fees
 * WC requires at least: 3.2.0
 * WC tested up to:      3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Advanced_Fees_for_WooCommerce.
 *
 * Main class, add filters and handling all other files.
 *
 * @class		Advanced_Fees_for_WooCommerce
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class Advanced_Fees_for_WooCommerce {


	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var string $version Plugin version number.
	 */
	public $version = '1.3.0';


	/**
	 * File.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin __FILE__ path.
	 */
	public $file = __FILE__;


	/**
	 * Instance of Advanced_Fees_for_WooCommerce.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of the plugin.
	 */
	private static $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Check if WooCommerce is active
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! function_exists( 'WC' ) ) {
			return;
		}

		// Initialize plugin parts
		$this->init();

		do_action( 'woocommerce_advanced_fees_init' );

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 *
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		if ( version_compare( PHP_VERSION, '5.3', 'lt' ) ) {
			return add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
		}

		require_once plugin_dir_path( __FILE__ ) . 'includes/class-waf-fee.php';
		require_once plugin_dir_path( __FILE__ ) . '/libraries/wp-conditions/functions.php';
		require_once plugin_dir_path( __FILE__ ) . '/integrations/woocommerce-subscriptions.php';

		/**
		 * Post Type class
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-waf-post-type.php';
		$this->post_type = new WAF_Post_Type();

		// AJAX
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) :

			/**
			 * Load ajax methods
			 */
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-waf-ajax.php';
			$this->ajax = new WAF_Ajax();

		endif;

		// Admin
		if ( is_admin() ) :

			/**
			 * Admin class.
			 */
			require_once plugin_dir_path( __FILE__ ) . '/includes/admin/class-waf-admin.php';
			$this->admin = new WAF_Admin();

			// Settings
			require_once plugin_dir_path( __FILE__ ) . '/includes/admin/class-waf-settings.php';
			$this->settings = new WAF_Settings();

		endif;

		// Include functions
		require_once plugin_dir_path( __FILE__ ) . 'includes/waf-fee-functions.php';

		// Load textdomain
		$this->load_textdomain();

	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-advanced-fees' );

		// Load textdomain
		load_textdomain( 'woocommerce-advanced-fees', WP_LANG_DIR . '/woocommerce-advanced-fees/woocommerce-advanced-fees-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-advanced-fees', false, basename( dirname( __FILE__ ) ) . '/languages' );

	}


	/**
	 * Display PHP 5.3 required notice.
	 *
	 * Display a notice when the required PHP version is not met.
	 *
	 * @since 1.0.6
	 */
	public function php_version_notice() {

		?><div class='updated'>
			<p><?php echo sprintf( __( 'Advanced Fees requires PHP 5.3 or higher and your current PHP version is %s. Please (contact your host to) update your PHP version.', 'woocommerce-advanced-fees' ), PHP_VERSION ); ?></p>
		</div><?php

	}


}


/**
 * The main function responsible for returning the Advanced_Fees_for_WooCommerce object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php WooCommerce_Advanced_Fees()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return object WooCommerce_Advanced_Fees class object.
 */
if ( ! function_exists( 'WooCommerce_Advanced_Fees' ) ) :

	function WooCommerce_Advanced_Fees() {

		return Advanced_Fees_for_WooCommerce::instance();

	}


endif;
WooCommerce_Advanced_Fees();

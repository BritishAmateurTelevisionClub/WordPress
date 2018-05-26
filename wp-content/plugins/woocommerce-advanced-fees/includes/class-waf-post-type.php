<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Post type class.
 *
 * Initialize and manage everything related to the custom post type.
 *
 * @author     	Jeroen Sormani
 * @version		1.0.0
 */
class WAF_Post_Type {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Add/save meta boxes
		add_action( 'add_meta_boxes', array( $this, 'post_type_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );

		// Edit user notices
		add_filter( 'post_updated_messages', array( $this, 'custom_post_type_messages' ) );

		// Redirect after delete
		add_action( 'load-edit.php', array( $this, 'redirect_after_trash' ) );

	}


	/**
	 * Post type.
	 *
	 * Register the 'advanced_fee' post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Advanced Fees', 'woocommerce-advanced-fees' ),
			'singular_name'      => __( 'Advanced Fee', 'woocommerce-advanced-fees' ),
			'add_new'            => __( 'Add New', 'woocommerce-advanced-fees' ),
			'add_new_item'       => __( 'Add New Advanced Fee', 'woocommerce-advanced-fees' ),
			'edit_item'          => __( 'Edit Advanced Fee', 'woocommerce-advanced-fees' ),
			'new_item'           => __( 'New Advanced Fee', 'woocommerce-advanced-fees' ),
			'view_item'          => __( 'View Advanced Fee', 'woocommerce-advanced-fees' ),
			'search_items'       => __( 'Search Advanced Fees', 'woocommerce-advanced-fees' ),
			'not_found'          => __( 'No Advanced Fees', 'woocommerce-advanced-fees' ),
			'not_found_in_trash' => __( 'No Advanced Fees found in Trash', 'woocommerce-advanced-fees' ),
		);

		register_post_type( 'advanced_fee', array(
			'label'           => 'advanced_fee',
			'show_ui'         => true,
			'show_in_menu'    => false,
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => false,
			'_builtin'        => false,
			'query_var'       => true,
			'supports'        => array( 'title' ),
			'labels'          => $labels,
		) );

	}


	/**
	 * Messages.
	 *
	 * Modify the notice messages text for the 'advanced_fee' post type.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $messages Existing list of messages.
	 * @return array           Modified list of messages.
	 */
	function custom_post_type_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['advanced_fee'] = array(
			0  => '',
			1  => __( 'Advanced Fee updated.', 'woocommerce-advanced-fees' ),
			2  => __( 'Custom field updated.', 'woocommerce-advanced-fees' ),
			3  => __( 'Custom field deleted.', 'woocommerce-advanced-fees' ),
			4  => __( 'Advanced Fee updated.', 'woocommerce-advanced-fees' ),
			5  => isset( $_GET['revision'] ) ?
				sprintf( __( 'Advanced Fee restored to revision from %s', 'woocommerce-advanced-fees' ), wp_post_revision_title( (int) $_GET['revision'], false ) )
				: false,
			6  => __( 'Advanced Fee published.', 'woocommerce-advanced-fees' ),
			7  => __( 'Advanced Fee saved.', 'woocommerce-advanced-fees' ),
			8  => __( 'Advanced Fee submitted.', 'woocommerce-advanced-fees' ),
			9  => sprintf(
				__( 'Advanced Fee scheduled for: <strong>%1$s</strong>.', 'woocommerce-advanced-fees' ),
				date_i18n( __( 'M j, Y @ G:i', 'woocommerce-advanced-fees' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Advanced Fee draft updated.', 'woocommerce-advanced-fees' ),
		);

		$permalink                     = admin_url( 'admin.php?page=wc-settings&tab=advanced_fees' );
		$overview_link                 = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'Return to overview.', 'woocommerce-advanced-fees' ) );
		$messages['advanced_fee'][1]  .= $overview_link;
		$messages['advanced_fee'][6]  .= $overview_link;
		$messages['advanced_fee'][9]  .= $overview_link;
		$messages['advanced_fee'][8]  .= $overview_link;
		$messages['advanced_fee'][10] .= $overview_link;

		return $messages;

	}


	/**
	 * Meta boxes.
	 *
	 * Add two meta boxes to the 'advanced_fee' post type.
	 *
	 * @since 1.0.0
	 */
	public function post_type_meta_box() {

		add_meta_box( 'waf_conditions', __( 'Fee conditions', 'woocommerce-advanced-fees' ), array( $this, 'render_conditions' ), 'advanced_fee', 'normal' );
		add_meta_box( 'waf_settings', __( 'Fee settings', 'woocommerce-advanced-fees' ), array( $this, 'render_settings' ), 'advanced_fee', 'normal' );

	}


	/**
	 * Render meta box.
	 *
	 * Get conditions meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_conditions() {

		// Conditions meta box
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/html-meta-box-conditions.php';

	}


	/**
	 * Render meta box.
	 *
	 * Get settings meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_settings() {

		// Settings meta box
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/html-meta-box-settings.php';

	}


	/**
	 * Save meta.
	 *
	 * Validate and save post meta. This value contains all
	 * the normal fee settings (no conditions).
	 *
	 * @since 1.0.0
	 *
	 * @param  int      $post_id ID of the post being saved.
	 * @return int|void          Post ID when failing, not returning otherwise.
	 */
	public function save_meta_boxes( $post_id ) {

		if ( ! isset( $_POST['waf_settings_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['waf_settings_meta_box_nonce'], 'waf_settings_meta_box' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return $post_id;
		}

		$posted = wp_parse_args( $_POST, array(
			'conditions'    => array(),
			'fee_title'     => '',
			'fee_amount'    => '',
			'fee_tax_class' => '',
		) );

		// Save sanitized conditions
		update_post_meta( $post_id, 'conditions', wpc_sanitize_conditions( $posted['conditions'] ) );

		// Sanitize & save settings
		update_post_meta( $post_id, 'fee_title', sanitize_text_field( $posted['fee_title'] ) );
		update_post_meta( $post_id, 'fee_amount', sanitize_text_field( $posted['fee_amount'] ) );
		update_post_meta( $post_id, 'fee_tax_class', sanitize_text_field( $posted['fee_tax_class'] ) );

		// Initiate fee cost options to ensure posted data is saved
		waf_get_cost_options();

		do_action( 'woocommerce_advanced_fees_save_meta_boxes', $post_id );

	}


	/**
	 * Redirect trash.
	 *
	 * Redirect user after trashing a custom post.
	 *
	 * @since 1.0.0
	 */
	public function redirect_after_trash() {

		$screen = get_current_screen();

		if ( 'edit-advanced_fee' == $screen->id ) :

			if ( isset( $_GET['trashed'] ) && intval( $_GET['trashed'] ) > 0 ) :

				$redirect = admin_url( '/admin.php?page=wc-settings&tab=advanced_fees' );
				wp_redirect( $redirect );
				exit();

			endif;

		endif;

	}


}

/**
 * Load condition object
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/class-waf-condition.php';

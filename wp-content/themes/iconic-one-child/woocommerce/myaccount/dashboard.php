<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<p><?php
	/* translators: 1: user display name 2: logout url */
	printf(
		__( 'Hello %1$s (not %1$s? <a href="%2$s">Sign out</a>)', 'woocommerce' ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
	);


      $user_id = get_current_user_id();
      $user = get_userdata( $user_id );

      global $wpdb;
        $level = $wpdb->get_row('SELECT level_id FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user_id . '";');
        $level_id = $level->level_id;
        $level_array = $wpdb->get_row('SELECT option_value FROM ' . $wpdb->prefix . 'options WHERE option_id="583";');
        $level_array = unserialize($level_array->option_value);


?></p>

<p><?php
	printf(
		__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a> and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
?></p>

<form>

<div class="field_wrapper my-account">

  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
    <label for="account_first_name"><?php _e( 'Membership Level', 'woocommerce' ); ?></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo $level_array[$level_id]['label']; ?>" disabled="disabled"/>
  </p>

<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
  <label for="account_first_name"><?php _e( 'Membership Number', 'woocommerce' ); ?></label>
  <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo $user->ID; ?>" disabled="disabled"/>
</p>

<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first joined">
  <label for="account_first_name"><?php _e( 'Joined date', 'woocommerce' ); ?></label>
  <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php
  global $wpdb;
  get_currentuserinfo();
    $data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user->ID . '";');
    echo $data->start_time;
  ?>" disabled="disabled"/>
</p>

<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
  <label for="account_first_name"><?php _e( 'Renewal date', 'woocommerce' ); ?></label>
  <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php
  global $wpdb;
  get_currentuserinfo();
    $data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user->ID . '";');
    echo $data->expire_time;
  ?>" disabled="disabled"/>
</p>

</div>

</form>


<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

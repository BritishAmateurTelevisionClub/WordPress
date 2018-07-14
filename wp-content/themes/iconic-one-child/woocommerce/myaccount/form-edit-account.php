<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_edit_account_form' ); ?>

<?php
      $user_id = get_current_user_id();
  ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

<div class="field_wrapper">

  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
    <label for="account_first_name"><?php _e( 'Membership Number', 'woocommerce' ); ?></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo $user->ID; ?>" disabled="disabled"/>
  </p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-last">
		<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>



      <!--<table class="form-table chat_name">
          <tr>
            <th><label for="chat_name"><?php// _e("Chat Nick Name"); ?></label></th>
            <td>
              <input type="text" name="chat_name" id="chat_name" class="regular-text"
                  value="<?php echo esc_attr( get_user_meta( $user->ID, 'chat_name', true ) ); ?>"><span class="description"><?php// _e('For future use.'); ?></span>
          </td>
          </tr>
        </table>-->

</div>

<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-wide">
	<label for="call_sign"><?php _e( 'Call Sign', 'woocommerce' ); ?></label>
	<input type="text" name="call_sign" class="woocommerce-Input woocommerce-Input--email input-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'call_sign', true ) ); ?>"/>
</p>


	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<fieldset>
		<h1><?php _e( 'Password change', 'woocommerce' ); ?></h1>
		
		<p>
			Note - if you cut and paste your current password from an email program,  please be very careful not to include any spaces at the beginning or the end.  If you are having problems such as incorrect password, please type the password in manually or paste it in to a text editor to confirm there are no additional characters added by your mail client.
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php _e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php _e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php _e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" />
		</p>
			<a href="#" class="save_changes">Update Password</a>
	</fieldset>

	<div class="clear"></div>

	<?php
	$customer_id = get_current_user_id();

	if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
		$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
			'billing' => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		), $customer_id );
	} else {
		$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		), $customer_id );
	}

	$oldcol = 1;
	$col    = 1;
	?>
	<h1>Addresses</h1>
	<p>
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); ?>
	</p>

	<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) echo '<div class="u-columns woocommerce-Addresses col2-set addresses">'; ?>

	<?php foreach ( $get_addresses as $name => $title ) : ?>

		<div class="u-column<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> col-<?php echo ( ( $oldcol = $oldcol * -1 ) < 0 ) ? 1 : 2; ?> woocommerce-Address">
			<header class="woocommerce-Address-title title">
				<h3><?php echo $title; ?></h3>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit"><?php _e( 'Edit', 'woocommerce' ); ?></a>
			</header>
			<address>
				<?php
					$address = apply_filters( 'woocommerce_my_account_my_address_formatted_address', array(
						'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
						'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
						'company'     => get_user_meta( $customer_id, $name . '_company', true ),
						'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
						'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
						'city'        => get_user_meta( $customer_id, $name . '_city', true ),
						'state'       => get_user_meta( $customer_id, $name . '_state', true ),
						'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
						'country'     => get_user_meta( $customer_id, $name . '_country', true ),
					), $customer_id, $name );

					$formatted_address = WC()->countries->get_formatted_address( $address );

					if ( ! $formatted_address )
						_e( 'You have not set up this type of address yet.', 'woocommerce' );
					else
						echo $formatted_address;
				?>
			</address>
		</div>




	<?php endforeach; ?>

	<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) echo '</div>'; ?>
	<p>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" id="save_changes" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</p>
	<h1>Streaming Details</h1>
<span class="chat_nick_name_valid"></span>
<div class="streaming_wrapper">
		<?php do_action( 'woocommerce_edit_account_form' ); ?>



	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</div>





	<a href="#" class="save_changes">Save Changes</a>



<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
</form>

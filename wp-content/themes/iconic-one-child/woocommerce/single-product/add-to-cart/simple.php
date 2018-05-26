<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product );

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
		<?php

  global $wpdb;
  $user_id = get_current_user_id();
  $user = get_userdata( $user_id );
	/*
  $level = $wpdb->get_row('SELECT level_id FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user_id . '";');
  $level_id = $level->level_id;
  $level_array = $wpdb->get_row('SELECT option_value FROM ' . $wpdb->prefix . 'options WHERE option_id="583";');
  $level_array = unserialize($level_array->option_value);
	*/

	$payment = $wpdb->get_row('SELECT expire_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user_id . '";');
  $payment_confirmed = $payment->expire_time;


  if (new DateTime() > new DateTime($payment_confirmed) || empty($payment_confirmed)) {

?>
  <p>Sorry, but you can only purchase this item if you are a BATC member. If you are already a member, please login first, then come back here. If you are not yet a member, please consider joining us.</p>
  <a href="/join-the-batc/" class="simple_product_join">Join the BATC</a>
  <?php
  }
  else {
    /**
     * @since 2.1.0.
     */
    do_action( 'woocommerce_before_add_to_cart_button' );

    /**
     * @since 3.0.0.
     */

    do_action( 'woocommerce_before_add_to_cart_quantity' );

    woocommerce_quantity_input( array(
      'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
      'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
      'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
    ) );

    /**
     * @since 3.0.0.
     */
    do_action( 'woocommerce_after_add_to_cart_quantity' );

  ?>
  <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
  <?php

  }

?>

		<?php
			/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_button' );
		?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>

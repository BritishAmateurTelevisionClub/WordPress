<?php
/**
 * Growdev_Admin_Meta_Boxes
 *
 * @author 		Shop Plugins
 * @category 	Admin
 * @version     1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Growdev_Admin_Meta_Boxes
 */
class Growdev_Admin_Meta_Boxes {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 30 );
        // Save data
        add_action( 'woocommerce_process_product_meta', 'Growdev_Meta_Box_Redirect::save', 10, 2 );
    }

    public function add_meta_box(){
        add_meta_box( 'growdev-thankyou-redirect', __( 'Custom Thank You Page', 'woocommerce-redirect-thank-you' ), 'Growdev_Meta_Box_Redirect::output', 'product', 'side', 'default' );
    }
}

new Growdev_Admin_Meta_Boxes();
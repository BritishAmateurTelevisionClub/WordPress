<?php
/**
 * Custom Thank You page redirect
 *
 * Displays the product data box, tabbed, with several panels covering price, stock etc.
 *
 * @author 		Shop Plugins
 * @category 	Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Growdev_Meta_Box_Redirect
 */
class Growdev_Meta_Box_Redirect {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $_redirect_page_id = get_post_meta( $post->ID, '_redirect_page_id', true);
        ?>
        <ul class="thank_you_page_redirect submitbox">
            <li class="wide" id="actions">
                <?php
                $args = array( 'name'	    => '_redirect_page_id',
                    'id'					=> '_redirect_page_id',
                    'sort_column' 		    => 'menu_order',
                    'sort_order'			=> 'ASC',
                    'show_option_none' 	    => ' ',
                    'class'				    => 'chosen_select_nostd',
                    'echo' 				    => false,
                    'selected'			    => absint( $_redirect_page_id )
                );
                echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', 'woocommerce-redirect-thank-you' ) .  "' style='width:90%;' class='chosen_select_nostd' id=", wp_dropdown_pages( $args ) );

                ?>
            </li>
        </ul>
        <p><?php _e('Customers who buy this product will be redirected to this page.','growdev') ?></p>
        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id, $post ) {
        if ( isset( $_POST['_redirect_page_id'] ) ) update_post_meta( $post_id, '_redirect_page_id', stripslashes( $_POST['_redirect_page_id'] ) );
    }
}
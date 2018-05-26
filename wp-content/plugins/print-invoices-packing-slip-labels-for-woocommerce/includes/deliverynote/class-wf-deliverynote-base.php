<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wf_Deliverynote_Base')) {

    // class for delivery note related activities
    class Wf_Deliverynote_Base {

        public function __construct() {
            $this->wf_custom_footer = get_option('woocommerce_wf_packinglist_footer_dn') != '' ? get_option('woocommerce_wf_packinglist_footer_dn') : '';
            $this->woocommerce_wf_delivery_note_disable_total_weight = get_option('woocommerce_wf_delivery_note_disable_total_weight') != '' ? get_option('woocommerce_wf_delivery_note_disable_total_weight') : 'no';
        }
        public function get_footer($order, $document_type){
            
            return stripslashes(apply_filters('wf_pklist_customize_footer_information', $this->wf_custom_footer, $order, $document_type));
        }
        
        
        /**
         * Admin Page for delivery note.
         */
        public function render_settings() {
            ?>
            <div id="delivery_note_tab" class="tab-pane fade in active"><?php
            include('settings.php');
            ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }
        
        /**
         * Validate submission of settings page for delivery note.
         */
         public function validate_settings(){
             
             

                if (!isset($_POST['woocommerce_wf_enable_delivery_note'])) {
                    $_POST['woocommerce_wf_enable_delivery_note'] = 'no';
                }
                
                if (!isset($_POST['woocommerce_wf_attach_image_delivery_note'])) {
                    $_POST['woocommerce_wf_attach_image_delivery_note'] = "No";
                }
                
                if (!isset($_POST['woocommerce_wf_attach_price_delivery_note'])) {
                    $_POST['woocommerce_wf_attach_price_delivery_note'] = "No";
                }
                
                if (!isset($_POST['woocommerce_wf_delivery_note_disable_total_weight'])) {
                    $_POST['woocommerce_wf_delivery_note_disable_total_weight'] = 'no';
                }
                
                
                if (!isset($_POST['wf_deliverynote_contactno_email'])) {
                    $_POST['wf_deliverynote_contactno_email'] = array();
                }
                
                if (!isset($_POST['wf_packing_list_product_meta_fields_dn'])) {
                    $_POST['wf_packing_list_product_meta_fields_dn'] = array();
                }
                if (isset($_POST['woocommerce_wf_packinglist_footer_dn']) && strlen($_POST['woocommerce_wf_packinglist_footer_dn']) > 75) {
                    $_POST['woocommerce_wf_packinglist_footer_dn'] = $_POST['woocommerce_wf_packinglist_footer_dn'];
                }
         }
    }

}
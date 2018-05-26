<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wf_Packinglist_Base')) {

    // Class for packing list related activities
    class Wf_Packinglist_Base {

        public function __construct() {

            $this->wf_custom_footer = get_option('woocommerce_wf_packinglist_footer_pk') != '' ? get_option('woocommerce_wf_packinglist_footer_pk') : '';
            $this->woocommerce_wf_packinglist_disable_total_weight = get_option('woocommerce_wf_packinglist_disable_total_weight') != '' ? get_option('woocommerce_wf_packinglist_disable_total_weight') : 'no';
        }

        public function get_footer($order, $document_type) {

            return stripslashes(apply_filters('wf_pklist_customize_footer_information', $this->wf_custom_footer, $order, $document_type));
        }

        /**
         * Admin settings page for packing slip
         */
        public function render_settings() {
            ?>
            <div id="packing_slip_tab" class="tab-pane fade in active"><?php include('settings.php'); ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }

        /**
         * Validate submission of settings page for packinglist
         */
        public function validate_settings() {

            if (!isset($_POST['woocommerce_wf_enable_packing_slip'])) {
                $_POST['woocommerce_wf_enable_packing_slip'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_attach_image_packinglist'])) {
                $_POST['woocommerce_wf_attach_image_packinglist'] = "No";
            }
            if (!isset($_POST['woocommerce_wf_attach_price_packinglist'])) {
                $_POST['woocommerce_wf_attach_price_packinglist'] = "No";
            }
            if (!isset($_POST['woocommerce_wf_packinglist_disable_total_weight'])) {
                $_POST['woocommerce_wf_packinglist_disable_total_weight'] = 'no';
            }
            if (!isset($_POST['wf_packinglist_contactno_email'])) {
                $_POST['wf_packinglist_contactno_email'] = array();
            }
            if (!isset($_POST['wf_packing_list_product_meta_fields'])) {
                $_POST['wf_packing_list_product_meta_fields'] = array();
            }
            if (isset($_POST['woocommerce_wf_packinglist_footer_pk']) && strlen($_POST['woocommerce_wf_packinglist_footer_pk']) > 75) {
                $_POST['woocommerce_wf_packinglist_footer_pk'] = $_POST['woocommerce_wf_packinglist_footer_pk'];
            }
            
        }

    }

}
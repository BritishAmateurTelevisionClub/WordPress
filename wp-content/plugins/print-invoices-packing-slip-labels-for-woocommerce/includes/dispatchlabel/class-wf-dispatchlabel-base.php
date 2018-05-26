<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wf_Dispatchlabel_Base')) {

    // class for dispatch label related activities
    class Wf_Dispatchlabel_Base {

        public function __construct() {
            
            $this->additional_data_fields = array(
                'Contact Number' => 'contact_number',
                'Email' => 'email'
            );
        }
        
        
        /**
         * Admin Page for dispatch label.
         */
        public function render_settings() {
            ?>
            <div id="dispatch_label_tab" class="tab-pane fade in active"><?php
            include('settings.php');
            ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }

        /**
         * Validate submission of settings page for dispatch label
         */
         public function validate_settings(){
             
            if (!isset($_POST['woocommerce_wf_enable_dispath_label'])) {
                    $_POST['woocommerce_wf_enable_dispath_label'] = 'no';
                }
                
                if (!isset($_POST['wf_dispatchlabel_contactno_email'])) {
                    $_POST['wf_dispatchlabel_contactno_email'] = array();
                }
                
                
        }
    }

}
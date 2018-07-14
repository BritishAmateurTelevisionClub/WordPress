<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wf_Shippinglabel_Base')) {

    // class for shipping label related activities
    class Wf_Shippinglabel_Base {

        public function __construct() {
            $this->additional_data_fields = array(
                'Contact Number' => 'contact_number',
                'Email' => 'email'
            );
            $this->shippinglabel_contactno_email = is_array(get_option('wf_shippinglabel_contactno_email')) ? get_option('wf_shippinglabel_contactno_email') : $this->additional_data_fields;
            $this->wf_enable_shipping_label = get_option('woocommerce_wf_enable_shipping_label') != '' ? get_option('woocommerce_wf_enable_shipping_label') : 'Yes';
            
            
        }

        // function to get shipping to address
        public function get_shipto_address($order) {

            $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
            $shipping_address = array();
            $shippto_address = '';
            if ($_GET['type'] == 'print_shipment_label') {
                if (get_post_meta((WC()->version < '2.7.0') ? $order->id : $order->get_id(), '_wcmspackage', true)) {
                    $packages = get_post_meta((WC()->version < '2.7.0') ? $order->id : $order->get_id(), '_wcmspackage', true);
                    foreach ($packages as $package) {
                        $shippto_address.= '<p>' . WC()->countries->get_formatted_address($package['full_address']) . '</p>';
                    }
                } else {

                    $shippto_address.= '<p>' . $order->get_formatted_shipping_address() . '</p>';
                }
                $billing_phone = (WC()->version < '2.7.0') ? $order->billing_phone : $order->get_billing_phone();
                $billing_phone = apply_filters('wf_alter_billing_phone_number',$billing_phone,$order_id);
                if($billing_phone != '') {
                    $shippto_address.= "<p><strong>";
                    $shippto_address.= __('Ph No : ', 'wf-woocommerce-shipment-label-printing');
                    $shippto_address.= $billing_phone . '</strong></p>';
                }
                $billing_email = (WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email();
                $billing_email = apply_filters('wf_alter_billing_email',$billing_email,$order_id);
                if($billing_email != '') {
                   $shippto_address.= "<p><strong>";
                    $shippto_address.= __('Email : ', 'wf-woocommerce-shipment-label-printing');
                    $shippto_address.= $billing_email . '</strong></p>';

                }
                $shippto_address = apply_filters('wf_alter_shipmentlabel_shipto_address', $shippto_address, $order);
                return $shippto_address;
            } else {
                $countries = new WC_Countries;
                $shipping_country = get_post_meta((WC()->version < '2.7.0') ? $order->id : $order->get_id(), '_shipping_country', true);
                $shipping_state = get_post_meta((WC()->version < '2.7.0') ? $order->id : $order->get_id(), '_shipping_state', true);
                $shipping_state_full = ( $shipping_country && $shipping_state && isset($countries->states[$shipping_country][$shipping_state]) ) ? $countries->states[$shipping_country][$shipping_state] : $shipping_state;
                $shipping_country_full = ( $shipping_country && isset($countries->countries[$shipping_country]) ) ? $countries->countries[$shipping_country] : $shipping_country;
                $shipping_address = array(
                    'first_name' => $order->shipping_first_name,
                    'last_name' => $order->shipping_last_name,
                    'company' => $order->shipping_company,
                    'address_1' => $order->shipping_address_1,
                    'address_2' => $order->shipping_address_2,
                    'city' => $order->shipping_city,
                    'state' => (get_option('woocommerce_wf_state_code_disable') != '' && get_option('woocommerce_wf_state_code_disable') === 'yes' ? $shipping_state_full : $shipping_state),
                    'postcode' => $order->shipping_postcode,
                    'country' => $shipping_country_full,
                    'phone' => $order->billing_phone,
                    'email' => $order->billing_email,
                );
                // clear the $countries object when we're done to free up memory
                unset($countries);
                return apply_filters('wf_pklist_label_shipping_address', $shipping_address, $order);
            }
        }
        
        /**
         * Admin settings page for shipping label
         */
        public function render_settings() {            
            ?>
            <div id="shipping_label_tab" class="tab-pane fade in active"><?php include('settings.php'); ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }
        
        /**
         * Validate submission of settings page for shipping label
         */
        public function validate_settings(){
            
            if (!isset($_POST['woocommerce_wf_enable_shipping_label'])) {
                    $_POST['woocommerce_wf_enable_shipping_label'] = 'no';
                }
                
                if (!isset($_POST['woocommerce_wf_enable_multiple_shipping_label'])) {
                    $_POST['woocommerce_wf_enable_multiple_shipping_label'] = 'No';
                }
                
                if (!isset($_POST['wf_shipping_label_column_number'])) {
                    $_POST['wf_shipping_label_column_number'] = 4;
                }
                 if (!isset($_POST['wf_shippinglabel_contactno_email'])) {
                    $_POST['wf_shippinglabel_contactno_email'] = array();
                }
                if (!isset($_POST['woocommerce_wf_packinglist_contact_number'])) {
                $_POST['woocommerce_wf_packinglist_contact_number'] = 'no';
            }
            
        }   

    }

}
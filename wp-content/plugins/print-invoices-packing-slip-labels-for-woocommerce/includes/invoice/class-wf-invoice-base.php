<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wf_Invoice_Base')) {

    // class for invoice related activities
    class Wf_Invoice_Base {

        public function __construct() {

            $this->wf_invoice_padding = get_option('woocommerce_wf_invoice_padding_number') != '' ? get_option('woocommerce_wf_invoice_padding_number') : 0;
            $this->wf_add_variation = get_option('woocommerce_wf_packinglist_variation_data') != '' ? get_option('woocommerce_wf_packinglist_variation_data') : 'Yes';
            $this->wf_reduce_product_name = 'Yes';
            $this->wf_pklist_add_sku = get_option('woocommerce_wf_packinglist_add_sku') != '' ? get_option('woocommerce_wf_packinglist_add_sku') : 'No';
            $this->wf_add_frontend_info = get_option('woocommerce_wf_packinglist_frontend_info') != '' ? get_option('woocommerce_wf_packinglist_frontend_info') : 'Yes';
            $this->wf_custom_footer = get_option('woocommerce_wf_packinglist_footer_in') != '' ? get_option('woocommerce_wf_packinglist_footer_in') : '';
            
            $this->wf_view_checkbox_data = get_option('wf_view_checkbox_data') != '' ? get_option('wf_view_checkbox_data') : 'No';
           
            $this->additional_invoice_data_fields = array(
                'Contact Number' => 'contact_number',
                'Email' => 'email',
                'SSN' => 'ssn',
                'VAT' => 'vat',
                'Customer Note' => 'cus_note',
            );
            
            
            add_action('wp_ajax_reset_invoice_number', array($this, 'reset_invoice_number'));
            add_action('woocommerce_order_details_after_order_table', array($this, 'wf_view_order_action')); // add actions to My Account view order screen
        }

        public function wf_view_order_action($order) {
            $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
            if ($this->wf_add_frontend_info === 'Yes') {
                $invoice_url = esc_url(wp_nonce_url(admin_url('?attaching_pdf=1&print_packinglist=true&email=' . $this->wf_own_encode_method((WC()->version < '2.7.0') ? $order->billing_email : $order->get_billing_email()) . '&post=' . $this->wf_own_encode_method((WC()->version < '2.7.0') ? $order->id : $order->get_id()) . '&type=print_invoice&user_print=1')));
                $button = '<a class="button button-primary" target="_blank" href="' . $invoice_url . '">' . esc_html__('Print Invoice', 'wf-woocommerce-packing-list') . '</a><br><br>';
                echo $button;
            }
        }

        public function wf_own_encode_method($data) {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }

        public function add_postfix_prefix($padded_invoice_number) {

            $prefix_data = get_option('woocommerce_wf_invoice_number_prefix');
            $postfix_data = get_option('woocommerce_wf_invoice_number_postfix');

            if ($prefix_data != '') {
                $prefix_data = $this->get_shortcode_replaced_date($prefix_data);
            }
            if ($postfix_data != '') {
                $postfix_data = $this->get_shortcode_replaced_date($postfix_data);
            }

            return $prefix_data . $padded_invoice_number . $postfix_data;
        }

        public function add_invoice_padding($wf_invoice_number) {

            $padded_invoice_number = '';
            $padding_count = $this->wf_invoice_padding - strlen($wf_invoice_number);
            if ($padding_count > 0) {
                for ($i = 0; $i < $padding_count; $i++) {
                    $padded_invoice_number .= '0';
                }
            }
            return $padded_invoice_number . $wf_invoice_number;
        }

        public function get_shortcode_replaced_date($shortcode_text) {

            preg_match_all("/\[([^\]]*)\]/", $shortcode_text, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $date_shortcode) {
                    $date = date($date_shortcode, strtotime('now'));
                    $shortcode_text = str_replace("[$date_shortcode]", $date, $shortcode_text);
                }
            }
            return $shortcode_text;
        }

        
        /* Ajax reset invoice number */

        public function reset_invoice_number() {

            if (isset($_POST)) {

                $invoice_start_number = $_POST['invoice_start_number'];
                $order_no_as_invoice_number = $_POST['order_no_as_invoice_number'];
                $invoice_padding_number = $_POST['invoice_padding_number'];
                $invoice_number_prefix = $_POST['invoice_number_prefix'];
                $invoice_number_postfix = $_POST['invoice_number_postfix'];

                if ($invoice_start_number == "" or $invoice_start_number == NULL) {
                    $invoice_start_number = 1;
                }
                update_option('woocommerce_wf_Current_Invoice_number', $invoice_start_number);
                update_option('woocommerce_wf_invoice_start_number', $invoice_start_number);

                update_option('woocommerce_wf_invoice_padding_number', $invoice_padding_number);
                update_option('woocommerce_wf_invoice_number_prefix', $invoice_number_prefix);
                update_option('woocommerce_wf_invoice_number_postfix', $invoice_number_postfix);

                if ($order_no_as_invoice_number !== true) {
                    update_option('woocommerce_wf_invoice_as_ordernumber', 'No');
                }
            }
        }

        //function to set labels used for invoice
        public function get_invoice_labels() {
            
            $labels = array(
                'document_name' => __('INVOICE', 'wf-woocommerce-packing-list'),
                'order_date' => __('Order Date', 'wf-woocommerce-packing-list'),
                'billing_address' => __('Billing address', 'wf-woocommerce-packing-list'),
                'shipping_address' => __('Shipping address', 'wf-woocommerce-packing-list'),
                'email' => __('Email', 'wf-woocommerce-packing-list'),
                'contact_number' => __('Tel', 'wf-woocommerce-packing-list'),
                'vat' => __('VAT', 'wf-woocommerce-packing-list'),
                'ssn' => __('SSN', 'wf-woocommerce-packing-list'),
                'sku' => __('SKU', 'wf-woocommerce-packing-list'),
                'product_name' => __('Product', 'wf-woocommerce-packing-list'),
                'quantity' => __('Quantity', 'wf-woocommerce-packing-list'),
                'total_price' => __('Total Price', 'wf-woocommerce-packing-list'),
                'price' => __('Price', 'wf-woocommerce-packing-list'),
                'sub_total' => __('Subtotal', 'wf-woocommerce-packing-list'),
                'total' => __('Total', 'wf-woocommerce-packing-list'),
                'payment_method' => __('Payment Method', 'wf-woocommerce-packing-list'),
                'tracking_provider' => __('Shipping Method', 'wf-woocommerce-packing-list'),
                'tracking_number' => __('Tracking number', 'wf-woocommerce-packing-list'),
                'shipping' => __('Shipping', 'wf-woocommerce-packing-list'),
                'shipping_service' => __('Shipping Service', 'wf-woocommerce-packing-list'),
                'cart_discount' => __('Cart Discount', 'wf-woocommerce-packing-list'),
                'order_discount' => __('Order Discount', 'wf-woocommerce-packing-list'),
                'total_tax' => __('Total Tax', 'wf-woocommerce-packing-list'),
                'signature' => __('Signature', 'wf-woocommerce-packing-list'),
                'from_addr' => __('From Address', 'wf-woocommerce-packing-list'),
                'date_txt' => __('Date', 'wf-woocommerce-packing-list'),
                'fee_txt' => __('Fee', 'wf-woocommerce-packing-list')
            );
            return $labels;
        }

        public function woocommerce_invoice_order_items_table($order, $show_price = FALSE) {

            $return = '';

            $user_currency = get_post_meta(( WC()->version < '2.7.0' ) ? $order->id : $order->get_id(), '_order_currency', true);
            $acive_template = get_option('wf_invoice_active_key');
            if (get_option($acive_template . 'value')) {
                $main_data_value = get_option($acive_template . 'value');
            } else {
                $main_data_value = get_option('wf_invoice_active_value');
            }
            $main_data_array = explode('|', $main_data_value);

            $order_items = $order->get_items();
            foreach ($order_items as $item_id => $item) {
                // get the product; if this variation or product has been deleted, this will return null...


                $_product = $order->get_product_from_item($item);
                if ($_product) {
                    if (WC()->version < '2.7.0') {
                        $product_variation_data = $_product->variation_data;
                    } else {
                        $product_variation_data = $_product->is_type('variation') ? wc_get_product_variation_attributes($_product->get_id()) : '';
                    }
                    $item_price = (WC()->version < '2.7.0') ? $_product->price : $_product->get_price();

                    $item_price = apply_filters('wf_alter_invoice_item_price', $item_price, $_product, $order);
                    $item_qty = apply_filters('wf_alter_invoice_quantiy', $item['qty'], $_product, $order);

                    $image_id = get_post_thumbnail_id(( (WC()->version < '2.7.0') ? $_product->id : $_product->get_id()));
                    $attachment = wp_get_attachment_image_src($image_id);
                    $sku = $variation = '';
                    $sku = $_product->get_sku();

                   $item_meta = (WC()->version < '3.1.0') ? new WC_Order_Item_Meta($item) : new WC_Order_Item_Product($item);
                    // first, is there order item meta avaialble to display?
                    $variation;
                    $variation = function_exists('wc_get_order_item_meta') ? wc_get_order_item_meta($item_id, '', false) : $order->get_item_meta($item_id);
                    if (!$variation && $_product && isset($product_variation_data)) {
                        // otherwise (for an order added through the admin) lets display the formatted variation data so we have something to fall back to
                        $variation = $this->get_order_line_item_variation_data($item_id, $_product, $order);
                    }
                    if ($variation) {
                        $variation = $this->get_order_line_item_variation_data($item_id, $_product, $order);
                    }


                    if ($this->wf_reduce_product_name != 'Yes') {
                        $item['name'] = strlen($item['name']) > 15 ? substr($item['name'], 0, 15) . '...' : $item['name'];
                        if ($variation) {
                            $variation = strlen($variation) > 15 ? substr($variation, 0, 15) . '...' : $variation;
                        }
                    }
                    $variation = '<br/><small>' . $variation . '</small>';
                    $return .= '<tr>';
                    $addional_product_meta = '';
                    if (get_option('wf_invoice_product_meta_fields')) {

                        $main_product_select_array = get_option('wf_invoice_product_meta_fields');
                        $main_product_name_array = get_option('wf_invoice_own_product_meta_field_import');
                        foreach ($main_product_select_array as $value) {
                            if (get_post_meta(((WC()->version < '2.7.0') ? $_product->id : $_product->get_id()), $value, true)) {
                                $data = get_post_meta(((WC()->version < '2.7.0') ? $_product->id : $_product->get_id()), $value, true);
                                if (is_array($data)) {
                                    $output_data = implode(', ', $data);
                                } else {

                                    $output_data = $data;
                                }
                                $addional_product_meta .= '<small>' . $main_product_name_array[$value] . ' : ' . $output_data . '</small><br>';
                            }
                        }
                    }

                    if (get_option('woocommerce_wf_attach_image_packinglist') == 'Yes' && $show_price === FALSE) {
                        $return .= '<td class="thumb column-thumb" data-colname="Image" style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                        if (!empty($attachment[0])) {
                            $return .= '<a><img src="' . $attachment[0] . '" class="attachment-thumbnail size-thumbnail wp-post-image" height="30" width="60"/></a>';
                        }
                        $return .= '</td>';
                    }
                    if (get_option('woocommerce_wf_attach_image_delivery_note') == 'Yes' && $show_price === FALSE) {
                        $return .= '<td class="thumb column-thumb" data-colname="Image" style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                        if (!empty($attachment[0])) {
                            $return .= '<a><img src="' . $attachment[0] . '" class="attachment-thumbnail size-thumbnail wp-post-image" height="30" width="60"/></a>';
                        }
                        $return .= '</td>';
                    }
                    
                        if ($main_data_array[67] != 'default') {
                           
                            $return .= '<td style="word-wrap: break-word;text-align:unset;color:' . $main_data_array[67] . '" class="qty">' . $sku . '</td>
						<td style="word-wrap: break-word;text-align:unset;color:' . $main_data_array[67] . '" class="desc"><font>' . apply_filters('woocommerce_order_product_title', $item['name'], $_product) . '</font>' . $variation . ' ' . rtrim($addional_product_meta, '<br>') . '</td>';
                        } else {

                            $return .= '<td style="word-wrap: break-word;text-align:unset;color:black" class="qty">' . $sku . '</td>
                        <td style="word-wrap: break-word;text-align:unset;color:black" class="desc"><font>' . apply_filters('woocommerce_order_product_title', $item['name'], $_product) . '</font>' . $variation . ' ' . rtrim($addional_product_meta, '<br>') . '</td>';
                        }
                    
                    if ($main_data_array[67] != 'default') {
                        $return .= '<td class="unit" style="word-wrap: break-word;color:' . $main_data_array[67] . ';text-align:unset;">' . $item_qty . '</td>';
                    } else {
                        $return .= '<td class="unit" style="word-wrap: break-word;color:black;text-align:unset;">' . $item_qty . '</td>';
                    }
                    if (WC()->version < '2.7.0') {
                        $order_prices_include_tax = $order->prices_include_tax;
                        $order_display_cart_ex_tax = $order->display_cart_ex_tax;
                    } else {
                        $order_prices_include_tax = $order->get_prices_include_tax();
                        $order_display_cart_ex_tax = get_post_meta($order->get_id(), '_display_cart_ex_tax', true);
                    }
                    if ($main_data_array[67] != 'default') {
                        $return .= '<td  style="word-wrap: break-word;color:' . $main_data_array[67] . ';text-align:unset;" class="total">';
                        $return .= wc_price($item_price,array('currency' => $user_currency));
                    } else {
                        $return .= '<td style="word-wrap: break-word;color:black;text-align:unset;" class="total">';
                        $return .= wc_price($item_price,array('currency' => $user_currency));
                    }
                    $return .= '</td>';

                    if ($show_price) {
                        if ($main_data_array[67] != 'default') {


                            $return .= '<td  style="word-wrap: break-word;text-align:unset;color:' . $main_data_array[67] . ';" class="total">';
                        } else {
                            $return .= '<td  style="word-wrap: break-word;text-align:unset;color:black;" class="total">';
                        }
                        if ($order_display_cart_ex_tax || !$order_prices_include_tax) {

                            $ex_tax_label = ($order_prices_include_tax) ? 1 : 0;
                            $return .= wc_price($order->get_line_subtotal($item), array('currency' => $user_currency), array(
                                'ex_tax_label' => $ex_tax_label
                            ));
                        } else {

                            $return .= wc_price($order->get_line_subtotal($item, TRUE), array('currency' => $user_currency));
                        }

                        $return .= '</td>';
                    } else {
                        if ($main_data_array[67] != 'default') {
                            $return .= '<td style="color:' . $main_data_array[67] . ';text-align:center; border: 1px solid lightgrey; padding:5px;">';
                        } else {
                            $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                        }
                        $return .= ($_product && $_product->get_weight()) ? $_product->get_weight() * $item['qty'] . ' ' . get_option('woocommerce_weight_unit') : __('n/a', 'wf-woocommerce-packing-list');
                        $return .= '</td>';
                        if (get_option('woocommerce_wf_attach_price_packinglist') == 'Yes') {
                            $return .= '<td style="color:' . $main_data_array[67] . ';text-align:center; border: 1px solid lightgrey; padding:5px;">';
                            if ($order_display_cart_ex_tax || !$order_prices_include_tax) {
                                $ex_tax_label = ($order_prices_include_tax) ? 1 : 0;
                                $return .= wc_price($order->get_line_subtotal($item), array('currency' => $user_currency), array(
                                    'ex_tax_label' => $ex_tax_label
                                ));
                            } else {
                                $return .= wc_price($order->get_line_subtotal($item, TRUE), array('currency' => $user_currency));
                            }
                            $return .= '</td>';
                        }
                        if (get_option('woocommerce_wf_attach_price_delivery_note') == 'Yes') {
                            $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                            if ($order_display_cart_ex_tax || !$order_prices_include_tax) {
                                $ex_tax_label = ($order_prices_include_tax) ? 1 : 0;
                                $return .= wc_price($order->get_line_subtotal($item), array('currency' => $user_currency), array(
                                    'ex_tax_label' => $ex_tax_label
                                ));
                            } else {
                                $return .= wc_price($order->get_line_subtotal($item, TRUE), array('currency' => $user_currency));
                            }
                            $return .= '</td>';
                        }
                    }
                    $return .= '</tr>';
                }
            }

            $return = apply_filters('woocommerce_packinglist_order_items_table', $return);
            return $return;
        }

        // Function to get variation details of line order line item
        public function get_order_line_item_variation_data($item_id, $_product, $order) {
            
            if (WC()->version > '2.7.0') {
                global $wpdb;
                $meta_value_data = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value, meta_id, order_item_id
			FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d
			ORDER BY meta_id", absint($item_id)), ARRAY_A);
            }
            $variation = '';
            $meta_data = array();
            if ($metadata = ((WC()->version < '2.7.0') ? $order->has_meta($item_id) : $meta_value_data)) {
                foreach ($metadata as $meta) {
                    // Skip hidden core fields
                    if (in_array($meta['meta_key'], array(
                                '_qty',
                                '_tax_class',
                                '_product_id',
                                '_variation_id',
                                '_line_subtotal',
                                '_line_subtotal_tax',
                                '_line_total',
                                '_line_tax',
                                'method_id',
                                'cost'
                            ))) {
                        continue;
                    }

                    // Skip serialised meta
                    if (is_serialized($meta['meta_value'])) {
                        continue;
                    }

                    // Get attribute data
                    if (taxonomy_exists(wc_sanitize_taxonomy_name($meta['meta_key']))) {
                        $term = get_term_by('slug', $meta['meta_value'], wc_sanitize_taxonomy_name($meta['meta_key']));
                        $meta_key = wc_attribute_label(wc_sanitize_taxonomy_name($meta['meta_key']));

                        $meta_value = isset($term->name) ? $term->name : $meta['meta_value'];

                        $meta_data[$meta_key] = $meta_value;
                    } else {
                        $meta_data[$meta['meta_key']] = $meta['meta_value'];
                    }
                }
                $meta_data = apply_filters('wf_pklist_modify_meta_data', $meta_data);
                foreach ($meta_data as $id => $value) {
                    $variation .= wp_kses_post(rawurldecode($id)) . ' : ' . wp_kses_post(rawurldecode($value)) . ' ';
                }
            }
            return $variation;
        }

        public function get_footer($order, $document_type) {

            return stripslashes(apply_filters('wf_pklist_customize_footer_information', $this->wf_custom_footer, $order, $document_type));
        }

        /**
         * Admin settings page for invoice
         */
        public function render_settings() {
            ?>
            <div id="invoice_tab" class="tab-pane fade in active"><?php
                include('settings.php');
                ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }

        /**
         * Validate submission of settings page for invoice
         */
        public function validate_settings() {

            if (!isset($_POST['woocommerce_wf_enable_invoice'])) {
                $_POST['woocommerce_wf_enable_invoice'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_add_invoice_in_mail'])) {
                $_POST['woocommerce_wf_add_invoice_in_mail'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_generate_for_orderstatus'])) {
                $_POST['woocommerce_wf_generate_for_orderstatus'] = "";
            }
            if (!isset($_POST['woocommerce_wf_invoice_as_ordernumber'])) {
                $_POST['woocommerce_wf_invoice_as_ordernumber'] = "No";
            }
            
            if (isset($_POST['woocommerce_wf_packinglist_footer_in']) && strlen($_POST['woocommerce_wf_packinglist_footer_in']) > 75) {
                $_POST['woocommerce_wf_packinglist_footer_in'] = $_POST['woocommerce_wf_packinglist_footer_in'];
            }

            if (isset($_POST['woocommerce_wf_invoice_regenerate'])) {
                if (trim($_POST['woocommerce_wf_invoice_start_number']) == "" or trim($_POST['woocommerce_wf_invoice_start_number']) == NULL) {
                    $_POST['woocommerce_wf_Current_Invoice_number'] = $_POST['woocommerce_wf_invoice_start_number'] = 1;
                } else {
                    $_POST['woocommerce_wf_Current_Invoice_number'] = $_POST['woocommerce_wf_invoice_start_number'];
                }
            } else {
                if ($_POST['woocommerce_wf_invoice_start_number'] == "" or $_POST['woocommerce_wf_invoice_start_number'] == NULL) {
                    $_POST['woocommerce_wf_Current_Invoice_number'] = $_POST['woocommerce_wf_invoice_start_number'] = 1;
                }
            }
        }

    }

}
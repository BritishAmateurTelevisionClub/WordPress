<?php

// to check wether accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Wf_Document_common {

    public function __construct() {
        ;
    }

    //function to create billing address array
    public function get_shipping_address($order) {
        
        $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
        $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
        $shipping_address = array();
        $countries = new WC_Countries;
        $shipping_country = get_post_meta($order_id, '_shipping_country', true);
        $shipping_state = get_post_meta($order_id, '_shipping_state', true);
        $shipping_state_full = ( $shipping_country && $shipping_state && isset($countries->states[$shipping_country][$shipping_state]) ) ? $countries->states[$shipping_country][$shipping_state] : $shipping_state;
        $shipping_country_full = ( $shipping_country && isset($countries->countries[$shipping_country]) ) ? $countries->countries[$shipping_country] : $shipping_country;
        $shipping_address['first_name'] = $order->shipping_first_name;
        $shipping_address['last_name'] = $order->shipping_last_name;
        $shipping_address['company'] = $order->shipping_company;
        $shipping_address['address_1'] = $order->shipping_address_1;
        $shipping_address['address_2'] = $order->shipping_address_2;
        $shipping_address['city'] = $order->shipping_city;
        $shipping_address['state'] = get_option('woocommerce_wf_state_code_disable') != '' && get_option('woocommerce_wf_state_code_disable') === 'yes' ? $shipping_state_full : $shipping_state;
        $shipping_address['country'] = $shipping_country_full;
        $shipping_address['postcode'] = $order->shipping_postcode;
        return apply_filters('wf_pklist_modify_shipping_address', $shipping_address, $order);
    }

    //function to create billing address array
    public function get_billing_address($order) {
        
        $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
        $billing_address = array();
        $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
        $countries = new WC_Countries;
        $billing_country = get_post_meta($order_id, '_billing_country', true);
        $billing_state = get_post_meta($order_id, '_billing_state', true);
        $billing_state_full = ( $billing_country && $billing_state && isset($countries->states[$billing_country][$billing_state]) ) ? $countries->states[$billing_country][$billing_state] : $billing_state;
        $billing_country_full = ( $billing_country && isset($countries->countries[$billing_country]) ) ? $countries->countries[$billing_country] : $billing_country;
        $billing_address['first_name'] = $order->billing_first_name;
        $billing_address['last_name'] = $order->billing_last_name;
        $billing_address['company'] = $order->billing_company;
        $billing_address['address_1'] = $order->billing_address_1;
        $billing_address['address_2'] = $order->billing_address_2;
        $billing_address['city'] = $order->billing_city;
        $billing_address['state'] = get_option('woocommerce_wf_state_code_disable') != '' && get_option('woocommerce_wf_state_code_disable') === 'yes' ? $billing_state_full : $billing_state;
        $billing_address['country'] = $billing_country_full;
        $billing_address['postcode'] = $order->billing_postcode;
        return apply_filters('wf_pklist_modify_billing_address', $billing_address, $order);
    }
    

    //function to calculate size for columns in invoice
    public function get_table_column_sizes($order) {
        
        $table_column_content_sizes = array(
            'sku' => 10,
            'product' => 7,
            'quantity' => 8,
            'total_price' => 11
        );
        foreach ($order->get_items() as $item) {
            $_product = $order->get_product_from_item($item);
            if ($_product) {
                if (WC()->version < '2.7.0') {
                    $product_id = $_product->id;
                    $product_variation_data = $_product->variation_data;
                    $product_product_type = $_product->product_type;
                    $product_variation_id = $product_product_type === 'variation' ? $_product->variation_id : '';
                } else {
                    $product_id = $_product->get_id();
                    $product_variation_data = $_product->is_type('variation') ? wc_get_product_variation_attributes($_product->get_id()) : '';
                    $product_product_type = $_product->get_type();
                    $product_variation_id = $_product->is_type('variation') ? $_product->get_id() : '';
                }
                $image_id = get_post_thumbnail_id($product_id);
                $attachment = wp_get_attachment_image_src($image_id);
                $sku = $order_meta_value = '';
                $sku = $_product->get_sku();
                $item_meta = (WC()->version < '3.1.0') ? new WC_Order_Item_Meta($item) : new WC_Order_Item_Product($item);

                $order_meta_value;
                if (($this->wf_add_variation == 'Yes')) {

                    if (WC()->version < '3.1.0') {
                        $order_meta_value = $item_meta->display(true, true);
                    } else {
                        $arg = array(
                            'before' => '',
                            'after' => '',
                            'separator' => ',',
                            'echo' => false,
                        );

                        $order_meta_value = wc_display_item_meta($item, $arg);
                    }

                    if ($order_meta_value) {
                        // remove newlines
                        $order_meta_value = str_replace(array("\r", "\r\n", "\n"), '', $order_meta_value);
                        $order_meta_value = str_replace(array('<strong class="wc-item-meta-label">', '</strong> <p>', "</p>"), '', $order_meta_value);

                        // switch reserved chars (:;|) to =
                        $order_meta_value = str_replace(array(': ', ':', ';', '|'), '=', $order_meta_value);
                        $order_meta_value = str_replace('meta=', '', $order_meta_value);
                    }

                    if (!$order_meta_value && $_product && isset($product_variation_data)) {
                        // otherwise (for an order added through the admin) lets display the formatted variation data so we have something to fall back to
                        $order_meta_value = wc_get_formatted_variation($product_variation_data, true);
                    }
                }
                if ($this->wf_reduce_product_name != 'Yes') {
                    $item['name'] = strlen($item['name']) > 15 ? substr($item['name'], 0, 15) . '...' : $item['name'];
                    if ($order_meta_value) {
                        $order_meta_value = strlen($order_meta_value) > 15 ? substr($order_meta_value, 0, 15) . '...' : $order_meta_value;
                    }
                }

                $price = $order->get_line_subtotal($item);

                if (strlen($item['name'] . $order_meta_value) > $table_column_content_sizes['product']) {
                    $table_column_content_sizes['product'] = strlen($item['name'] . $order_meta_value);
                }
                if (strlen($item['qty']) > $table_column_content_sizes['quantity']) {
                    $table_column_content_sizes['quantity'] = strlen($item['qty']);
                }
                if (strlen($price) > $table_column_content_sizes['total_price']) {
                    $table_column_content_sizes['total_price'] = strlen($price);
                }
            }
        }
        $total_width = 180;
        $table_column_sizes = array();
        if ($this->wf_pklist_add_sku == 'Yes') {
            $table_column_sizes['quantity'] = 3 * $table_column_content_sizes['quantity'];
            $table_column_sizes['price'] = 3 * $table_column_content_sizes['total_price'];
            $table_column_sizes['sku'] = 3 * $table_column_content_sizes['sku'];
            $table_column_sizes['product'] = 180 - ($table_column_sizes['quantity'] + $table_column_sizes['price'] + $table_column_sizes['sku']);
        } else {
            $table_column_sizes['quantity'] = 3 * $table_column_content_sizes['quantity'];
            $table_column_sizes['price'] = 3 * $table_column_content_sizes['total_price'];
            $table_column_sizes['product'] = 180 - ($table_column_sizes['quantity'] + $table_column_sizes['price']);
        }
        return $table_column_sizes;
    }

    //function to create available font list 
    public function wf_pklist_get_fonts() {
        if (file_exists(plugin_dir_path(__FILE__) . 'wf-template/pdf-templates/font/unifont/big5.ttf')) {
            return array(
                'arial' => 'Default',
                'big5' => 'Big5'
            );
        } else {
            return array(
                'arial' => 'Default'
            );
        }
    }

    //function to get new dimensions
    public function wf_pklist_get_new_dimensions($image_url, $target_height, $target_width) {
        $new_dimensions = array();
        $image_info = @getimagesize($image_url);
        if (($image_info[1] <= $target_height) && ($image_info[0] <= $target_width)) {
            $new_dimensions['width'] = $image_info[0];
            $new_dimensions['height'] = $image_info[1];
        } else {
            $new_dimensions = $this->wf_pklist_get_calculate_new_dimensions($image_info[1], $image_info[0], $target_height, $target_width);
        }
        return $new_dimensions;
    }

    //function to resize image with aspect ratio
    public function wf_pklist_get_calculate_new_dimensions($current_height, $current_width, $target_height, $target_width) {
        
        $aspect_ratio;
        $new_dimensions = array(
            'height' => $current_height,
            'width' => $current_width
        );
        $calculate_dimensions = true;
        if ($current_height > $current_width) {
            $aspect_ratio = $target_height / $current_height;
        } else {
            $aspect_ratio = $target_width / $current_width;
        }
        while ($calculate_dimensions) {
            $new_dimensions['height'] = floor($aspect_ratio * $new_dimensions['height']);
            $new_dimensions['width'] = floor($aspect_ratio * $new_dimensions['width']);
            if (($new_dimensions['height']) > $target_height) {
                $aspect_ratio = $target_height / $new_dimensions['height'];
            } else if (($new_dimensions['width']) > $target_width) {
                $aspect_ratio = $target_width / $new_dimensions['width'];
            } else {
                $calculate_dimensions = false;
            }
        }
        return $new_dimensions;
    }

    public function wf_pklist_create_order_box_shipping_package($order) {
        if (!class_exists('WF_Boxpack')) {
            include_once 'class-wf-packing.php';
        }
        $packages = array();
        $boxpack = new WF_Boxpack();
         
        // Define boxes
        
        foreach ($this->boxes as $key => $box) {
            
           
            if (!is_numeric($key)) {
                continue;
            }
            if (!$box['enabled']) {
                continue;
            }
            $newbox = $boxpack->add_box($box['name'],$box['length'], $box['width'], $box['height'], $box['box_weight']);
           
            
            if (isset($box['id'])) {
                $newbox->set_id(current(explode(':', $box['id'])));
            }
            if ($box['max_weight']) {
                $newbox->set_max_weight($box['max_weight']);
            }


        }

        $orderItems = $order->get_items();
        $items = array();
        foreach ($orderItems as $orderItem) {
            if (!empty($orderItem)) {
                $product_data = wc_get_product($orderItem['variation_id'] ? $orderItem['variation_id'] : $orderItem['product_id']);
                $items[] = array('data' => $product_data, 'quantity' => $orderItem['qty']);
            }
        }
        if (!empty($items)) {
            $package['contents'] = $items;
            // Add items
            foreach ($package['contents'] as $item_id => $values) {
                if ($values['data']) {
                    if (!$values['data']->needs_shipping()) {
                        continue;
                    }
                    $skip_product = apply_filters('wf_shipping_skip_product', false, $values, $package['contents']);
                    if ($skip_product) {
                        continue;
                    }
                    if ((WC()->version < '2.7.0')) {
                        $p_length = $values['data']->length;
                        $p_height = $values['data']->height;
                        $p_weight = $values['data']->weight;
                        $p_width = $values['data']->width;
                    } else {
                        $p_length = $values['data']->get_length();
                        $p_height = $values['data']->get_height();
                        $p_weight = $values['data']->get_weight();
                        $p_width = $values['data']->get_width();
                    }
                    if ($p_length && $p_height && $p_width && $p_weight) {
                        $dimensions = array($p_length, $p_height, $p_width);
                        for ($i = 0; $i < $values['quantity']; $i ++) {

                            $boxpack->add_item($box['name'],
                                    wc_get_dimension($dimensions[2], $this->dimension_unit), wc_get_dimension($dimensions[1], $this->dimension_unit), wc_get_dimension($dimensions[0], $this->dimension_unit), wc_get_weight($values['data']->get_weight(), $this->weight_unit), $values['data']->get_price(), array(
                                'data' => $values['data']
                                    )
                            );
                        }
                    } else {
                        return $this->wf_pklist_create_order_indvidual_item_package($order);
                    }
                }
            }
            // Pack it
            $boxpack->pack();
            $packages = $boxpack->get_packages();


        }
        
        return $this->wf_pklist_create_packinglist_boxpack_package($packages, $order);
    }

    public function wf_pklist_create_packinglist_boxpack_package($to_ship, $order) {
        $packinglist_package = array();

        foreach ($to_ship as $key => $packages) {
            if (property_exists($packages, 'packed')) {
                foreach ($packages->packed as $id => $product_data) {
                   
                    $is_product_already_exist = false;
                    $package_id_count;
                    $product = $product_data->meta['data'];
                    if ($product) {
                        if (WC()->version < '2.7.0') {
                            $product_id = $product->id;
                            $product_variation_data = $product->variation_data;
                            $product_product_type = $product->product_type;
                            $product_variation_id = $product_product_type === 'variation' ? $product->variation_id : '';
                        } else {
                            $product_id = $product->get_id();
                            $product_variation_data = $product->is_type('variation') ? wc_get_product_variation_attributes($product->get_id()) : '';
                            $product_product_type = $product->get_type();
                            $product_variation_id = $product->is_type('variation') ? $product->get_id() : '';
                        }

                        if (is_array($packinglist_package) && (count($packinglist_package) > 0) && key_exists($key, $packinglist_package)) {
                            foreach ($packinglist_package[$key] as $package_id => $package_data) {
                                if ($product_id == $package_data['id']) {
                                    $package_id_count = $package_id;
                                    $is_product_already_exist = true;
                                }
                            }
                        }
                        if ($is_product_already_exist) {
                            $packinglist_package[$key][$package_id_count]['quantity'] += 1;
                        } else {
                            $variation_details = $product_product_type == 'variation' ? wc_get_formatted_variation($product_variation_data, true) : '';
                            $variation_id = $product_product_type == 'variation' ? $product_variation_id : '';
                            
                            $packinglist_package[$key][] = array(
                                'sku' => $product->get_sku(),
                                'name' => $product->get_title(),
                                'type' => $product_product_type,
                                'weight' => $product->get_weight(),
                                'id' => $product_id,
                                'variation_id' => $variation_id,
                                'price' => $product->get_price(),
                                'variation_data' => $variation_details,
                                'quantity' => 1,
                                'package_weight' => $packages->weight,
                                'title' => $packages->box_name,
                            );

                        }
                    }
                }
                $next_package = next($to_ship);
                if (!empty($next_package) && !(property_exists($next_package, 'packed'))) {
                    foreach ($packages->unpacked as $id => $product_data) {
                        $product = $product_data->meta['data'];
                        if ($product) {
                            if (WC()->version < '2.7.0') {
                                $product_id = $product->id;
                                $product_variation_data = $product->variation_data;
                                $product_product_type = $product->product_type;
                                $product_variation_id = $product_product_type === 'variation' ? $product->variation_id : '';
                            } else {
                                $product_id = $product->get_id();
                                $product_variation_data = $product->is_type('variation') ? wc_get_product_variation_attributes($product->get_id()) : '';
                                $product_product_type = $product->get_type();
                                $product_variation_id = $product->is_type('variation') ? $product->get_id() : '';
                            }
                            $variation_details = $product_product_type == 'variation' ? wc_get_formatted_variation($product_variation_data, true) : '';
                            $variation_id = $product_product_type == 'variation' ? $product_variation_id : '';
                            $packinglist_package[][] = array(
                                'sku' => $product->get_sku(),
                                'name' => $product->get_title(),
                                'type' => $product_product_type,
                                'weight' => $product->get_weight(),
                                'id' => $product_id,
                                'variation_id' => $variation_id,
                                'price' => $product->get_price(),
                                'variation_data' => $variation_details,
                                'quantity' => 1
                            );
                        }
                    }
                }
            } else {
                if (empty($packinglist_package)) {
                    $packinglist_package = $this->wf_pklist_create_order_indvidual_item_package($order);
                }
            }
        }

        return $packinglist_package;
    }

    //function to create order package for individual items packing
    public function wf_pklist_create_order_indvidual_item_package($order) {
        
        $order_items = $order->get_items();
        $packinglist_package = array();
        foreach ($order_items as $id => $item) {
            $product = $order->get_product_from_item($item);
            $sku = $variation_details = '';
            if ($product) {
                if (WC()->version < '2.7.0') {
                    $product_id = $product->id;
                    $product_variation_data = $product->variation_data;
                    $product_product_type = $product->product_type;
                    $product_variation_id = $product_product_type === 'variation' ? $product->variation_id : '';
                } else {
                    $product_id = $product->get_id();
                    $product_variation_data = $product->is_type('variation') ? wc_get_product_variation_attributes($product->get_id()) : '';
                    $product_product_type = $product->get_type();
                    $product_variation_id = $product->is_type('variation') ? $product->get_id() : '';
                }
                $sku = $product->get_sku();
                $item_meta = (WC()->version < '3.1.0') ? new WC_Order_Item_Meta($item) : new WC_Order_Item_Product($item);
                $variation_details = $product_product_type == 'variation' ? $this->invoice->get_order_line_item_variation_data($id, $product, $order) : '';
                $variation_id = $product_product_type === 'variation' ? $product_variation_id : '';
                for ($item_count = 0; $item_count < $item['qty']; $item_count++) {
                    $packinglist_package[][] = array(
                        'sku' => $product->get_sku(),
                        'name' => $product->get_title(),
                        'type' => $product_product_type,
                        'weight' => $product->get_weight(),
                        'id' => $product_id,
                        'variation_id' => $variation_id,
                        'price' => $product->get_price(),
                        'variation_data' => $variation_details,
                        'quantity' => 1,
                        
                    );
                }
            }
        }
        return $packinglist_package;
    }

    // Function to create packaging list and shipping lables package
    public function wf_pklist_create_order_single_package($order) {

        $order_items = $order->get_items();
        $packinglist_package = array();
        foreach ($order_items as $id => $item) {
            $product = $order->get_product_from_item($item);
            $sku = $variation_details = '';
            if ($product) {
                if (WC()->version < '2.7.0') {
                    $product_id = $product->id;
                    $product_variation_data = $product->variation_data;
                    $product_product_type = $product->product_type;
                    $product_variation_id = $product_product_type === 'variation' ? $product->variation_id : '';
                } else {
                    $product_id = $product->get_id();
                    $product_variation_data = $product->is_type('variation') ? wc_get_product_variation_attributes($product->get_id()) : '';
                    $product_product_type = $product->get_type();
                    $product_variation_id = $product->is_type('variation') ? $product->get_id() : '';
                }
                $sku = $product->get_sku();
                $item_meta = (WC()->version < '3.1.0') ? new WC_Order_Item_Meta($item) : new WC_Order_Item_Product($item);
                $variation_details = $product_product_type == 'variation' ? $this->invoice->get_order_line_item_variation_data($id, $product, $order) : '';
                $variation_id = $product_product_type == 'variation' ? $product_variation_id : '';
                $packinglist_package[0][] = array(
                    'sku' => $product->get_sku(),
                    'name' => $product->get_title(),
                    'type' => $product_product_type,
                    'weight' => $product->get_weight(),
                    'id' => $product_id,
                    'variation_id' => $variation_id,
                    'price' => $product->get_price(),
                    'variation_data' => $variation_details,
                    'quantity' => $item['qty'],
                    
                );
            }
        }
        return $packinglist_package;
    }

    // Function to get shipping from address
    public function wf_shipment_label_get_from_address($document_type='invoice',$order) {

        $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
        $fromaddress = array();
        if (get_option('woocommerce_wf_packinglist_sender_name') != '') {
            $fromaddress['sender_name'] = stripslashes(get_option('woocommerce_wf_packinglist_sender_name'));
        }
        if (get_option('woocommerce_wf_packinglist_sender_address_line1') != '') {
            $fromaddress['sender_address_line1'] = stripslashes(get_option('woocommerce_wf_packinglist_sender_address_line1'));
        }
        if (get_option('woocommerce_wf_packinglist_sender_address_line2') != '') {
            $fromaddress['sender_address_line2'] = stripslashes(get_option('woocommerce_wf_packinglist_sender_address_line2'));
        } 
        if (get_option('woocommerce_wf_packinglist_sender_city') != '') {
            $fromaddress['sender_city'] = stripslashes(get_option('woocommerce_wf_packinglist_sender_city'));
        }
        if (get_option('woocommerce_wf_packinglist_sender_country') != '') {
            $fromaddress['sender_country'] = stripslashes(get_option('woocommerce_wf_packinglist_sender_country'));
        }
        if (get_option('woocommerce_wf_packinglist_sender_postalcode') != '') {
            $fromaddress['sender_postalcode'] = get_option('woocommerce_wf_packinglist_sender_postalcode');
        }
        return apply_filters('wf_alter_shipping_from_address',$fromaddress,$document_type,$order);
    }

    // function to get logo for printing
    public function wf_packinglist_get_logo($action = '') {
        
        $logo_url = '';
        if ($action == 'print_invoice' || $action == 'download_invoice') {
            if (get_option('woocommerce_wf_packinglist_invoice_logo') != '') {
                $logo_url = get_option('woocommerce_wf_packinglist_invoice_logo');
            } else {
                $logo_url = get_option('woocommerce_wf_packinglist_logo');
            }
        } else {
            $logo_url = get_option('woocommerce_wf_packinglist_logo');
        }
        return $logo_url;
    }
    
    public function woocommerce_packinglist_order_items_table($order, $show_price = FALSE, $print_type = '', $order_id = '') {
        
           $return = '';
           $acive_template = get_option('wf_invoice_active_key');
            if (get_option($acive_template . 'value')) {
                $main_data_value = get_option($acive_template . 'value');
            } else {
                $main_data_value = get_option('wf_invoice_active_value');
            }
            $main_data_array = explode('|', $main_data_value);
            
            $my_order = '';
            if ($order_id != '') {
                $my_order = new WC_Order($order_id);
            }

            foreach ($order as $id => $item) {
                $item_quantity = apply_filters('wf_alter_packing_slip_quantiy', $item['quantity']);
                $_product = wc_get_product($item['id']);
                $image_id = get_post_thumbnail_id($item['id']);
                $attachment = wp_get_attachment_image_src($image_id);
                if (($item['variation_id'] != '') && empty($attachment[0])) {
                   
                     $parent_id=wp_get_post_parent_id($item['variation_id'] );
                        $var_image_id = ($item['variation_id'] != '') ? get_post_thumbnail_id($item['variation_id']) : '';
                        $image_id = ($var_image_id == '') ? get_post_thumbnail_id($parent_id) : $var_image_id;
                        $attachment = wp_get_attachment_image_src($image_id);
                }
                if ((get_option('woocommerce_wf_attach_image_packinglist') == 'Yes' && $show_price === FALSE) & $print_type != 'dn') {
                    $return .= '<tr><td class="thumb column-thumb" data-colname="Image" style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    if (!empty($attachment[0])) {
                        $dimensions = $this->wf_pklist_get_new_dimensions($attachment[0], 30, 40);
                        $return .= '<a><img src="' . $attachment[0] . '" class="attachment-thumbnail size-thumbnail wp-post-image" height="' . $dimensions['height'] . '" width="' . $dimensions['width'] . '"/></a>';
                    }
                    $return .= '</td>';
                }
                if ((get_option('woocommerce_wf_attach_image_delivery_note') == 'Yes' && $show_price === FALSE) & $print_type === 'dn') {
                    $return .= '<tr><td class="thumb column-thumb" data-colname="Image" style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    if (!empty($attachment[0])) {
                        $dimensions = $this->wf_pklist_get_new_dimensions($attachment[0], 30, 40);
                        $return .= '<a><img src="' . $attachment[0] . '" class="attachment-thumbnail size-thumbnail wp-post-image" height="' . $dimensions['height'] . '" width="' . $dimensions['width'] . '"/></a>';
                    }
                    $return .= '</td>';
                }

                $addional_product_meta = '';
                if ($print_type != '') {
                    if (get_option('wf_packing_list_product_meta_fields_dn')) {
                        $main_product_select_array = get_option('wf_packing_list_product_meta_fields_dn');
                        $main_product_name_array = get_option('wf_packing_list_own_product_meta_field_import_dn');
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
                } else {
                    if (get_option('wf_packing_list_product_meta_fields')) {
                        $main_product_select_array = get_option('wf_packing_list_product_meta_fields');
                        $main_product_name_array = get_option('wf_packing_list_own_product_meta_field_import');
                        foreach ($main_product_select_array as $value) {
                            if (get_post_meta(((WC()->version < '2.7.0') ? $_product->id : $_product->get_id()), $value, true)) {
                                $addional_product_meta .= '<small>' . $main_product_name_array[$value] . ' : ' . get_post_meta(((WC()->version < '2.7.0') ? $_product->id : $_product->get_id()), $value, true) . '</small><br>';
                            }
                        }
                    }
                }
                $item_meta = array();
                if ($my_order != '') {
                    $order_items = $my_order->get_items();
                    foreach ($order_items as $item_id => $value) {
                        $product = $my_order->get_product_from_item($value);
                        if (((WC()->version < '2.7.0') ? $product->id : $product->get_id()) == ((WC()->version < '2.7.0') ? $_product->id : $_product->get_id())) {
                            $item_meta = function_exists('wc_get_order_item_meta') ? wc_get_order_item_meta($item_id, '', false) : $my_order->get_item_meta($item_id);
                        }
                    }
                }
                // first, is there order item meta avaialble to display?
                $variation = '';
                $variation_data = apply_filters('wf_print_invoice_variation_add', $item_meta);
                
                
                    $variation = '<small>' . $item['variation_data'] . '</small>';
                
                if ($this->wf_reduce_product_name != 'Yes') {

                    $item['name'] = strlen($item['name']) > 15 ? substr($item['name'], 0, 15) . '...' : $item['name'];
                    $variation = strlen($item['variation_data']) > 15 ? substr($item['variation_data'], 0, 15) . '...' : $item['variation_data'];
                }
                if (!empty($variation_data) && !is_array($variation_data)) {
                    $variation .= '<br>' . $variation_data;
                }

                
                    if($main_data_array[67] != 'default')
                    {
                    $return .= '<td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">' . $item['sku'] . '</td>';
                }
                else
                {
                     $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">' . $item['sku'] . '</td>';
                }
                
                if($main_data_array[67] != 'default'){
                $return .= '
                <td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">' . apply_filters('woocommerce_order_product_title_for_pk_list', $item['name'], $_product) . '<br/>' . $variation . '' . rtrim($addional_product_meta, '<br>') . '</td>
                <td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">' . $item_quantity . '</td>';
            }
            else
            {
                 $return .= '
                <td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">' . apply_filters('woocommerce_order_product_title_for_pk_list', $item['name'], $_product) . '<br/>' . $variation . '' . rtrim($addional_product_meta, '<br>') . '</td>
                <td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">' . $item_quantity . '</td>';
            }
                if ($this->woocommerce_wf_packinglist_disable_total_weight != 'Yes' & $print_type != 'dn') {
                    if($main_data_array[67] != 'default')
                    {
                    $return .= '<td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    $return .= ($item['weight'] != '') ? $item['weight'] * $item['quantity'] . ' ' . $this->weight_unit : __('n/a', 'wf-woocommerce-packing-list');
                    $return .= '</td>';
                }
                else
                {
                     $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    $return .= ($item['weight'] != '') ? $item['weight'] * $item['quantity'] . ' ' . $this->weight_unit : __('n/a', 'wf-woocommerce-packing-list');
                    $return .= '</td>';
                }
                }

                if ($this->woocommerce_wf_delivery_note_disable_total_weight != 'Yes' & $print_type === 'dn') {
                    if($main_data_array[67] != 'default'){
                    $return .= '<td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    $return .= ($item['weight'] != '') ? $item['weight'] * $item['quantity'] . ' ' . $this->weight_unit : __('n/a', 'wf-woocommerce-packing-list');
                    $return .= '</td>';
                }
                else
                {
                    $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">';
                    $return .= ($item['weight'] != '') ? $item['weight'] * $item['quantity'] . ' ' . $this->weight_unit : __('n/a', 'wf-woocommerce-packing-list');
                    $return .= '</td>';

                }
                }

                if (get_option('woocommerce_wf_attach_price_packinglist') == 'Yes' & $print_type != 'dn') {
                    if($main_data_array[67] != 'default'){
                    $currency = get_woocommerce_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($currency);
                    $price =  $item['quantity'] * $item['price'];
                    $return .= '<td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">' . $currency_symbol .apply_filters('wf_alter_product_item_price',$price,$item) . '</td>';
                }
                else
                {

                    $currency = get_woocommerce_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($currency);
                    $price =  $item['quantity'] * $item['price'];
                    $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">' . $currency_symbol .apply_filters('wf_alter_product_item_price',$price,$item) . '</td>';

                }
                }
                if (get_option('woocommerce_wf_attach_price_delivery_note') == 'Yes' & $print_type === 'dn') {
                    if($main_data_array[67] != 'default'){
                    $currency = get_woocommerce_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($currency);
                    $price =  $item['quantity'] * $item['price'];
                    $return .= '<td style="color:'.$main_data_array[67].';text-align:center; border: 1px solid lightgrey; padding:5px;">' . $currency_symbol .apply_filters('wf_alter_product_item_price',$price,$item) . '</td>';
                }
                else
                {
                    $currency = get_woocommerce_currency();
                    $currency_symbol = get_woocommerce_currency_symbol($currency);
                    $price =  $item['quantity'] * $item['price'];
                    $return .= '<td style="color:black;text-align:center; border: 1px solid lightgrey; padding:5px;">' . $currency_symbol .apply_filters('wf_alter_product_item_price',$price,$item) . '</td>';

                }
                }
                $return .= '</tr>';
            }
            $return = apply_filters('woocommerce_packinglist_order_items_table', $return);
            return $return;
        }
        
        
        // function to get plugin url
        public function wf_packinglist_get_plugin_url() {
            return untrailingslashit(plugins_url('/', __FILE__));
        }

        // functio to get pulgin directory
        public function wf_packinglist_get_plugin_path() {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

}

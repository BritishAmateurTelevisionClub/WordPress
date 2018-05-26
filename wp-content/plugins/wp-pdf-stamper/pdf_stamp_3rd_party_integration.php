<?php

/* * ************************************ */
/* * * WooCommerce Integration Start **** */
/* * ************************************ */

//Add the meta box in the woocommerce product add/edit interface
add_action('add_meta_boxes', 'stamper_woo_meta_boxes');

function stamper_woo_meta_boxes() {
    add_meta_box('stamper-wooproduct-data', 'PDF Stamper Settings', 'stamper_woo_data_box', 'product', 'normal', 'high');
}

function stamper_woo_data_box($wp_post_obj) {
    $stamp_file = get_post_meta($wp_post_obj->ID, 'stamper_woo_product_specific_stamp', true);
    $stamp_file = isset($stamp_file)? $stamp_file : '';
    
    $stamp_file_chkbox_value = '';
    if ($stamp_file != '') {
        $stamp_file_chkbox_value = ' checked="checked"';
    }
    echo "Do Not Stamp This Product: ";
    echo '<input type="checkbox" name="stamper_woo_product_specific_stamp" ' . $stamp_file_chkbox_value . ' value="1"/>';
    //echo '<input type="text" size="5" name="aff_woo_product_specific_commission" value="'.$commission_level.'" />';
    echo '<p class="description">All PDF files for your products are stamped by default. The above option can be used to exclude a product from stamping.</p>';
}

//Save the membership level data to the post meta with the product when it is saved
add_action('save_post', 'stamper_woo_save_product_data', 10, 2);

function stamper_woo_save_product_data($post_id, $post_obj) {
    // Check post type for woocommerce product
    if ($post_obj->post_type == 'product') {
        // Store data in post meta table if present in post data
        update_post_meta($post_id, 'stamper_woo_product_specific_stamp', ($_POST["stamper_woo_product_specific_stamp"] == '1') ? '1' : '');
    }
}

//Check and Handle stamping at file download time
add_action('woocommerce_download_product', 'stamper_woocommerce_download_product_handler', 10, 6);

function stamper_woocommerce_download_product_handler($email, $order_key, $product_id, $user_id, $download_id, $order_id) {
    $wp_ps_config = PDF_Stamper_Config::getInstance();
    if ($wp_ps_config->getValue('wp_pdf_stamp_allow_woocommerce_integration') == '1') {
        pdf_stamper_debug("WooCommerce integration is enabled. Checking details... ", true);
    } else {
        return;
    }

    pdf_stamper_debug("WooCommerce Download Product Hook handler. Data: " . $email . "|" . $order_key . "|" . $product_id . "|" . $user_id . "|" . $download_id . "|" . $order_id, true);

    //Get the product object
    $_product = get_product($product_id);
    // Get the download URL and try to replace the url with a path
    $file_path = $_product->get_file_download_path($download_id);
    if (!$file_path) {
        wp_die('Could not retrieve file path for WooCommerce product ID: ' . $product_id);
    }

    $stamp_file = get_post_meta($product_id, 'stamper_woo_product_specific_stamp', true);
    if ($stamp_file != '') {
        //Stamping is OFF for this product
        pdf_stamper_debug('You have excluded this product from stamping. PDF stamping for this product will not be performed!', true);
        return;
    }

    $file_type_pdf = stamper_is_file_pdf($file_path);
    if (!$file_type_pdf) {
        pdf_stamper_debug('Source file is not a PDF file so stamping is not necessary for this file!', true);
        return;
    }

    pdf_stamper_debug("WooCommerce Download Product file path: " . $file_path, true);

    //Get the order object
    $order = new WC_Order($order_id);
    $buyer_name = $order->billing_first_name . " " . $order->billing_last_name;
    $buyer_email = $order->billing_email;
    $buyer_phone = $order->billing_phone;
    $billing_address = $order->get_formatted_billing_address();
    $billing_address = str_replace(array('<br/>', '<br />', '<br>'), " ", $billing_address);
    $txn_id = $order_id;
    pdf_stamper_debug("WooCommerce transaction data: " . $buyer_name . "|" . $buyer_email . "|" . $buyer_phone . "|" . $billing_address . "|" . $txn_id, true);

    //Grab the business name from woocommerce order (post) data.
    $business_name_data = get_post_meta($order_id, '_billing_company');
    if (isset($business_name_data['0'])) {
        $customer_business_name = $business_name_data['0'];
    } else {
        $customer_business_name = "";
    }
    $src_file = $file_path;

    $line_distance = "";
    $line_space = "";
    $footer_text = "";
    $additional_params = array();
    $additional_params['transaction_id'] = $txn_id;
    $returnValue = pdf_stamper_stamp_internal_file($src_file, $buyer_name, $buyer_email, $buyer_phone, $billing_address, $customer_business_name, $line_distance, $line_space, $footer_text, $additional_params);

    list ($status, $value) = explode("\n", $returnValue);
    $message = "";
    if (strpos($status, "Success!") !== false) {
        
        //Update download count and permission
        $args = array(
            'product_id' => $product_id,
            'order_key' => $order_key,
            'email' => $email,
            'download_id' => $download_id,
        );
        $download_data = stamper_woocommerce_get_download_data($args);
        WC_Download_Handler::count_download($download_data);

        //Serve the download
        $file_url = trim($value);
        pdf_stamper_debug("File stamped successfully! Stamped file URL: " . $file_url, true);
        header('Location: ' . $file_url);
        exit;
    } else {
        $error_msg = "WooCommerce and PDF Stamper integration - An error occured while trying to stamp the PDF file! Error details: " . $value;
        echo $error_msg;
        pdf_stamper_debug($error_msg, false);
        exit;
    }
}

function stamper_woocommerce_get_download_data($args) {
    global $wpdb;

    $query = "SELECT * FROM " . $wpdb->prefix . "woocommerce_downloadable_product_permissions ";
    $query .= "WHERE user_email = %s ";
    $query .= "AND order_key = %s ";
    $query .= "AND product_id = %s ";

    if ($args['download_id']) {
        $query .= "AND download_id = %s ";
    }

    return $wpdb->get_row($wpdb->prepare($query, array($args['email'], $args['order_key'], $args['product_id'], $args['download_id'])));
}

/********* End WooCommerce Integration **********/
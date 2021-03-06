<?php

/* Use pdf_stamper_execute_stamping($data) to stamp a file from another plugin or addon. */

function pdf_stamper_stamp_internal_file($source_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $distance_from_bottom, $line_space, $footer_text, $additional_params = '') {
    ob_start();
    //execute stamping
    pdf_stamper_process_internal_file_stamping($source_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $distance_from_bottom, $line_space, $footer_text, $additional_params);
    //collect output
    $output = ob_get_contents();
    ob_end_clean();
    //process output
    //print_r($output);
    return $output;
}

function pdf_stamper_process_internal_file_stamping($source_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $distance_from_bottom, $line_space, $footer_text, $additional_params = '') {
    pdf_stamper_debug("Processing file stamping request via internal API.", true);
    $received_src_file = $source_file;

    if (!empty($additional_params)) {
        //This is an internal API request from eStore plugin so ignore the URL check since eStore can accept a file path
    } else if (!pdf_stamper_check_if_url_missing_http($received_src_file)) {
        echo "Error! \n";
        echo "The file URL is missing the http keyword! A properly formatted URL must have the http keyword in it.";
        return false;
    }

    //Start the process
    if (preg_match("/http/", $received_src_file)) {
        //Get the absolute or relative path for the file
        $src_file_to_process = pdf_stamper_get_abs_path_from_src_file($received_src_file);
    } else {
        $src_file_to_process = $received_src_file;
    }
    //echo "<br />File to stamp:".$src_file_to_process;	    

    $dest_dir = pdf_stamper_get_abs_path_from_src_file(get_option('wp_pdf_stamped_files_dest_dir'));
    if (empty($dest_dir)) {
        //$dest_dir = pdf_stamp_get_domain_path_from_url(WP_PDF_STAMP_URL).'/stamped-files';
        //$dest_dir = pdf_stamp_get_relative_url_from_full_url(WP_PDF_STAMP_URL).'/stamped-files';
        $dest_dir = pdf_stamper_get_abs_path_from_src_file(WP_PDF_STAMP_URL) . '/stamped-files';
    }

    if (empty($footer_text)) {
        $footer_text_tmp = get_option('wp_pdf_stamp_line_template');
        $footer_text = html_entity_decode($footer_text_tmp, ENT_COMPAT, "UTF-8");
    }

    //Extract any additional parameters that was passed via the $additional_params array  
    $transaction_id = "";
    $product_name = "";
    if (!empty($additional_params)) {
        $transaction_id = isset($additional_params['transaction_id'])? $additional_params['transaction_id'] : '';
        $product_name = isset($additional_params['product_name'])? $additional_params['product_name'] : '';
        $ip_address = isset($additional_params['ip_address'])? $additional_params['ip_address'] : '';
    }

    if(empty($ip_address)){
        $ip_address = pdf_stamp_get_user_ip();
    }
    
    //Replace the tags. Allowed tags are {customer_name}, {customer_email}, {customer_phone}, {customer_address}, {customer_business_name}, {transaction_id}, {date}
    $firstname = "";
    $lastname = "";
    if (!empty($customer_name)) {
        list($firstname, $lastname) = explode(' ', $customer_name);
    }
    $current_date_val = date(get_option('date_format')); //date("F j, Y");
    if (empty($current_date_val)) {
        $current_date_val = date("F j, Y");
    }
    $current_date_val = apply_filters('stamper_current_date_tag_filter', $current_date_val);

    //Do the standard dynamic email tags
    $tags = array("{customer_name}", "{customer_email}", "{customer_phone}", "{customer_address}", "{customer_business_name}", "{first_name}", "{last_name}", "{transaction_id}", "{date}", "{product_name}", "{ip_address}");
    $vals = array($customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $firstname, $lastname, $transaction_id, $current_date_val, $product_name, $ip_address);
    $footer_text = stripslashes(str_replace($tags, $vals, $footer_text));
    
    //Do the encoded dynamic email tags
    $encoded_tags = array("{encoded_customer_name}", "{encoded_customer_email}", "{encoded_customer_phone}", "{encoded_customer_address}", "{encoded_customer_business_name}", "{encoded_first_name}", "{encoded_last_name}");
    $encoded_vals = array(base64_encode($customer_name), base64_encode($customer_email), base64_encode($customer_phone), base64_encode($customer_address), base64_encode($customer_business), base64_encode($firstname), base64_encode($lastname));
    $footer_text = stripslashes(str_replace($encoded_tags, $encoded_vals, $footer_text));

    $footer_text = apply_filters('filter_stamper_text_after_replacing_tags', $footer_text, $additional_params);

    $color_red = get_option('wp_pdf_stamp_font_color_red');
    $color_green = get_option('wp_pdf_stamp_font_color_green');
    $color_blue = get_option('wp_pdf_stamp_font_color_blue');
    $font_family = "arial"; //get_option('wp_pdf_stamp_font_family');   
    $font_size = get_option('wp_pdf_stamp_font_size');
    $alignment = get_option('wp_pdf_stamp_line_alignment');

    $stamp_position = get_option('pdf_stamper_stamp_position');
    if (empty($stamp_position)) {
        $stamp_position = '1';
    }

    $distance_from_header = get_option('wp_pdf_stamp_line_distance_header'); //distance from header 
    if (empty($distance_from_header)) {
        $distance_from_header = '15';
    }

    if (empty($distance_from_bottom)) {//Distance from bottom
        $distance_from_bottom = "-" . get_option('wp_pdf_stamp_line_distance');
    } else {
        $distance_from_bottom = "-" . $distance_from_bottom;
    }
    if (empty($line_space)) {//Lince space
        $line_space = get_option('wp_pdf_stamp_line_spacing');
    }

    //Font style not longer supported
//    $font_style = "";
//    if (get_option('wp_pdf_stamp_font_style_bold')) {
//        $font_style .= "B";
//    }
//    if (get_option('wp_pdf_stamp_font_style_italic')) {
//        $font_style .= "I";
//    }
//    if (get_option('wp_pdf_stamp_font_style_underline')) {
//        $font_style .= "U";
//    }

    //Security/Encryption settings
    $allow_print = false;
    $allow_modify = false;
    $allow_copy = false;
    $userpass = '';
    if (get_option('wp_pdf_stamp_allow_print')) {
        $allow_print = true;
    }
    if (get_option('wp_pdf_stamp_allow_modify')) {
        $allow_modify = true;
    }
    if (get_option('wp_pdf_stamp_allow_copy')) {
        $allow_copy = true;
    }
    $userpass = get_option('wp_pdf_stamp_file_userpass');
    if (empty($userpass)) {
        if (get_option('wp_pdf_stamp_use_email_as_password') == '1') {
            $userpass = $customer_email;
        } else {
            $userpass = "";
        }
    }
    $ownerpass = get_option('wp_pdf_stamp_file_ownerpass');

    /* Disable PHP execution timer before the stamping process starts */
    if (WP_PDF_STAMP_DISABLE_PHP_EXECUTION_TIMER == '1') {//attempt to disable the execution timer
        // Get current maximum execution time.
        $old_max_time = ini_get('max_execution_time');
        // Attempt to disable the timer.
        @set_time_limit(0);
        // Now get current maximum execution time.
        $new_max_time = ini_get('max_execution_time');
        if ($new_max_time > 0) {
            echo "Error!\n";
            echo "<br />Could not disable the PHP execution timer! The script will terminate if it takes more than " . $new_max_time . " seconds.<br />";
            echo "<br />PDF stamper will terminate stamping now.<br />";
            return;
        } else {
            //Execution timer was successfully disabled. stamping will go ahead while the executio timer is disabled.
        }
    }
    /* End of PHP timer disabling */
    
    //Include method 3. This is the only option for PHP7.0+ version.
    include('stamp-api-include-3.php');
    
}

if (!function_exists('pdf_stamper_should_page_be_stamped')) {
    $pdf_stamper_start_page = get_option('wp_pdf_start_stamping_from_page_number');
    if (empty($pdf_stamper_start_page)) {
        $pdf_stamper_start_page = 1;
    }
    $pdf_stamper_end_page = get_option('wp_pdf_stamping_end_page_number');

    function pdf_stamper_should_page_be_stamped($pageNo, $totalPageCount) {
        global $pdf_stamper_start_page, $pdf_stamper_end_page;
        if (empty($pdf_stamper_start_page)) {
            $pdf_stamper_start_page = 1;
        }
        if (is_numeric($pdf_stamper_end_page)) {
            if ($pageNo >= $pdf_stamper_start_page && $pageNo <= $pdf_stamper_end_page) {
                return true;
            }
        } else if ($pageNo >= $pdf_stamper_start_page) {
            return true;
        }
        return false;
    }

}

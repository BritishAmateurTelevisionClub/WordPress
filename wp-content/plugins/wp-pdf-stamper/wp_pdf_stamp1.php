<?php

include_once('stamper_debug_handler.php');
include_once('wp_pdf_stamp_utility_functions.php');
include_once('pdf_stamper_db_access.php');
include_once('api/stamp_api_internal.php');
include_once('api/stamp_api_function.php');
include_once('pdf_stamp_shortcodes.php');
include_once('pdf_stamp_scheduled_tasks.php');
include_once('pdf_stamp_3rd_party_integration.php');
include_once('pdf_stamp_misc_functions.php');

add_action('init', 'pdf_stamper_init_tasks');

function pdf_stamper_init_tasks() {
    pdf_stamper_misc_init_time_shortcodes_tasks();
    pdf_stamper_clickbank_payment_prcessor_listener();
    pdf_stamper_paypal_ipn_listener();
}

add_action('plugins_loaded', 'wp_pdf_stamper_handle_plugins_loaded_hook');

function wp_pdf_stamper_handle_plugins_loaded_hook() {
    if (is_admin()) {//Check if DB needs to be updated
        if (get_option('pdf_stamper_db_version') != WP_PDF_STAMPER_DB_VERSION) {
            require_once('pdf_stamper_installer.php');
            wp_pdf_stamper_run_installer();
        }
    }
}

// Insert the options page to the admin menu
if (is_admin()) {
    add_action('admin_menu', 'registerWpPdfStampOptionsPage');
}

function pdf_stamper_paypal_ipn_listener() {
    if (isset($_REQUEST['pdfs_pp_ipn']) && $_REQUEST['pdfs_pp_ipn'] == "process") {
        include_once('api/ipn_handler.php');
        exit;
    }
}

add_shortcode('pdf_stamper_clickbank_purchase', 'pdf_stamper_clickbank_purchase_handler');

function pdf_stamper_clickbank_purchase_handler($atts) {
    $output = "";
    if (isset($_SESSION['pdf_stamper_purchase_result'])) {
        $output = $_SESSION['pdf_stamper_purchase_result'];
    }
    return $output;
}

function pdf_stamper_clickbank_payment_prcessor_listener() {
    $output = "";
    //Clickbank Integration
    if (isset($_REQUEST['cname']) && isset($_REQUEST['cbreceipt'])) {
        $wp_ps_config = PDF_Stamper_Config::getInstance();
        
        pdf_stamper_debug("Clickbank payment data received. Checking details... ", true);
        if (get_option('wp_pdf_stamp_allow_clickbank_integration') != '1') {
            pdf_stamper_debug("Clickbank integration is not enabled in the PDT stamper plugin settings.", false);
            echo '<p style="color:red;">Clickbank integration is not enabled in the PDT stamper plugin settings</p>'; //Clickbank integration is not enabled in the settings
            exit;
        }

        if (!wp_pdf_stamper_cb_valid()) {
            pdf_stamper_debug("Clickbank payment data validation failed! This request will not be processed", false);
            echo '<p style="color:red;">Error! The ClickBank link security check failed. Please check your clickbank secret key and make sure it has been entered correctly in the settings menu of the PDF stamper plugin.</p>';
            exit;
        }
        //check that the request is not more than 24 hours old
        //$time=$_REQUEST['time'];	
        //convert to string and do difference with current time()
        //make sure it is not more than 60*60*24
        global $pdf_stamper_clickbank_items;
        global $pdf_stamper_clickbank_success_message, $pdf_stamper_clickbank_failure_message;

        $customer_name = $_REQUEST['cname'];
        $customer_email = $_REQUEST['cemail'];
        $txn_id = $_REQUEST['cbreceipt'];
        $item_id = $_REQUEST['item'];

        include_once('api/clickbank-config.php');
        $postURL = WP_PDF_STAMP_URL . "/api/stamp_api.php";
        // The Secret key
        $secretKey = get_option('wp_pdf_stamp_secret_key');
        // The site URL
        $domainURL = $_SERVER['SERVER_NAME'];

        $stamping_success = true;

        $files_to_be_stamped = $pdf_stamper_clickbank_items[$item_id];
        $files_to_be_stamped_array = explode(",", $files_to_be_stamped);
        //print_r($files_to_be_stamped_array);
        foreach ($files_to_be_stamped_array as $file_to_be_stamped) {
            $src_file = $file_to_be_stamped;

            // prepare the data
            $data = array();
            $data['secret_key'] = $secretKey;
            $data['requested_domain'] = $domainURL;
            $data['source_file'] = $src_file;
            $data['customer_name'] = $_REQUEST['cname'];
            $data['customer_email'] = $_REQUEST['cemail'];
            $data['customer_phone'] = 'No phone number provided';
            $data['customer_address'] = 'No address provided';
            $data['customer_business_name'] = 'No business name provided';
            $data['transaction_id'] = $txn_id;

            $line_distance = "";
            $line_space = "";
            $footer_text = "";
            $additional_params = array();
            $additional_params['transaction_id'] = $txn_id; //send transaction_id via additional param data			

            if (WP_PDF_STAMP_DO_NOT_USE_CURL == '1') {//Use internal API							 			
                $returnValue = pdf_stamper_stamp_internal_file($src_file, $data['customer_name'], $data['customer_email'], $data['customer_phone'], $data['customer_address'], $data['customer_business_name'], $line_distance, $line_space, $footer_text, $additional_params);
            } else {// send data to post URL using CURL			    
                $ch = curl_init($postURL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $returnValue = curl_exec($ch);
                curl_close($ch);
            }

            list ($status, $value) = explode("\n", $returnValue);
            if (strpos($status, "Success!") !== false) {
                $file_url .= trim($value);
                $file_url .= "\n";
            } else {
                $stamping_success = false;
            }
        }

        if ($stamping_success) {
            pdf_stamper_debug("Clickbank integration - Stamping was successful", true);
            $firstname = "";
            $lastname = "";
            if (!empty($customer_name)) {
                list($firstname, $lastname) = explode(' ', $customer_name);
            }            
            $tags = array("{customer_name}", "{first_name}", "{last_name}", "{product_link}");
            $vals = array($customer_name, $firstname, $lastname, $file_url);
            $buyer_email_body = stripslashes(str_replace($tags, $vals, $pdf_stamper_clickbank_buyer_email_body));

            //Buyer email notification            
            $pdf_stamper_clickbank_from_email = $wp_ps_config->getValue('wp_pdf_stamper_from_email_address');//It will be used to send emails to the buyer.
            $pdf_stamper_clickbank_subject = $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_subj'); //Email subject
            $pdf_stamper_clickbank_buyer_email_body = $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_body'); //The buyer email body.            
            if (!empty($pdf_stamper_clickbank_from_email)) {
                $headers = 'From: ' . $pdf_stamper_clickbank_from_email . "\r\n";
            }
            wp_mail($customer_email, $pdf_stamper_clickbank_subject, $buyer_email_body, $headers);
            pdf_stamper_debug("Clickbank integration - Customer notification email sent to: " . $customer_email, true);

            //Handle seller email notification
            $seller_email_address = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_address');
            $seller_email_subject = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_subj');
            $seller_email_body = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_body');

            if (!empty($seller_email_address)) {//the value of this var come from the global scope in the config file
                $tags = array("{first_name}", "{last_name}", "{product_link}", "{buyer_email}");
                $vals = array($firstname, $lastname, $file_url, $buyer_email_body);
                $seller_email_body = stripslashes(str_replace($tags, $vals, $seller_email_body));
                wp_mail($seller_email_address, $seller_email_subject, $seller_email_body, $headers);
                pdf_stamper_debug('Clickbank integration - Email successfully sent to seller: ' . $seller_email_address, true);
            } else {
                pdf_stamper_debug('Clickbank integration - Seller email address field is empty. No email will be sent to the seller.', true);
            }
        
            $output .= $pdf_stamper_clickbank_success_message;
        } else {
            pdf_stamper_debug("Clickbank integration - Stamping failed: " . $value, true);
            $output .= $pdf_stamper_clickbank_failure_message . $value;
        }
        $_SESSION['pdf_stamper_purchase_result'] = $output; //Save the result so it can be displayed where the shortcode is
    }
}

function wp_pdf_stamper_cb_valid() {
    $key = get_option('wp_pdf_stamp_clickbank_secret_key'); //Clickbank link security SECRET KEY;
    if (empty($key)) {
        echo '<p style="color:red;">Error! You did not specify a clickbank link security secret key in the settings menu of this plugin!</p>';
    }
    $rcpt = $_REQUEST['cbreceipt'];
    $time = $_REQUEST['time'];
    $item = $_REQUEST['item'];
    $cbpop = $_REQUEST['cbpop'];
    $xxpop = sha1("$key|$rcpt|$time|$item");
    $xxpop = strtoupper(substr($xxpop, 0, 8));
    if ($cbpop == $xxpop)
        return 1;
    else
        return 0;
}

// Display The Options Page
if (is_admin()) {

    function registerWpPdfStampOptionsPage() {
        add_menu_page("PDF Stamper", "PDF Stamper", PDF_STAMPER_MANAGEMENT_PERMISSION, __FILE__, "wpPdfStampOptionsMenu");
        add_submenu_page(__FILE__, "Settings", "Settings", PDF_STAMPER_MANAGEMENT_PERMISSION, __FILE__, "wpPdfStampOptionsMenu");
        add_submenu_page(__FILE__, "Manual Stamping", "Manual Stamping", PDF_STAMPER_MANAGEMENT_PERMISSION, 'manual_stamp_page', "manual_stamp_menu");
        add_submenu_page(__FILE__, "Manage Stamped Files", "Manage Stamped Files", PDF_STAMPER_MANAGEMENT_PERMISSION, 'manage_stamped_files_page', "manage_stamped_files_menu");
        add_submenu_page(__FILE__, "Admin Functions", "Admin Functions", PDF_STAMPER_MANAGEMENT_PERMISSION, 'stamper_admin_functions', "stamper_admin_functions_menu");
        add_submenu_page(__FILE__, "Integration Help", "Integration Help", PDF_STAMPER_MANAGEMENT_PERMISSION, 'integration_help_page', "integration_help_menu");
        add_submenu_page(__FILE__, "Product License", "Product License", PDF_STAMPER_MANAGEMENT_PERMISSION, 'stamper_license_page', "pdf_stamper_license_menu");
    }

    //Include menus
    require_once(dirname(__FILE__) . '/pdf_stamp_settings_page.php');
    require_once(dirname(__FILE__) . '/manual_stamp_page.php');
    require_once(dirname(__FILE__) . '/manage_stamped_files_page.php');
    require_once(dirname(__FILE__) . '/pdf_stamp_admin_funtions_settings.php');
    require_once(dirname(__FILE__) . '/integration_help_page.php');
    require_once(dirname(__FILE__) . '/pdf_stamper_product_license_page.php');
}

//use a function here to set the default values at activation time
/* * ****************************************************************************** */
/* * * Start! Everything to do with License verification, activation, deactivation ** */
/* * ****************************************************************************** */
define('WP_LICENSE_MGR_SECRET_KEY', '4c132da1f24a41.63429762');
define('WP_LICENSE_MGR_DEACTIVATION_POST_URL', 'http://license-manager.tipsandtricks-hq.com/wp-content/plugins/wp-license-manager/api/deactivate.php');
define('WP_LICENSE_MGR_ACTIVATION_POST_URL', 'http://license-manager.tipsandtricks-hq.com/wp-content/plugins/wp-license-manager/api/verify.php');

function wp_pdf_stamper_deactivate_license($lic) {
    // Post URL
    $postURL = WP_LICENSE_MGR_DEACTIVATION_POST_URL;
    // The Secret key
    $secretKey = WP_LICENSE_MGR_SECRET_KEY;
    // The License key
    $licenseKey = $lic; //take this input from the user
    $data = array();
    $data['secret_key'] = $secretKey;
    $data['license_key'] = $licenseKey;
    $data['registered_domain'] = $_SERVER['SERVER_NAME'];

    // send data to post URL
//    $ch = curl_init ($postURL);
//    curl_setopt ($ch, CURLOPT_POST, true);
//    curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
//    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);    
//    $returnValue = curl_exec ($ch);
    //Alternate method of API query
    $post_url_with_data = $postURL . '/?secret_key=' . $secretKey . '&license_key=' . $licenseKey . '&registered_domain=' . $_SERVER['SERVER_NAME'];

    //$returnValue = file_get_contents($post_url_with_data);//returns a string with data
    $response = wp_remote_get($post_url_with_data); //returns an array with data. See wp_remote_get() function
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo '<p>Failed to communicate with the license server. Please contact us for help. Error message: ' . $error_message . '</p>';
    }
    $returnValue = $response['body'];

    //print_r($returnValue);
    list ($result, $msg, $additionalMsg) = explode("\n", $returnValue);
    $retData = array();
    $retData['result'] = $result;
    $retData['msg'] = $msg;
    $retData['additional_msg'] = $additionalMsg;
    return $retData;
}

function wp_pdf_stamper_liceinse_verify($lic) {
    // Post URL
    $postURL = WP_LICENSE_MGR_ACTIVATION_POST_URL;
    // The Secret key
    $secretKey = WP_LICENSE_MGR_SECRET_KEY;
    // The License key
    $licenseKey = $lic;
    $data = array();
    $data['secret_key'] = $secretKey;
    $data['license_key'] = $licenseKey;
    $data['registered_domain'] = $_SERVER['SERVER_NAME'];

    // send data to post URL
//    $ch = curl_init ($postURL);
//    curl_setopt ($ch, CURLOPT_POST, true);
//    curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
//    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
//    $returnValue = curl_exec ($ch);

    $post_url_with_data = $postURL . '/?secret_key=' . $secretKey . '&license_key=' . $licenseKey . '&registered_domain=' . $_SERVER['SERVER_NAME'];
    //$returnValue = file_get_contents($post_url_with_data);//returns a string with data
    $response = wp_remote_get($post_url_with_data); //returns an array with data. See wp_remote_get() function
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo '<p>Failed to communicate with the license server. Please contact us for help. Error message: ' . $error_message . '</p>';
    }
    $returnValue = $response['body'];

    //print_r($returnValue);
    list ($result, $msg, $additionalMsg) = explode("\n", $returnValue);
    $retData = array();
    $retData['result'] = $result;
    $retData['msg'] = $msg;
    $retData['additional_msg'] = $additionalMsg;
    return $retData;
}

function wp_pdf_stamper_is_license_valid() {
    $is_valid = false;
    $license_key = get_option('xiuyAmIn_wp_pdf_stamper_lic_key');
    if (!empty($license_key)) {
        $is_valid = true;
    }
    return $is_valid;
}

function wp_pdf_stamper_lic_warning() {
    if (!wp_pdf_stamper_is_license_valid()) {
        echo '<div class="updated fade"><p>WP PDF Stamper is almost ready. You must provide a valid License key. <a href="admin.php?page=stamper_license_page" class="button-primary">Click Here</a> to enter your license key and start using the plugin.</p></div>';
    }
}

add_action('admin_notices', 'wp_pdf_stamper_lic_warning');
/*********************************************************************************/
/*** End! Everything to do with License verification, activation, deactivation ***/
/*********************************************************************************/

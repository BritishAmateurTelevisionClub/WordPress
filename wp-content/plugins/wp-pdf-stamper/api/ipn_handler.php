<?php

if (!defined('ABSPATH')) {
    include_once('../../../../wp-load.php');
}
include_once('ipn_customization_config.php');

$error_msg = '';

class pdf_stamper_paypal_ipn_handler {

    var $last_error;                 // holds the last error encountered
    var $ipn_log;                    // bool: log IPN results to text file?
    var $ipn_log_file;               // filename of the IPN log
    var $ipn_response;               // holds the IPN response from paypal
    var $ipn_data = array();         // array contains the POST values for IPN
    var $fields = array();           // array holds the fields to submit to paypal
    var $sandbox_mode = false;

    function pdf_stamper_paypal_ipn_handler() {
        $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        $this->last_error = '';
        $this->ipn_log_file = WP_PDF_STAMP_PATH . 'api/ipn_handle_debug.log';
        $this->ipn_response = '';
    }

    function pdf_stamper_stamp_file() {
        // Check Product Name , Price , Currency , Receivers email ,
        global $error_msg;
        global $pdf_stamper_paypal_custom_file;

        $wp_ps_config = PDF_Stamper_Config::getInstance();

        // Read the IPN and validate
        $payment_status = $this->ipn_data['payment_status'];
        if (!empty($payment_status)) {
            if ($payment_status != "Completed" && $payment_status != "Processed" && $payment_status != "Refunded") {
                $error_msg .= 'Funds have not been cleared yet. File will stamped and emailed when the fund clears!';
                $this->debug_log($error_msg, false);
                return false;
            }
        }

        $transaction_type = $this->ipn_data['txn_type'];
        $transaction_id = $this->ipn_data['txn_id'];
        $transaction_subject = $this->ipn_data['transaction_subject'];
        $gross_total = $this->ipn_data['mc_gross'];
        if ($gross_total < 0) {
            // This is a refund or reversal
            $this->debug_log('This is a refund. Refund amount: ' . $gross_total, true);
            return true;
        }

        //Check for duplicate notification due to server setup/configuration issue
        $txn_id = $this->ipn_data['txn_id'];
        $resultset = WpPdfStamperDbAccess::findAll(WP_PDF_STAMPED_FILES_TABLE_NAME, " txn_id = '$txn_id'");
        if ($resultset) {
            $this->debug_log('The transaction ID already exists in the database. So this seems to be a duplicate transaction notification. This usually happens with bad server setup.', false);
            return true; //No need to be alarmed			
        }

        if ($transaction_type == "cart") {
            $this->debug_log('Transaction Type: Shopping Cart', true);
            // Cart Items
            $num_cart_items = $this->ipn_data['num_cart_items'];
            $this->debug_log('Number of Cart Items: ' . $num_cart_items, true);

            $i = 1;
            $cart_items = array();
            while ($i < $num_cart_items + 1) {
                $item_number = $this->ipn_data['item_number' . $i];
                $item_name = $this->ipn_data['item_name' . $i];
                $quantity = $this->ipn_data['quantity' . $i];
                $mc_gross = $this->ipn_data['mc_gross_' . $i];
                $mc_currency = $this->ipn_data['mc_currency'];

                $current_item = array(
                    'item_number' => $item_number,
                    'item_name' => $item_name,
                    'quantity' => $quantity,
                    'mc_gross' => $mc_gross,
                    'mc_currency' => $mc_currency,
                );

                array_push($cart_items, $current_item);
                $i++;
            }
        } else if (($transaction_type == "subscr_signup")) {
            $this->debug_log('Subscription signup IPN received... nothing to do here.', true);
            return true;
        } else if (($transaction_type == "subscr_cancel") || ($transaction_type == "subscr_eot") || ($transaction_type == "subscr_failed")) {
            $this->debug_log('Subscription cancellation IPN received... nothing to do here', true);
            return true;
        } else {
            $cart_items = array();
            $this->debug_log('Transaction Type: Buy Now/Subscribe', true);
            $item_number = $this->ipn_data['item_number'];
            $item_name = $this->ipn_data['item_name'];
            $quantity = $this->ipn_data['quantity'];
            $mc_gross = $this->ipn_data['mc_gross'];
            $mc_currency = $this->ipn_data['mc_currency'];

            $current_item = array(
                'item_number' => $item_number,
                'item_name' => $item_name,
                'quantity' => $quantity,
                'mc_gross' => $mc_gross,
                'mc_currency' => $mc_currency,
            );

            array_push($cart_items, $current_item);
        }

        $this->debug_log('Stamping the PDF file using API.', true);

        if (!empty($pdf_stamper_paypal_custom_file)) {
            $src_file = $pdf_stamper_paypal_custom_file;
            $this->debug_log("Using the custom PDF File URL for stamping purpose", true);
        } else {
            $src_file = $this->ipn_data['custom'];
        }
        $this->debug_log("Source File URL is: " . $src_file, true);

        $postURL = WP_PDF_STAMP_URL . "/api/stamp_api.php";
        $secretKey = get_option('wp_pdf_stamp_secret_key'); // The Secret key
        $domainURL = $_SERVER['SERVER_NAME']; // The site URL
        // prepare the data
        $data = array();
        $data['secret_key'] = $secretKey;
        $data['requested_domain'] = $domainURL;
        $data['source_file'] = trim($src_file);
        $data['customer_name'] = $this->ipn_data['first_name'] . " " . $this->ipn_data['last_name'];
        $data['customer_email'] = $this->ipn_data['payer_email'];
        $data['customer_phone'] = $this->ipn_data['contact_phone'];
        $data['customer_address'] = $this->ipn_data['address_street'] . ", " . $this->ipn_data['address_city'] . ", " . $this->ipn_data['address_state'] . " " . $this->ipn_data['address_zip'] . ", " . $this->ipn_data['address_country'];
        $data['customer_business_name'] = $this->ipn_data['payer_business_name'];
        $data['transaction_id'] = $this->ipn_data['txn_id'];

        if (WP_PDF_STAMP_DO_NOT_USE_CURL == '1') {
            $this->debug_log("Using the internal API to stamp the file", true);
            $returnValue = pdf_stamper_stamp_internal_file($src_file, $data['customer_name'], $data['customer_email'], $data['customer_phone'], $data['customer_address'], $data['customer_business_name'], "", "", "", $data);
        } else {
            $this->debug_log("Using the POST API to stamp the file", true);
            // send data to post URL
            $ch = curl_init($postURL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $returnValue = curl_exec($ch);
        }

        list ($status, $value) = explode("\n", $returnValue);
        $message = "";
        if (strpos($status, "Success!") !== false) {
            $file_url = trim($value);
            $message .= "Files stamped successfully! Stamped file URL: " . $file_url;
            $this->debug_log($message, true);
        } else {
            $message .= "An error occured while trying to stamp the file! Error details: " . $value;
            $this->debug_log($message, false);
            return false;
        }

        //The from email address that will be used to send emails to the buyer. By default it will use the paypal receiver email address.
        $from_email = $wp_ps_config->getValue('wp_pdf_stamper_from_email_address');
        //Email subject
        $subject = $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_subj');
        //The buyer email body. The first name, last name and stamped file's URL will be replaced with the text with braces {} 
        $buyer_email_body = $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_body');

        if (empty($from_email)) {
            $from_email = $this->ipn_data['receiver_email'];
        }
        $this->debug_log('Preparing to send email. From email address value: ' . $from_email, true);

        $tags = array("{first_name}", "{last_name}", "{product_link}");
        $vals = array($this->ipn_data['first_name'], $this->ipn_data['last_name'], $file_url);
        $buyer_email_body = stripslashes(str_replace($tags, $vals, $buyer_email_body));
        $headers = 'From: ' . $from_email . "\r\n";
        wp_mail($this->ipn_data['payer_email'], $subject, $buyer_email_body, $headers);
        $this->debug_log('Email successfully sent to buyer: ' . $this->ipn_data['payer_email'], true);

        //Handle seller email notification
        $seller_email_address = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_address');
        $seller_email_subject = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_subj');
        $seller_email_body = $wp_ps_config->getValue('wp_pdf_stamper_seller_email_body');
        
        if (!empty($seller_email_address)) {//the value of this var come from the global scope in the config file
            $tags = array("{first_name}", "{last_name}", "{product_link}", "{buyer_email}");
            $vals = array($this->ipn_data['first_name'], $this->ipn_data['last_name'], $file_url, $buyer_email_body);
            $seller_email_body = stripslashes(str_replace($tags, $vals, $seller_email_body));
            wp_mail($seller_email_address, $seller_email_subject, $seller_email_body, $headers);
            $this->debug_log('Email successfully sent to seller: ' . $seller_email_address, true);
        } else {
            $this->debug_log('Seller email address field is empty. No email will be sent to the seller.', true);
        }            

        // Do Post operation and cleanup if needed        
        return true;
    }

    function validate_ipn() {
        // generate the post string from the _POST vars aswell as load the _POST vars into an arry
        $post_string = '';
        foreach ($_POST as $field => $value) {
            $this->ipn_data["$field"] = $value;
            $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }

        $this->post_string = $post_string;
        $this->debug_log('Post string : ' . $this->post_string, true);

        //IPN validation check
        if($this->validate_ipn_using_remote_post()){
            //We can also use an alternative validation using the validate_ipn_using_curl() function
            return true;
        } else {
            return false;
        }
    }

    function validate_ipn_using_remote_post(){
        $this->debug_log( 'Checking if PayPal IPN response is valid', true);
        
        // Get received values from post data
        $validate_ipn = array( 'cmd' => '_notify-validate' );
        $validate_ipn += wp_unslash( $_POST );

        // Send back post vars to paypal
        $params = array(
                'body'        => $validate_ipn,
                'timeout'     => 60,
                'httpversion' => '1.1',
                'compress'    => false,
                'decompress'  => false,
                'user-agent'  => 'WP PDF Stamper/' . WP_PDF_STAMP_VERSION
        );

        // Post back to get a response.
        $connection_url = $this->sandbox_mode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
        $this->debug_log('Connecting to: ' . $connection_url, true);
        $response = wp_safe_remote_post( $connection_url, $params );

        //The following two lines can be used for debugging
        //$this->debug_log( 'IPN Request: ' . print_r( $params, true ) , true);
        //$this->debug_log( 'IPN Response: ' . print_r( $response, true ), true);

        // Check to see if the request was valid.
        if ( ! is_wp_error( $response ) && strstr( $response['body'], 'VERIFIED' ) ) {
            $this->debug_log('IPN successfully verified.', true);
            return true;
        }

        // Invalid IPN transaction. Check the log for details.
        $this->debug_log('IPN validation failed.', false);
        if ( is_wp_error( $response ) ) {
            $this->debug_log('Error response: ' . $response->get_error_message(), false);
        }
        return false;        
    }
    
    function log_ipn_results($success) {
        if (!$this->ipn_log)
            return;  // is logging turned off?
        // Timestamp
        $text = '[' . date('m/d/Y g:i A') . '] - ';

        // Success or failure being logged?
        if ($success)
            $text .= "SUCCESS!\n";
        else
            $text .= 'FAIL: ' . $this->last_error . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from Paypal:\n";
        foreach ($this->ipn_data as $key => $value) {
            $text .= "$key=$value, ";
        }

        // Log the response from the paypal server
        $text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;

        // Write to log
        $fp = fopen($this->ipn_log_file, 'a');
        fwrite($fp, $text . "\n\n");

        fclose($fp);  // close file
    }

    function debug_log($message, $success, $end = false) {

        if (!$this->ipn_log)
            return;  // is logging turned off?
        // Timestamp
        $text = '[' . date('m/d/Y g:i A') . '] - ' . (($success) ? 'SUCCESS :' : 'FAILURE :') . $message . "\n";

        if ($end) {
            $text .= "\n------------------------------------------------------------------\n\n";
        }

        // Write to log
        $fp = fopen($this->ipn_log_file, 'a');
        fwrite($fp, $text);
        fclose($fp);  // close file
    }

}

// Start of IPN handling (script execution)

$ipn_handler_instance = new pdf_stamper_paypal_ipn_handler();

$wp_ps_config = PDF_Stamper_Config::getInstance();
$ps_debug_enabled = $wp_ps_config->getValue('enable_pdf_stamper_debug');
if ($ps_debug_enabled == '1') {
    echo 'Debug is enabled. Check the ipn_handle_debug.log file for debug output.';
    $ipn_handler_instance->ipn_log = true;
    if (empty($_POST)) {
        $ipn_handler_instance->debug_log('This debug line was generated because you entered the URL of the ipn handling script in the browser.', true, true);
        exit;
    }
}

$ps_sandbox = $wp_ps_config->getValue('enable_pdf_stamper_sandbox');
if ($ps_sandbox == '1') { // Enable sandbox testing
    $ipn_handler_instance->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    $ipn_handler_instance->sandbox_mode = true;
}

$ipn_handler_instance->debug_log('Paypal Class Initiated by ' . $_SERVER['REMOTE_ADDR'], true);

// Validate the IPN
if ($ipn_handler_instance->validate_ipn()) {
    $ipn_handler_instance->debug_log('Stamping PDF file.', true);

    if (!$ipn_handler_instance->pdf_stamper_stamp_file()) {
        $ipn_handler_instance->debug_log('Stamping of PDF file failed.', false);
    }
}
$ipn_handler_instance->debug_log('Paypal class finished.', true, true);

<?php

function pdf_stamper_execute_stamping($data) {

    pdf_stamper_debug("pdf_stamper_execute_stamping() called.", true);
    
    $additional_params = array();
    $additional_params['transaction_id'] = $data['transaction_id'];
    $additional_params['product_name'] = $data['product_name'];
    
    $returnValue = pdf_stamper_stamp_internal_file($data['source_file'], $data['customer_name'], $data['customer_email'], $data['customer_phone'], $data['customer_address'], $data['customer_business_name'], "", "", "", $additional_params);

    list ($status, $value) = explode("\n", $returnValue);
    if (strpos($status, "Success!") !== false) {
        $file_url = trim($value);        
        pdf_stamper_debug("Success! Stamped file URL: " . $file_url, true);
        return $file_url;
    } else {
        pdf_stamper_debug("Error! Details: " . $value, false);
        return "";
    }
}
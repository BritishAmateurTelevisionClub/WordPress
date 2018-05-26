<?php

function pdf_stamper_debug($message,$success,$end=false)
{
	$wp_ps_config = PDF_Stamper_Config::getInstance();
	$debug_enabled = $wp_ps_config->getValue('enable_pdf_stamper_debug');
    if ($debug_enabled != '1') return;
    
    // Timestamp
    $text = '['.date('m/d/Y g:i A').'] - '.(($success)?'SUCCESS :':'FAILURE :').$message. "\n";
    if ($end) {
    	$text .= "\n------------------------------------------------------------------\n\n";
    }
    // Write to log
    $debug_log_file_name = WP_PDF_STAMP_PATH.'pdf_stamper_debug.log';
    $fp=fopen($debug_log_file_name,'a');
    fwrite($fp, $text );
    fclose($fp);
}

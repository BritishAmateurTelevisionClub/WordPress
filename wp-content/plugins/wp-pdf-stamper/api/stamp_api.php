<?php

if(isset($_REQUEST['secret_key']))
{
	$secret_key_received = $_REQUEST['secret_key'];
	$source_file_received = $_REQUEST['source_file'];
}

if(!empty($secret_key_received) && !empty($source_file_received))
{
    include_once('../../../../wp-load.php');
    $right_secret_key =  get_option('wp_pdf_stamp_secret_key');
    $right_req_domain = $_SERVER['SERVER_NAME'];
   
    //$received_req_domain = $_POST['requested_domain'];
    if ($secret_key_received != $right_secret_key)
    {
        echo "Error! \n";
        echo "Secret key is invalid\n";
        exit;
    }

    //Stamp the PDF
	include_once('../wp_pdf_stamp_utility_functions.php');
	include_once('../pdf_stamper_db_access.php');        

    //Get all the input data   
	$distance_from_bottom = $_REQUEST['distance_from_bottom'];
	$line_space = $_REQUEST['line_space'];
	$footer_text = $_REQUEST['footer_text'];
	$customer_name = $_REQUEST['customer_name'];
	$customer_email = $_REQUEST['customer_email'];
	$customer_phone = $_REQUEST['customer_phone'];
	$customer_address = $_REQUEST['customer_address'];
	$customer_business = $_REQUEST['customer_business_name'];
	$transaction_id = $_REQUEST['transaction_id'];	//Additional param - {transaction_id}
		
    $received_src_file = $source_file_received;
    
    //Start the process
    if(preg_match("/http/",$received_src_file))
    {
        //Get the absolute or relative path for the file
        //$src_file_to_process = pdf_stamp_convert_to_domain_path_from_src_file($received_src_file);        
        //$src_file_to_process = pdf_stamp_get_relative_url_from_full_url($received_src_file);
        $src_file_to_process = pdf_stamper_get_abs_path_from_src_file($received_src_file);
    }
    else
    {
        $src_file_to_process = $received_src_file;
    }
    //echo "<br />File to stamp:".$src_file_to_process;
    
    //$dest_dir = pdf_stamp_get_domain_path_from_url(get_option('wp_pdf_stamped_files_dest_dir'));
    $dest_dir = pdf_stamp_get_relative_url_from_full_url(get_option('wp_pdf_stamped_files_dest_dir'));
	if(empty($dest_dir))
	{
    	//$dest_dir = pdf_stamp_get_domain_path_from_url(WP_PDF_STAMP_URL).'/stamped-files';
    	$dest_dir = pdf_stamp_get_relative_url_from_full_url(WP_PDF_STAMP_URL).'/stamped-files';
	}	
	    
    if(empty($footer_text))
    {
		$footer_text_tmp = get_option('wp_pdf_stamp_line_template');
	    $footer_text = html_entity_decode($footer_text_tmp, ENT_COMPAT,"UTF-8");    
    }
	    
    //Replace the tags. Allowed tags are {customer_name}, {customer_email}, {customer_phone}, {customer_address}, {customer_business_name}
    $firstname = "";
    $lastname = "";
    if(!empty($customer_name)){
    	list($firstname,$lastname) = explode(' ',$customer_name);
    }
    $current_date_val = date(get_option('date_format'));//date("F j, Y");
    if(empty($current_date_val)){
        $current_date_val = date("F j, Y");
    }
    $tags = array("{customer_name}","{customer_email}","{customer_phone}","{customer_address}","{customer_business_name}","{first_name}","{last_name}","{transaction_id}","{date}");
	$vals = array($customer_name,$customer_email,$customer_phone,$customer_address,$customer_business,$firstname,$lastname,$transaction_id,$current_date_val);
    $footer_text = stripslashes(str_replace($tags,$vals,$footer_text));
	
    $color_red = get_option('wp_pdf_stamp_font_color_red');
    $color_green = get_option('wp_pdf_stamp_font_color_green');
    $color_blue = get_option('wp_pdf_stamp_font_color_blue');
    $font_family = "arial";//get_option('wp_pdf_stamp_font_family');   
    $font_size = get_option('wp_pdf_stamp_font_size');
    $alignment = get_option('wp_pdf_stamp_line_alignment');
    
	$stamp_position = get_option('pdf_stamper_stamp_position');
    if(empty($stamp_position)){$stamp_position = '1';}
    
    $distance_from_header = get_option('wp_pdf_stamp_line_distance_header');    
    if(empty($distance_from_bottom))
    {
    	$distance_from_bottom = "-".get_option('wp_pdf_stamp_line_distance');
    }
    else
    {
    	$distance_from_bottom = "-".$distance_from_bottom;
    }
    if(empty($line_space))
    {
    	$line_space = get_option('wp_pdf_stamp_line_spacing');
    }
    
    $font_style = "";
    if(get_option('wp_pdf_stamp_font_style_bold'))
    {
    	$font_style .= "B";
    }
    if(get_option('wp_pdf_stamp_font_style_italic'))
    {
    	$font_style .= "I";
    }
    if(get_option('wp_pdf_stamp_font_style_underline'))
    {
    	$font_style .= "U";
    }            
    //Security settings
    $allow_print = false;
    $allow_modify = false;
    $allow_copy = false;
    $userpass = '';
    if(get_option('wp_pdf_stamp_allow_print'))  
    {
    	$allow_print = true;
    }     
    if(get_option('wp_pdf_stamp_allow_modify'))  
    {
    	$allow_modify = true;
    }  
    if(get_option('wp_pdf_stamp_allow_copy'))  
    {
    	$allow_copy = true;
    }  
    $userpass = get_option('wp_pdf_stamp_file_userpass');
    if(empty($userpass))
    {
    	if(get_option('wp_pdf_stamp_use_email_as_password') == '1'){
    		$userpass = $customer_email;
    	}
    	else{
    		$userpass = "";
    	}
    }
    $ownerpass = get_option('wp_pdf_stamp_file_ownerpass');
            
    /*** Disable PHP execution timer before the stamping process starts ***/
    if(WP_PDF_STAMP_DISABLE_PHP_EXECUTION_TIMER == '1')//attempt to disable the execution timer
    {
	    // Get current maximum execution time.
		$old_max_time = ini_get('max_execution_time');			
		// Attempt to disable the timer.
		@set_time_limit(0);	
		// Now get current maximum execution time.
		$new_max_time = ini_get('max_execution_time');			
		if($new_max_time > 0)
		{
			echo "Error!\n";
			echo "<br />Could not disable the PHP execution timer! The script will terminate if it takes more than ".$new_max_time." seconds.<br />";
			echo "<br />PDF stamper will terminate stamping now.<br />";
			return;		
		}
		else
		{
			//Execution timer was successfully disabled. stamping will go ahead while the executio timer is disabled.
		}   
    }
	/*** End of PHP timer disabling ***/
	    
    //Include method 3. This is the only option for PHP7.0+ version.
    require_once('stamp-api-include-3.php');
    
}
else
{
    echo "Error! \n";
    echo "Secret key or source file information is not present in the request.\n";
}

if(!function_exists('pdf_stamper_should_page_be_stamped'))
{
	$pdf_stamper_start_page = get_option('wp_pdf_start_stamping_from_page_number');
	if(empty($pdf_stamper_start_page)){
		$pdf_stamper_start_page = 1;
	}
	$pdf_stamper_end_page = get_option('wp_pdf_stamping_end_page_number');

	function pdf_stamper_should_page_be_stamped($pageNo, $totalPageCount) 
	{
		global $pdf_stamper_start_page,$pdf_stamper_end_page;
		if(empty($pdf_stamper_start_page)){
			$pdf_stamper_start_page = 1;
		}
		if(is_numeric($pdf_stamper_end_page)){
			if($pageNo >= $pdf_stamper_start_page && $pageNo <= $pdf_stamper_end_page){
				return true;
			}
		}
		else if($pageNo >= $pdf_stamper_start_page){
			return true;
		}
		return false;
	}
}

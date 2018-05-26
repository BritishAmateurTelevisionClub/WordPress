<?php
//***** Installer *****/
function wp_pdf_stamper_run_installer()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	//***Installer variables***/
	$pdf_stamper_db_version = "1.1";//change the value of "WP_PDF_STAMPER_DB_VERSION" if needed
	global $wpdb;
	$stamped_files_table_name = $wpdb->prefix . "pdf_stamped_files_tbl";
	
	//***Installer***/
	if($wpdb->get_var("SHOW TABLES LIKE '$stamped_files_table_name'") != $stamped_files_table_name)
	{
	   $sql = "CREATE TABLE " . $stamped_files_table_name . " (
	         file_id int(12) NOT NULL auto_increment,
	         creation_time datetime NOT NULL default '0000-00-00 00:00:00',
	         file_url varchar(255) NOT NULL,         
	         stamped_text text NULL,
	         cust_name varchar(64) NOT NULL default '',
	         cust_email varchar(64) NOT NULL,
	         txn_id varchar(128) NOT NULL,
	         PRIMARY KEY (file_id)
	      )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	   dbDelta($sql);
	
	   // Add default options
	   add_option("pdf_stamper_db_version", $pdf_stamper_db_version);
	}
	
	//****************
	//*** Upgrader ***
	//****************/
	
	$installed_ver = get_option( "pdf_stamper_db_version" );
	if( $installed_ver != $pdf_stamper_db_version )
	{
	   $sql = "CREATE TABLE " . $stamped_files_table_name . " (
	         file_id int(12) NOT NULL auto_increment,
	         creation_time datetime NOT NULL default '0000-00-00 00:00:00',
	         file_url varchar(255) NOT NULL,         
	         stamped_text text NULL,
	         cust_name varchar(64) NOT NULL default '',
	         cust_email varchar(64) NOT NULL,
	         txn_id varchar(128) NOT NULL,
	         PRIMARY KEY (file_id)
	      )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	   dbDelta($sql);
		
	    // Add default options
	    update_option("pdf_stamper_db_version", $pdf_stamper_db_version); 
	}
	
	/********************************************/
	/*** Setting default values at activation ***/
	/********************************************/
	update_option('WP_PDF_STAMP_URL',WP_PDF_STAMP_URL);//For use in eStore
	
	$default_stamped_files_dest_dir = WP_PDF_STAMP_URL.'/stamped-files';
	add_option('wp_pdf_stamped_files_dest_dir', $default_stamped_files_dest_dir);  
	add_option('wp_pdf_stamp_file_path_conv_method', '1');
	add_option('wp_pdf_stamp_stamping_method', '2');
	add_option('wp_pdf_start_stamping_from_page_number', 1);
	add_option('wp_pdf_stamping_end_page_number', 'Last');
	add_option('wp_pdf_stamp_font_family', "arial");
	add_option('wp_pdf_stamp_font_color_red', 0);
	add_option('wp_pdf_stamp_font_color_green', 0);
	add_option('wp_pdf_stamp_font_color_blue', 0);
	add_option('wp_pdf_stamp_font_style_bold', '');
	add_option('wp_pdf_stamp_font_style_italic', '1');
	add_option('wp_pdf_stamp_font_style_underline','');
	add_option('wp_pdf_stamp_font_size', 10);
	add_option('wp_pdf_stamp_line_alignment', "L");    
	add_option('wp_pdf_stamp_line_distance',15);
	add_option('wp_pdf_stamp_line_spacing', 5);
	add_option('wp_pdf_stamp_line_template', "Licensed to {customer_name} of {customer_address}. Email address: {customer_email}");	   
	
	add_option('wp_pdf_stamp_allow_print', '1');
	add_option('wp_pdf_stamp_allow_modify', '');
	add_option('wp_pdf_stamp_allow_copy', '1');
	add_option('wp_pdf_stamp_file_userpass', '');
	add_option('wp_pdf_stamp_use_email_as_password', '');
	$random_owner_pass = uniqid('',true);
	add_option('wp_pdf_stamp_file_ownerpass', $random_owner_pass);
	
	add_option('pdf_stamper_stamp_position', '1');
	add_option('wp_pdf_stamp_line_distance_header',15);
	//Add any other basic settings in the "wpPdfStampLoadBasicSettings" function
}

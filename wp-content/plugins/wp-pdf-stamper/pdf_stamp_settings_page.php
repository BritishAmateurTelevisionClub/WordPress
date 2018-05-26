<?php
include_once('admin_includes1.php');
include_once('manual_stamp_page.php');

function wpPdfStampOptionsMenu()
{
    echo '<div class="wrap">';
    echo '<h2>WP PDF Stamper Settings v'.WP_PDF_STAMP_VERSION.'</h2>';
	
    if(!wp_pdf_stamper_is_license_valid())
	{		
	    echo '</div></div>';
	    echo '</div>';			
		return;	//Do not display the page if licese key is invalid	
	}
	wpPdfStampLoadBasicSettings();
	
	pdf_stamper_admin_general_css();
	wpPdfStampAdminSubmenuCss();
	$current_tab = (isset($_GET['settings_action']))? $_GET['settings_action']:'';
	?>

        <h2 class="nav-tab-wrapper">
            <a class="nav-tab <?php echo ($current_tab == '') ? 'nav-tab-active' : ''; ?>" href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php">General Settings</a>
            <a class="nav-tab <?php echo ($current_tab == 'email') ? 'nav-tab-active' : ''; ?>" href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php&settings_action=email">Email/Notification Settings</a>
            <a class="nav-tab <?php echo ($current_tab == 'integration') ? 'nav-tab-active' : ''; ?>" href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php&settings_action=integration">Integration Settings</a>
        </h2>

	<?php
        echo '<div id="poststuff"><div id="post-body">';
	switch ($current_tab)
	{
		case 'general':
			showStampGeneralSettingsPage();
			break;
		case 'email':
			include_once('pdf_stamp_email_settings_page.php');
			showStampEmailSettingsPage();
			break;
		case 'integration':
			include_once('pdf_stamp_integration_settings.php');
			pdf_stamper_integration_settings_menu();
			break;			
		default:
			showStampGeneralSettingsPage();
			break;
	}
	    
    echo '</div></div>';
    echo '</div>';
}

function wpPdfStampLoadBasicSettings()
{
	$wp_ps_config = PDF_Stamper_Config::getInstance();
	
	$from_email_address = get_bloginfo('name')." <".get_option('admin_email').">";
	$buyer_email_subj = "Thank you for your purchase";
	$buyer_email_body = "Dear {first_name} {last_name}".
		"\n\nThank you for your purchase!".
		"\n\nHere is the download link for the purchased product".
		"\n{product_link}".
		"\n\nThanks";
	$wp_ps_config->addValue('wp_pdf_stamper_from_email_address', $from_email_address);
	$wp_ps_config->addValue('wp_pdf_stamper_buyer_email_subj', $buyer_email_subj);
	$wp_ps_config->addValue('wp_pdf_stamper_buyer_email_body', $buyer_email_body);
	$wp_ps_config->saveConfig();	
}

function showStampGeneralSettingsPage()
{
	$wp_ps_config = PDF_Stamper_Config::getInstance();
	if (isset($_POST['reset_defaults']))
	{
		wp_pdf_stamp_set_defaults();
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Options have been reset to default!';
        echo '</strong></p></div>';		
	}
	if (isset($_POST['wp_stamper_reset_log_file']))
	{
		if(wp_pdf_stamper_reset_log_files()){
        	echo '<div id="message" class="updated fade"><p><strong>Debug log files have been reset!</strong></p></div>';
		}
		else{
			echo '<div id="message" class="updated fade"><p><strong>Debug log files could not be reset!</strong></p></div>';
		}
	}
    if (isset($_POST['info_update']))
    {
    	update_option('WP_PDF_STAMP_URL',WP_PDF_STAMP_URL);//For use in eStore
    	
        update_option('wp_pdf_stamp_secret_key', trim($_POST["wp_pdf_stamp_secret_key"]));
        update_option('wp_pdf_stamped_files_dest_dir', trim($_POST["wp_pdf_stamped_files_dest_dir"]));
        update_option('wp_pdf_stamp_file_path_conv_method', (string)$_POST["wp_pdf_stamp_file_path_conv_method"]);
        update_option('wp_pdf_stamp_stamping_method', (string)$_POST["wp_pdf_stamp_stamping_method"]);          
        update_option('wp_pdf_start_stamping_from_page_number', trim($_POST["wp_pdf_start_stamping_from_page_number"]));
        update_option('wp_pdf_stamping_end_page_number', trim($_POST["wp_pdf_stamping_end_page_number"]));

        //update_option('wp_pdf_stamp_font_family', (string)$_POST["wp_pdf_stamp_font_family"]);
        update_option('wp_pdf_stamp_font_color_red', (string)$_POST["wp_pdf_stamp_font_color_red"]);
        update_option('wp_pdf_stamp_font_color_green', (string)$_POST["wp_pdf_stamp_font_color_green"]);
        update_option('wp_pdf_stamp_font_color_blue', (string)$_POST["wp_pdf_stamp_font_color_blue"]);
        update_option('wp_pdf_stamp_font_style_bold', isset($_POST['wp_pdf_stamp_font_style_bold']) ? '1':'' );
        update_option('wp_pdf_stamp_font_style_italic', isset($_POST['wp_pdf_stamp_font_style_italic']) ? '1':'' );
        update_option('wp_pdf_stamp_font_style_underline', isset($_POST['wp_pdf_stamp_font_style_underline']) ? '1':'' );        
        update_option('wp_pdf_stamp_font_size', (string)$_POST["wp_pdf_stamp_font_size"]);
        update_option('wp_pdf_stamp_line_alignment', (string)$_POST["wp_pdf_stamp_line_alignment"]); 
        update_option('wp_pdf_stamp_use_utf_font', isset($_POST['wp_pdf_stamp_use_utf_font']) ? '1':'' );    
      
        update_option('wp_pdf_stamp_enable_encryption', isset($_POST['wp_pdf_stamp_enable_encryption']) ? '1':'' );
        update_option('wp_pdf_stamp_allow_print', isset($_POST['wp_pdf_stamp_allow_print']) ? '1':'' );
        update_option('wp_pdf_stamp_allow_modify', isset($_POST['wp_pdf_stamp_allow_modify']) ? '1':'' );
        update_option('wp_pdf_stamp_allow_copy', isset($_POST['wp_pdf_stamp_allow_copy']) ? '1':'' );
        update_option('wp_pdf_stamp_file_userpass', (string)$_POST["wp_pdf_stamp_file_userpass"]);
        update_option('wp_pdf_stamp_use_email_as_password', isset($_POST['wp_pdf_stamp_use_email_as_password']) ? '1':'' );  
        update_option('wp_pdf_stamp_file_ownerpass', (string)$_POST["wp_pdf_stamp_file_ownerpass"]);      

        update_option('pdf_stamper_stamp_position', trim($_POST["pdf_stamper_stamp_position"]));
        update_option('wp_pdf_stamp_line_distance', trim($_POST["wp_pdf_stamp_line_distance"]));
        update_option('wp_pdf_stamp_line_distance_header', trim($_POST["wp_pdf_stamp_line_distance_header"]));
        update_option('wp_pdf_stamp_line_spacing', trim($_POST["wp_pdf_stamp_line_spacing"]));
        $tmp_line_template = htmlentities(stripslashes($_POST['wp_pdf_stamp_line_template']) , ENT_COMPAT, "UTF-8");
        update_option('wp_pdf_stamp_line_template', (string)$tmp_line_template);
        
        $wp_ps_config->setValue('enable_pdf_stamper_debug', isset($_POST['enable_pdf_stamper_debug']) ? '1':'' );
        $wp_ps_config->setValue('enable_pdf_stamper_sandbox', isset($_POST['enable_pdf_stamper_sandbox']) ? '1':'' );
        
        $wp_ps_config->saveConfig();
        
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Options Updated!';
        echo '</strong></p></div>';
    }
    $secret_key = get_option('wp_pdf_stamp_secret_key');
    if(empty($secret_key)){
        $secret_key = uniqid('',true);
    }
    //$font_size = get_option('wp_pdf_stamp_font_size');
    $dest_dir = get_option('wp_pdf_stamped_files_dest_dir');
    if(empty($dest_dir)){
    	wp_pdf_stamp_set_defaults();
    }    

    $pdf_stamper_stamp_position = get_option('pdf_stamper_stamp_position');
    if(empty($pdf_stamper_stamp_position)){$pdf_stamper_stamp_position = '1';}
    ?>
    <div class="pdf_stamper_yellow_box">
    <p>
        For information, updates and detailed documentation, please visit the <a href="https://www.tipsandtricks-hq.com/wp-pdf-stamper/documentation-index" target="_blank">WP PDF Stamper Documentation Page</a> or
        The plugin page <a href="https://www.tipsandtricks-hq.com/wp-pdf-stamper-plugin-2332" target="_blank">WP PDF Stamper</a>
    </p>
    </div>

    <div class="postbox">
    <h3 class="hndle"><label for="title">Quick Usage Guide</label></h3>
    <div class="inside">

    <p>1. First, configure the settings options to your liking and save it.</p>
    <p>2. Follow one of the integration methods explained on the <a href="https://www.tipsandtricks-hq.com/wp-pdf-stamper/documentation-index" target="_blank">documentation page</a> to integrate it with PayPal, Clickbank, WP eStore or WooCommerce plugin.</p>
    </div></div>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

    <div class="postbox">
    <h3 class="hndle"><label for="title">General PDF Stamper Settings</label></h3>
    <div class="inside">
    <table class="form-table">

    <tr valign="top">
    <th scope="row">Secret Key for PDF Stamping</th>
    <td><input type="text" name="wp_pdf_stamp_secret_key" value="<?php echo $secret_key; ?>" size="30" />
    <br /><p class="description">This secret key will be used to authenticate any PDF stamp request. You can change it with something random if you like.</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">Destination Directory of Stamped Files</th>
    <td><input type="text" name="wp_pdf_stamped_files_dest_dir" value="<?php echo get_option('wp_pdf_stamped_files_dest_dir'); ?>" size="100" />
    <br /><p class="description">It is best to not change the value of this field. This is where the stamped files will be stored. You do not need to change this value unless you specifically want to store these files in a different directory on your server.</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">File PATH Conversion Method</th>
    <td>
    <select name="wp_pdf_stamp_file_path_conv_method">
    <option value="1" <?php if(get_option('wp_pdf_stamp_file_path_conv_method')=="1")echo 'selected="selected"';?>><?php echo "Default" ?></option>
    <option value="2" <?php if(get_option('wp_pdf_stamp_file_path_conv_method')=="2")echo 'selected="selected"';?>><?php echo "Method 2" ?></option>
    <option value="3" <?php if(get_option('wp_pdf_stamp_file_path_conv_method')=="3")echo 'selected="selected"';?>><?php echo "Method 3" ?></option>
    <option value="4" <?php if(get_option('wp_pdf_stamp_file_path_conv_method')=="4")echo 'selected="selected"';?>><?php echo "Method 4" ?></option>
    </select>
    <br /><p class="description">On most servers the default path conversion method will work fine. You shouldn't need to change this value unless you are having trouble stamping a file and are instructed to change this value.</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row">PDF Stamping Method</th>
    <td>
    <select name="wp_pdf_stamp_stamping_method">
    <option value="2" <?php if(get_option('wp_pdf_stamp_stamping_method')=="2")echo 'selected="selected"';?>><?php echo "Default" ?></option>
    <option value="3" <?php if(get_option('wp_pdf_stamp_stamping_method')=="3")echo 'selected="selected"';?>><?php echo "Stamping Method 2" ?></option>    
    </select>
    <br /><p class="description">On most servers the default stamping method will work fine. You shouldn't need to change this value unless you are having trouble stamping a file and are instructed to change this value.</p></td>
    </tr>
        
    <tr valign="top">
    <th scope="row">Stamping Page Range</th>
    <td>
    Stamping Start Page Number <input type="text" name="wp_pdf_start_stamping_from_page_number" value="<?php echo get_option('wp_pdf_start_stamping_from_page_number'); ?>" size="4" />
    <br />Stamping End Page Number <input type="text" name="wp_pdf_stamping_end_page_number" value="<?php echo get_option('wp_pdf_stamping_end_page_number'); ?>" size="4" />
    <br /><p class="description">The PDF stamper will stamp every page of the PDF file by default. If you do not want to stamp the cover page for example then enter 2 in the <code>Stamping Start Page Number</code> text box and it will start stamping from the 2nd page.
    If you don't want to stamp after a certain page then enter the page number value in the <code>Stamping End Page Number</code> text field.</p></td>
    </tr>
    
    </table>
    </div></div>

    <div class="postbox">
    <h3 class="hndle"><label for="title">PDF Stamper Text Format Settings</label></h3>
    <div class="inside">
    <table class="form-table">

    <tr valign="top">
    <th scope="row">Font Color</th>
    <td>
    Red <input type="text" name="wp_pdf_stamp_font_color_red" value="<?php echo get_option('wp_pdf_stamp_font_color_red'); ?>" size="3" />
    Green <input type="text" name="wp_pdf_stamp_font_color_green" value="<?php echo get_option('wp_pdf_stamp_font_color_green'); ?>" size="3" />
    Blue <input type="text" name="wp_pdf_stamp_font_color_blue" value="<?php echo get_option('wp_pdf_stamp_font_color_blue'); ?>" size="3" />
    <br /><p class="description">The font color in RGB (Red, Green, Blue) value. For example, entering 0 in all three fields will make the font color "Black". If you don't know the RGB value of a color you can get it from <a href="http://www.colorschemer.com/online.html" target="_blank">here</a>.</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">Font Size</th>
    <td><input type="text" name="wp_pdf_stamp_font_size" value="<?php echo get_option('wp_pdf_stamp_font_size'); ?>" size="3" />
    <br /><p class="description">The font size (Example: 10).</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">Text Alignment</th>
    <td>
    <select name="wp_pdf_stamp_line_alignment">
    <option value="L" <?php if(get_option('wp_pdf_stamp_line_alignment')=="L")echo 'selected="selected"';?>><?php echo "Left" ?></option>
    <option value="C" <?php if(get_option('wp_pdf_stamp_line_alignment')=="C")echo 'selected="selected"';?>><?php echo "Center" ?></option>
    <option value="R" <?php if(get_option('wp_pdf_stamp_line_alignment')=="R")echo 'selected="selected"';?>><?php echo "Right" ?></option>
    </select>
    <br /><p class="description">The text alignment (Example: center)</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">Use UTF-8 Font</th>
    <td>
    <input name="wp_pdf_stamp_use_utf_font" type="checkbox"<?php if(get_option('wp_pdf_stamp_use_utf_font')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">Use this option if your language needs unicode format support. In most cases you shouldn't need this.</p></td>
    </tr>
    
    </table>
    </div></div>

    <div class="postbox">
    <h3 class="hndle"><label for="title">PDF File Security Settings</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top"><td colspan="2">
	You can optionally encrypt the stamped PDF file using the following options.	
	</td></tr>
	<tr valign="top"><td colspan="2">
	<p><strong>Note:</strong> <i>Encrypting a PDF file is a resource intensive process, so if you are having issues stamping a file and you are on a shared hosting with low RAM and CPU resource then do not use this feature.</i></p>	
	</td></tr>

	<tr valign="top">
    <th scope="row">Enable PDF File Encryption</th>
    <td>
    <input name="wp_pdf_stamp_enable_encryption" type="checkbox"<?php if(get_option('wp_pdf_stamp_enable_encryption')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">If checked the stamped PDF file will be encrypted using the following security encryption options.</p></td>
    </tr>
    
	<tr valign="top">
    <th scope="row">Allow Print</th>
    <td>
    <input name="wp_pdf_stamp_allow_print" type="checkbox"<?php if(get_option('wp_pdf_stamp_allow_print')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">The user is allowed to print the stamped pdf file. If you do not want your customers to be able to print it then uncheck this field.</p></td>
    </tr>

	<tr valign="top">
    <th scope="row">Allow Copy</th>
    <td>
    <input name="wp_pdf_stamp_allow_copy" type="checkbox"<?php if(get_option('wp_pdf_stamp_allow_copy')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">The user is allowed to copy or otherwise extract text and graphics from the stamped pdf file. If you do not want your customers to be able to copy any text or images from the PDF file then uncheck this field.</p></td>
    </tr>
    
	<tr valign="top">
    <th scope="row">Allow Modify</th>
    <td>
    <input name="wp_pdf_stamp_allow_modify" type="checkbox"<?php if(get_option('wp_pdf_stamp_allow_modify')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">The user with full acrobat product range is allowed to modify the contents of the stamped file. If you do not want the file to be modifiable then uncheck this field.</p></td>
    </tr>
        
    <tr valign="top">
    <th scope="row">File Password</th>
    <td><input type="text" name="wp_pdf_stamp_file_userpass" value="<?php echo get_option('wp_pdf_stamp_file_userpass'); ?>" size="16" />
    <br /><p class="description">User password to open the file. Leave empty if you do not want to protect the stamped file with a set password.</p>
    </td>
    </tr>

	<tr valign="top"><td>Or</td></tr>

	<tr valign="top">
    <th scope="row">Use Email as Password</th>
    <td>
    <input name="wp_pdf_stamp_use_email_as_password" type="checkbox"<?php if(get_option('wp_pdf_stamp_use_email_as_password')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">The stamped PDF file will be protected with the customer's email address as the password given that an email address is provided.</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row">Owner Password</th>
    <td><input type="text" name="wp_pdf_stamp_file_ownerpass" value="<?php echo get_option('wp_pdf_stamp_file_ownerpass'); ?>" size="40" />
    <br /><p class="description">If you want to modify the stamped file using adobe product later you will need this password. You can change it with something random if you like.</p>
    </td>
    </tr>
        
    </table>
    </div></div>
    
    <div class="postbox">
    <h3 class="hndle"><label for="title">Stamp Text Layout Settings</label></h3>
    <div class="inside">
    <table class="form-table">

    <tr valign="top">
    <th scope="row">Stamping Position</th>
    <td>
    <input type="radio" value="1" name="pdf_stamper_stamp_position" <?php echo ($pdf_stamper_stamp_position=='1')?'checked="checked"':''; ?>>
    Stamp in the Footer<br />
    Line Distance from the Bottom of the Page <input type="text" name="wp_pdf_stamp_line_distance" value="<?php echo get_option('wp_pdf_stamp_line_distance'); ?>" size="3" /> Millimeter (mm)
    <p class="description">This value is used to determine the position of the text to be stamped in the PDF file. A value of 15 is a good default value for placing the text in the footer area.</p>    
    <br />
    <input type="radio" value="2" name="pdf_stamper_stamp_position" <?php echo ($pdf_stamper_stamp_position=='2')?'checked="checked"':''; ?>>
    Stamp in the Header<br />
    Line Distance from the Top of the Page <input type="text" name="wp_pdf_stamp_line_distance_header" value="<?php echo get_option('wp_pdf_stamp_line_distance_header'); ?>" size="3" /> Millimeter (mm)
    <p class="description">This value is used to determine the position of the text to be stamped in the PDF file. A value of 15 is a good default value for placing the text in the header area.</p>    
    </td>
    </tr>

    <tr valign="top">
    <th scope="row">Line Spacing</th>
    <td><input type="text" name="wp_pdf_stamp_line_spacing" value="<?php echo get_option('wp_pdf_stamp_line_spacing'); ?>" size="3" /> Millimeter (mm)
    <br /><p class="description">This value is used to determine the spacing between the lines. A value of 5 is a good default value.</p></td>
    </tr>

    <tr valign="top">
    <th scope="row">Text to Stamp</th>
    <td><textarea name="wp_pdf_stamp_line_template" rows="3" cols="80"><?php echo get_option('wp_pdf_stamp_line_template'); ?></textarea>
    <br /><p class="description">This text will be stamped in the PDF file. The special variables within braces {} will be replaced dynamically. <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/?p=68#footer_variables" target="_blank">This link</a> has more explanation on what dynamic variables you can use in the text area.</p></td>
    </tr>

    </table>
    </div></div>
    
<!--
Default Line one text template
-->

    <div class="postbox">
    <h3 class="hndle"><label for="title">Testing and Debugging Settings</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">Enable Debug</th>
    <td>
    <input name="enable_pdf_stamper_debug" type="checkbox"<?php if($wp_ps_config->getValue('enable_pdf_stamper_debug')=='1'){echo 'checked="checked"';} ?> value="1"/>
    <p class="description">If checked, debug output will be written to log files. </p>
		You can check the debug log file by clicking on the link below (The log file can be viewed using any text editor):
    	<li style="margin-left:15px;"><a href="<?php echo WP_PDF_STAMP_URL."/pdf_stamper_debug.log"; ?>" target="_blank">pdf stamper debug log file</a></li>
    	<li style="margin-left:15px;"><a href="<?php echo WP_PDF_STAMP_URL."/api/ipn_handle_debug.log"; ?>" target="_blank">ipn handling debug log file</a></li>
    	<div class="submit">
    	<input type="submit" name="wp_stamper_reset_log_file" style="font-weight:bold; color:red" value="Reset Debug Log File" class="button" /> 
    	<p class="description">The above debug log file will be "reset" and timestamped with a log file reset message.</p>
    	</div>
    </td>
    </tr>
    
	<tr valign="top">
    <th scope="row">Enable Sandbox Testing</th>
    <td>
    <input name="enable_pdf_stamper_sandbox" type="checkbox"<?php if($wp_ps_config->getValue('enable_pdf_stamper_sandbox')=='1'){echo 'checked="checked"';} ?> value="1"/>
    <p class="description">If checked the plugin will run in Sandbox/Testing mode (Example: PayPal Sandbox)</p></td>
    </tr>

    </table>
    </div></div>
    
    <div class="submit">
        <input type="submit" name="info_update" class="button-primary" value="Update Options" />
        <input type="submit" name="reset_defaults" class="button" value="Reset to Default" />
    </div>
    </form>
    <?php
}

function wp_pdf_stamp_set_defaults()
{
        //update_option('wp_pdf_stamp_secret_key', (string)$_POST["wp_pdf_stamp_secret_key"]);
        $default_stamped_files_dest_dir = WP_PDF_STAMP_URL.'/stamped-files';
        update_option('wp_pdf_stamped_files_dest_dir', $default_stamped_files_dest_dir);
        update_option('wp_pdf_stamp_file_path_conv_method', '1');   
        update_option('wp_pdf_stamp_stamping_method', '2');     
        update_option('wp_pdf_start_stamping_from_page_number', 1);
        update_option('wp_pdf_stamping_end_page_number', 'Last');
        
        update_option('wp_pdf_stamp_font_family', "arial");
        update_option('wp_pdf_stamp_font_color_red', 0);
        update_option('wp_pdf_stamp_font_color_green', 0);
        update_option('wp_pdf_stamp_font_color_blue', 0);
        update_option('wp_pdf_stamp_font_style_bold', '');
        update_option('wp_pdf_stamp_font_style_italic', '1');
        update_option('wp_pdf_stamp_font_style_underline','');
        update_option('wp_pdf_stamp_font_size', 10);
        update_option('wp_pdf_stamp_line_alignment', "L");
        update_option('wp_pdf_stamp_use_utf_font', '');
                
        update_option('wp_pdf_stamp_enable_encryption', '');
		update_option('wp_pdf_stamp_allow_print', '1');
		update_option('wp_pdf_stamp_allow_modify', '');
		update_option('wp_pdf_stamp_allow_copy', '1');
		update_option('wp_pdf_stamp_file_userpass', '');
		update_option('wp_pdf_stamp_use_email_as_password', '');
        
		update_option('pdf_stamper_stamp_position','1');
		update_option('wp_pdf_stamp_line_distance_header',15);
        update_option('wp_pdf_stamp_line_distance',15);
        update_option('wp_pdf_stamp_line_spacing', 5);
        update_option('wp_pdf_stamp_line_template', "Licensed to {customer_name} of {customer_address}. Email address: {customer_email}");

	$wp_ps_config = PDF_Stamper_Config::getInstance();
	
	$wp_ps_config->setValue('enable_pdf_stamper_debug','');
	$wp_ps_config->setValue('enable_pdf_stamper_sandbox','');
	
	$from_email_address = get_bloginfo('name')." <".get_option('admin_email').">";
	$buyer_email_subj = "Thank you for your purchase";
	$buyer_email_body = "Dear {first_name} {last_name}".
		"\n\nThank you for your purchase!".
		"\n\nHere is the download link for the purchased product".
		"\n{product_link}".
		"\n\nThanks";
	$wp_ps_config->setValue('wp_pdf_stamper_from_email_address', $from_email_address);
	$wp_ps_config->setValue('wp_pdf_stamper_buyer_email_subj', $buyer_email_subj);
	$wp_ps_config->setValue('wp_pdf_stamper_buyer_email_body', $buyer_email_body);
	$wp_ps_config->saveConfig();        
}

function wp_pdf_stamper_reset_log_files()
{
	$log_reset = true;
	$stamper_logfile_list = array (
		WP_PDF_STAMP_PATH."/pdf_stamper_debug.log",
		WP_PDF_STAMP_PATH.'/api/ipn_handle_debug.log',
	);

	foreach($stamper_logfile_list as $logfile)
	{
		if(empty($logfile)){continue;}
		
		$text = '['.date('m/d/Y g:i A').'] - SUCCESS : Log file reset';
	    $text .= "\n------------------------------------------------------------------\n\n";
		$fp = fopen($logfile, 'w');
		if($fp != FALSE) {
			@fwrite($fp, $text);
			@fclose($fp);
		}
		else{
			$log_reset = false;	
		}
	}
	return $log_reset;
}
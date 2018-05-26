<?php

function pdf_stamper_integration_settings_menu()
{
	$wp_ps_config = PDF_Stamper_Config::getInstance();
	if (isset($_POST['pdfstamper_save_email_settings']))
	{
        update_option('wp_pdf_stamp_allow_clickbank_integration', ($_POST['wp_pdf_stamp_allow_clickbank_integration']=='1') ? '1':'' );
        update_option('wp_pdf_stamp_clickbank_secret_key', trim($_POST["wp_pdf_stamp_clickbank_secret_key"]));
        		
		$wp_ps_config->setValue('wp_pdf_stamp_allow_woocommerce_integration', ($_POST['wp_pdf_stamp_allow_woocommerce_integration']=='1') ? '1':'' );
		$wp_ps_config->setValue('wp_pdf_stamp_enable_s2file_integration', ($_POST['wp_pdf_stamp_enable_s2file_integration']=='1') ? '1':'' );
		
		$wp_ps_config->saveConfig();
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Settings updated!';
        echo '</strong></p></div>';
	}
	
	$paypal_ipn_url = WP_PDF_STAMP_SITE_HOME_URL.'/?pdfs_pp_ipn=process';//WP_PDF_STAMP_URL."/api/ipn_handler.php";
	if(isset($_POST['pdf_stamper_pp_generate_advanced_code']))
	{
		$pdf_file_url = $_POST['pdf_stamper_pp_file_url'];
    	$pp_av_code = 'notify_url='.$paypal_ipn_url.'<br />'.'custom='.$pdf_file_url;
        echo '<div id="message" class="updated fade"><p>';
        echo '<strong>Paste the code below in the "Add advanced variables" field of your hosted PayPal button.</strong>';
		echo '<br /><code>'.$pp_av_code.'</code>';
        echo '</p></div>';
	}
	
	?>
	
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

    <div class="postbox">
    <h3 class="hndle"><label for="title">WP eStore Plugin Integration Settings</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">WP eStore Integration</th>
    <td>
	<p class="description">Read the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/how-to-integrate-the-pdf-stamper-with-the-wp-estore-plugin-79" target="_blank">WP eStore integration documentation</a>.</p></td>
    </tr>

    </table>
    </div></div>
    
    <div class="postbox">
    <h3 class="hndle"><label for="title">ClickBank Integration Settings (Read the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/?p=144" target="_blank">Integration Instruction</a>)</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">Enable ClickBank Integration</th>
    <td>
    <input name="wp_pdf_stamp_allow_clickbank_integration" type="checkbox"<?php if(get_option('wp_pdf_stamp_allow_clickbank_integration')!='') echo ' checked="checked"'; ?> value="1"/>
    <br /><p class="description">Use this option if you want to use PDF Stamper with a ClickBank product.</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row">ClickBank Link Security Secret Key</th>
    <td><input type="text" name="wp_pdf_stamp_clickbank_secret_key" value="<?php echo get_option('wp_pdf_stamp_clickbank_secret_key'); ?>" size="40" />
    <br /><p class="description">Specify your ClickBank secret key in the above field. This secret key is used to verify the payment notification after a purchase. You can enter a secret key on the My Site page of your ClickBank account.</p></td>
    </tr>

    </table>
    </div></div>

    <div class="postbox">
    <h3 class="hndle"><label for="title">PayPal Integration Settings</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">Direct PayPal Button Integration</th>
    <td>
	<p class="description">Read the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/plain-paypal-button-and-pdf-stamper-integration-guide-43" target="_blank">PayPal integration documentation</a> to learn how to integrate the stamper with a PayPal button.</p></td>
    </tr>

    <tr valign="top"><td width="25%" align="left">
    PayPal IPN (Instant Payment Notification) URL Value:
    </td><td align="left">
    <code><?php echo $paypal_ipn_url; ?></code>
    </td></tr>    
	</table>

	<div style="margin: 10px;">
	<strong>Generate the "Advanced Variables" code for your hosted PayPal button</strong>
	<br />
	Enter the PDF File URL: 
	<input name="pdf_stamper_pp_file_url" type="text" size="100" value="" />
	<br />
	<input type="submit" name="pdf_stamper_pp_generate_advanced_code" value="Generate Code" class= "button-primary" />
    </div>
    
    </div></div>
    
    <div class="postbox">
    <h3 class="hndle"><label for="title">WooCommerce Integration Settings (Read the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/woocommerce-and-pdf-stamper-integration-197" target="_blank">Integration Instruction</a>)</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">Enable WooCommerce Integration</th>
    <td>
    <input name="wp_pdf_stamp_allow_woocommerce_integration" type="checkbox"<?php if($wp_ps_config->getValue('wp_pdf_stamp_allow_woocommerce_integration')=='1'){echo 'checked="checked"';} ?> value="1"/>
    <br /><p class="description">Use this option if you want to use PDF Stamper with WooCommerce pluign. The PDF file will be stamped when customers download a PDF file sold via the WooCommerce plugin.</p></td>
    </tr>

    </table>
    </div></div>

    <div class="postbox">
    <h3 class="hndle"><label for="title">S2Member Integration Settings (Read the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/" target="_blank">Integration Instruction</a>)</label></h3>
    <div class="inside">
    <table class="form-table">

	<tr valign="top">
    <th scope="row">Enable S2File Shortcode Integration</th>
    <td>
    <input name="wp_pdf_stamp_enable_s2file_integration" type="checkbox"<?php if($wp_ps_config->getValue('wp_pdf_stamp_enable_s2file_integration')=='1'){echo 'checked="checked"';} ?> value="1"/>
    <br /><p class="description">Use this option if you want to use PDF Stamper with S2Member pluign's s2File shortcode. When enabled, PDF file downloads that you have created using the s2File shortcode will be stamped before it is given to your members.</p></td>
    </tr>

    </table>
    </div></div>
    
    <div class="submit">
        <input type="submit" class="button-primary" name="pdfstamper_save_email_settings" value=" Save Settings " />
    </div>
    </form>
	<?php 
}
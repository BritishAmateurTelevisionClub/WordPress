<?php

function showStampEmailSettingsPage()
{
    $wp_ps_config = PDF_Stamper_Config::getInstance();
    if (isset($_POST['pdfstamper_save_email_settings']))
    {
        $wp_ps_config->setValue('wp_pdf_stamper_from_email_address', stripslashes($_POST["wp_pdf_stamper_from_email_address"]));
        $wp_ps_config->setValue('wp_pdf_stamper_buyer_email_subj', stripslashes($_POST["wp_pdf_stamper_buyer_email_subj"]));
        $wp_ps_config->setValue('wp_pdf_stamper_buyer_email_body', stripslashes($_POST["wp_pdf_stamper_buyer_email_body"]));

        $wp_ps_config->setValue('wp_pdf_stamper_seller_email_address', stripslashes($_POST["wp_pdf_stamper_seller_email_address"]));
        $wp_ps_config->setValue('wp_pdf_stamper_seller_email_subj', stripslashes($_POST["wp_pdf_stamper_seller_email_subj"]));
        $wp_ps_config->setValue('wp_pdf_stamper_seller_email_body', stripslashes($_POST["wp_pdf_stamper_seller_email_body"]));

        $wp_ps_config->saveConfig();
        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Settings updated!';
        echo '</strong></p></div>';
    }
    
    ?>
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

	<div class="pdf_stamper_yellow_box">
	<p>The following settings values are used when you directly integrate the PDF Stamper plugin with a PayPal or a ClickBank button.</p> 
	<p>If you are using the PDF Stamper pluign with the <a href="https://www.tipsandtricks-hq.com/wordpress-estore-plugin-complete-solution-to-sell-digital-products-from-your-wordpress-blog-securely-1059" target="_blank">WP eStore plugin</a> then the email settings from the eStore plugin will be used for product sale notification.</p>
	</div>
	
	<div class="postbox">
	<h3 class="hndle"><label for="title">Email Settings</label></h3>
	<div class="inside">

    <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="6">

    <tr valign="top"><td width="25%" align="left">
    From Email Address
    </td><td align="left">
    <input name="wp_pdf_stamper_from_email_address" type="text" size="100" value="<?php echo $wp_ps_config->getValue('wp_pdf_stamper_from_email_address'); ?>"/>
    <p class="description">Example: Your Name &lt;sales@your-domain.com&gt; This is the email address that will be used to send the email. This name and email address will appear in the from field of the email.</p>
    </td></tr>

    <tr valign="top"><td width="25%" align="left">
	Buyer Email Subject
    </td><td align="left">
    <input name="wp_pdf_stamper_buyer_email_subj" type="text" size="100" value="<?php echo $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_subj'); ?>"/>
    <p class="description">This is the subject of the email that will be sent to the buyer after a purchase.</p>
    </td></tr>
    
    <tr valign="top"><td width="25%" align="left">
	Buyer Email Body
    </td><td align="left">
    <textarea name="wp_pdf_stamper_buyer_email_body" rows="8" cols="80"><?php echo $wp_ps_config->getValue('wp_pdf_stamper_buyer_email_body'); ?></textarea>
    <p class="description">This is the body of the email that will be sent to the buyer. Do not change the text within the braces {}. The first name, last name and stamped file's URL will be replaced with the text with braces {} </p>
    </td></tr>
        
    <tr valign="top"><td width="25%" align="left">
	Seller Email Address
    </td><td align="left">
    <input name="wp_pdf_stamper_seller_email_address" type="text" size="100" value="<?php echo $wp_ps_config->getValue('wp_pdf_stamper_seller_email_address'); ?>"/>
    <p class="description">This is the email address where the seller will be notified of product sales.</p>
    </td></tr> 
    
    <tr valign="top"><td width="25%" align="left">
	Seller Email Subject
    </td><td align="left">
    <input name="wp_pdf_stamper_seller_email_subj" type="text" size="100" value="<?php echo $wp_ps_config->getValue('wp_pdf_stamper_seller_email_subj'); ?>"/>
    <p class="description">This is the subject of the email that will be sent to the seller after a purchase.</p>
    </td></tr>
    
    <tr valign="top"><td width="25%" align="left">
	Seller Email Body
    </td><td align="left">
    <textarea name="wp_pdf_stamper_seller_email_body" rows="8" cols="80"><?php echo $wp_ps_config->getValue('wp_pdf_stamper_seller_email_body'); ?></textarea>
    <p class="description">This is the body of the email that will be sent to the seller.</p>
    </td></tr>
    
    </table>
    </div></div>

    <div class="submit">
        <input type="submit" class="button-primary" name="pdfstamper_save_email_settings" value=" Save Settings " />
    </div>
    </form>
	<?php 
}
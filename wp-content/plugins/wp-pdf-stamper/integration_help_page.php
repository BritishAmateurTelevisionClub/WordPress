<?php
function integration_help_menu()
{
    echo '<div class="wrap">';
    echo '<h2>WP PDF Stamper Integration Help v'.WP_PDF_STAMP_VERSION.'</h2>';
    echo '<div id="poststuff"><div id="post-body">';
      
	pdf_stamper_admin_general_css();
    
	
	if(!wp_pdf_stamper_is_license_valid())
	{		
	    echo '</div></div>';
	    echo '</div>';			
		return;	//Do not display the page if licese key is invalid	
	}    
    
	$postURL = WP_PDF_STAMP_URL.'/api/stamp_api.php';	
	echo "<strong>The POST URL For Your Installation</strong>";
	echo '<div class="pdf_stamper_code">'.$postURL.'</div>';

	?>
<h2>3rd Party Integration</h2>

Integrating a 3rd party payment system or shopping cart with WP PDF Stamper is possible. Please do not follow this instruction if you are using the WP eStore plugin as your shopping cart because the WP eStore plugin is integrated by default.
<br /><br />
The integration process can be accomplished in three steps, namely:
<br />
<br />1. Generate POST data
<br />2. Send POST data to the POST URL
<br />3. Process the returned data
<br /><br />
<strong>POST Values</strong>
<br />
WP PDF Stamper expects a certain set of variables to be sent to it via HTTP POST. These variables are:
<br /><br />
Mandatory Variables
<br />
----------------
<br />a. Secret Key: A Secret API key (you can find this value in the settings menu of this plugin)
<br />b. Source File URL: The URL of the source file that needs to be stamped (a copy of the source file will be stamped to keep the source file intact)
<br /><br />
Optional Variables
<br />
---------------
<br />c. Customer Name: The name of the customer
<br />d. Customer Email: The email address of the customer
<br />e. Customer Address: The customer's address
<br /><br />
<strong>Return Value</strong>
<br />
Upon successful processing, WP PDF Stamper will return a plain text message that will have two lines similar to the following:
<br />
<div class="pdf_stamper_code">
Success! 
http://www.examle.com/wp-content/plugins/wp-pdf-stamper/stamped-files/test-ebook_4c05fffce4de3.pdf
</div>
or
<div class="pdf_stamper_code">
Error!
Secret key is invalid
</div>

1. The first line is an indication of success or error
<br />2. The second line is the result. In the event of success, it will contain the URL of the stamped file (this is a copy of the source file but stamped with the appropriate information)
<br /><br />
<strong>Sample PHP Code</strong>
<br />
Below is a sample PHP code that shows how easy it is to integrate with WP PDF Stamper
<br />

<div class="pdf_stamper_code">
/*** Mandatory data ***/
<br />// Post URL
<br />$postURL = "<?php echo $postURL; ?>";
<br />// The Secret key
<br />$secretKey = "<?php echo get_option('wp_pdf_stamp_secret_key'); ?>";
<br />//The source file URL (The file that you want to stamp)
<br />$fileURL = "http://www.example.com/wp-content/uploads/test-ebook.pdf";
<br /> 
<br />/*** Optional Data ***/
<br />//Customers Name
<br />$customerName = "John Doe";
<br />//Customer Email address
<br />$customerEmail = "john.doe@gmail.com";
<br />//Customer's Address
<br />$customerAddress = "123 Some Street, San Jose, CA - 95130, U.S.A.";
<br />
<br />// prepare the data
<br />$data = array ();
<br />$data['secret_key'] = $secretKey;
<br />$data['source_file'] = $fileURL;
<br />$data['customer_name'] = $customerName;
<br />$data['customer_email'] = $customerEmail;
<br />$data['customer_address'] = $customerAddress;
<br />
<br />// send data to post URL
<br />$ch = curl_init ($postURL);
<br />curl_setopt ($ch, CURLOPT_POST, true);
<br />curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
<br />curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
<br />$returnValue = curl_exec ($ch);
<br />
<br />// Process the return values
<br />list ($status, $value) = explode ("\n", $returnValue);
<br />if(strpos($status,"Success!") !== false)
<br />{
<br />    $file_url = trim($value);
<br />    echo "The URL of the stamped file is: ".$file_url;
<br />}
<br />else
<br />{
<br />    echo "An error occured while trying to stamp the file! Error details: ".$value;
<br />}
</div>
	
	<?php     
    echo '</div></div>';
    echo '</div>';	
}
?>
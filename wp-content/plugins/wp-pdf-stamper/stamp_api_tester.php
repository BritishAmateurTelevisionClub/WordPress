<?php

	//Deactivate Using HTTP GET
	//Just type in the following
	//http://www.example.com/wp-content/plugins/wp-pdf-stamper/api/dc.php?secret_key=4c132da1f24a41.63429762
	
include_once('../../../wp-load.php');

wp_pdf_stamp_create_stamp_new3();//Test the new stamping function
//wp_pdf_stamp_create_stamp_new2();
//wp_pdf_stamp_create_stamp();

function wp_pdf_stamp_create_stamp_new3()
{
    $data = array ();
    $data['source_file'] = 'http://www.scam-wiki.com/wp-content/uploads/2010/06/pdf-stamper-sample-ebook.pdf';
    $data['customer_name'] = "John Doe";
    $data['customer_email'] = "john.doe@yahoo.com";
    $data['customer_phone'] = "0123456789";
    $data['customer_address'] = "Unit 50, 260 Princes Hwy, CA";
    $data['customer_business_name'] = "FrostMourne";
    $result = pdf_stamper_execute_stamping($data);
    if(!empty($result)){
        echo "Stamped File URL: ".$result;
    }else{
        echo "There was an error.";
    }
    exit;
}

function wp_pdf_stamp_create_stamp_new2()
{ 	
$source_file = "http://www.scam-wiki.com/wp-content/uploads/2012/02/sample-pdf-file-v1.5.pdf";	
//$source_file = "http://www.wordpresscheatsheets.com/wp-content/uploads/sample-pdf-file-v1.5.pdf";
$customer_name = "Korin Ivy";
$customer_email = "testemail@gmail.com";
$customer_phone = "12345678";
$customer_address = "Unit 50, 250-260 Princes Hwy, NSW - 2580, Australia";
$customer_business = "Shadow Labs";
$distance_from_bottom = "";
$line_space = "";
$footer_text = "";

$additional_params = array();
$additional_params['transaction_id'] = "SOMEUNIQUEID98vi9Iv";

//$returnValue = pdf_stamper_stamp_internal_file($source_file,$customer_name,$customer_email,$customer_phone,$customer_address,$customer_business,$distance_from_bottom,$line_space,$footer_text);
$returnValue = pdf_stamper_stamp_internal_file($source_file,$customer_name,$customer_email,$customer_phone,$customer_address,$customer_business,$distance_from_bottom,$line_space,$footer_text,$additional_params);
print_r($returnValue);
    	
}

function wp_pdf_stamp_create_stamp_new()
{
	//$url = "http://www.scam-wiki.com/wp-content/plugins/wp-pdf-stamper/api/stamp_api.php";	
	$url = "http://www.triphasebusiness.co.uk/wp1/wp-content/plugins/wp-pdf-stamper/api/stamp_api.php";
    //$secretKey = "4bc67180656782.45045137";
    $secretKey = "4e2b1636023396.54803049";
    //The file path
    //$fileURL = "http://www.scam-wiki.com/wp-content/plugins/wp-pdf-stamper/source-files/wp-estore-shortcodes.pdf";
    $fileURL = "http://www.triphasebusiness.co.uk/wp1/wp-content/uploads/2011/07/How-to-be-the-wife-behind-the-successful-entrepreneur.pdf";
    	
    $data = array ();
    $data['secret_key'] = $secretKey;
    //$data['requested_domain'] = $domainURL;
    $data['source_file'] = $fileURL;
    $data['customer_name'] = "Korin Iverson";
    $data['customer_email'] = "korin.ivy@gmail.com";
    $data['customer_phone'] = "01456987412";
    $data['customer_address'] = "Unit 50, 250-260 Princes Hwy, NSW - 2580, Australia";
    $data['customer_business_name'] = "Shadow Labs";
    
    echo "<br />Data to send<br />";
    print_r($data);
    
    //$data = array( 'secret_key' => $secretKey, 'source_file' => $fileURL, 'customer_name'=>"Korin Iverson", 'customer_email'=>"korin.iverson@gmail.com",'customer_phone'=>"12345678",'customer_address'=> "Unit 50, 250-260 Princes Hwy, NSW - 2580, Australia")    
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'timeout' => 120,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $data,
		'cookies' => array()
	    )
	);
		
	if( is_wp_error( $response ) ) {
	   echo 'Something went wrong!';
	} 
	else {
	   echo 'Response:<pre>';
	   //print_r( $response );
	   print_r($response['body']);
	   echo '</pre>';
	}	
}

function wp_pdf_stamp_create_stamp()
{
    // Post URL
    $postURL = "http://www.scam-wiki.com/wp-content/plugins/wp-pdf-stamper/api/stamp_api.php";
    // The Secret key
    $secretKey = "4bc67180656782.45045137";
    //The file path
    $fileURL = "http://www.scam-wiki.com/wp-content/plugins/wp-pdf-stamper/source-files/wp-estore-shortcodes.pdf";
    //$fileURL = "http://www.scam-wiki.com/wp-content/uploads/pdf-stamper-filled-form-test-file.pdf";
    //$fileURL = "http://www.scam-wiki.com/wp-content/uploads/2010/08/landscape.pdf";
    //$fileURL = "http://www.scam-wiki.com/wp-content/plugins/wp-pdf-stamper/LU_ebook_3pages.pdf";
    //$fileURL = "http://www.scam-wiki.com/wp-content/uploads/2011/04/narrow-pdf-doc.pdf";
    //$fileURL = "http://www.scam-wiki.com/wp-content/uploads/2010/06/Hummel-resouece-eBook.pdf";    
    
    $data = array ();
    $data['secret_key'] = $secretKey;
    //$data['requested_domain'] = $domainURL;
    $data['source_file'] = $fileURL;
    $data['customer_name'] = "Korin Iverson";
    $data['customer_email'] = "korin.iverson@gmail.com";
    $data['customer_phone'] = "0413899035";
    $data['customer_address'] = "Unit 50, 250-260 Princes Hwy, NSW - 2580, Australia";
    $data['customer_business_name'] = "Shadow Labs";
  
	// send data to post URL
	$ch = curl_init ($postURL);
	curl_setopt ($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$returnValue = curl_exec ($ch);
	
	print_r($returnValue);
	// Process the return values
//	list ($status, $value) = explode ("\n", $returnValue);
//	if(strpos($status,"Success!") !== false)
//	{
//	    $file_url = trim($value);
//	    echo "The URL of the stamped file is: ".$file_url;
//	}
//	else
//	{
//	    echo "An error occured while trying to stamp the file! Error details: ".$value;
//	}
}

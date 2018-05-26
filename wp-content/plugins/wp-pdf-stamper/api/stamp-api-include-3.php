<?php

// Define output file name and directory
$unique_file_suffix = uniqid();
if (empty($dest_dir)) {
    $output_file = str_replace('.pdf', '', $src_file_to_process) . '_' . $unique_file_suffix . '.pdf';
} else {
    $stamper_path_parts = pathinfo($src_file_to_process);
    $stamper_file_name = $stamper_path_parts['basename'];
    $output_file_name = str_replace('.pdf', '', $stamper_file_name) . '_' . $unique_file_suffix . '.pdf';
    $output_file = $dest_dir . '/' . $output_file_name;
}

pdf_stamper_debug("Autoloading library for stamping process", true);
require_once(WP_PDF_STAMP_PATH . 'lib/SetaPDF/Autoload.php');

try {
    // create a writer
    $writer = new SetaPDF_Core_Writer_File($output_file);

    // get a document instance
    $document = SetaPDF_Core_Document::loadByFilename($src_file_to_process, $writer);

    // create a stamper instance
    $stamper = new SetaPDF_Stamper($document);

    // create a font object
    $font = SetaPDF_Core_Font_Standard_Helvetica::create($document);

    // create simple text stamp with font and style
    $stamp = new SetaPDF_Stamper_Stamp_Text($font, $font_size);

    /* Massage and set the text to be stamped */

    //Get the page width from page property
    $pages = $document->getCatalog()->getPages();
    $page_count = $pages->count();
    $page = $pages->getPage(1);
    $page_width = $page->getWidth(); //$page->getWidthAndHeight();
    //Convert footer_text to utf-8
    if (get_option('wp_pdf_stamp_use_utf_font') == '1') {
        $footer_text = mb_convert_encoding($footer_text, 'UTF-16BE');
    }

    //$wrap_length = round(($page_width*1.7/$font_size), 2);
    //$footer_text = wordwrap($footer_text, $wrap_length, "\n");	
    $stamp->setWidth($page_width - 120); //(pagewidth - (leftmargin + rightmargin))= pagewidth - 120	
    $stamp->setText($footer_text);

    $stamp->setLineHeight($line_space * 2.7);
    $stamp->setTextColor(new SetaPDF_Core_DataStructure_Color_Rgb($color_red, $color_green, $color_blue));
    //$stamp->setOpacity(0.5);//TODO - add as a new settings?
    
    // Set position according to settings - http://manuals.setasign.com/setapdf-api-reference/c/SetaPDF.Stamper
    if ($stamp_position == '1') {//Stamp in the footer
        $stamp_text_pos = "B";
        $stamp_distance = $distance_from_bottom;
        $translateY = ($distance_from_bottom * -1); //Make it a positive number
    } else if ($stamp_position == '2') {//Stamp in the header
        $stamp_text_pos = "T";
        $stamp_distance = $distance_from_header;
        $translateY = ($distance_from_header * -1);
    } else {//Catch all and go footer
        $stamp_text_pos = "B";
        $stamp_distance = $distance_from_bottom;
        $translateY = ($distance_from_bottom * -1); //Make it a positive number
    }

    // Calculatue alignment and position
    $position = $alignment . $stamp_text_pos; //Example: CB (center bottom). SetaPDF_Stamper::POSITION_CENTER_BOTTOM
    
    $text_align = 'left';
    //Calculate stamp position movement on x-axis
    if ($alignment == "R") {
        $translateX = -60;
        $text_align = 'right';
    } else if ($alignment == "L") {
        $translateX = 60;
        $text_align = 'left';
    } else {
        $translateX = 0;
        $text_align = 'center';
    }
    //Set the text alignment according to settings
    $stamp->setAlign($text_align);
        
    //Find out which pages to stamp
    $pdf_stamper_start_page = get_option('wp_pdf_start_stamping_from_page_number');
    if (empty($pdf_stamper_start_page)) {
        $pdf_stamper_start_page = 1;
    }
    $pdf_stamper_end_page = get_option('wp_pdf_stamping_end_page_number');
    $pages_to_stamp = 'all'; //SetaPDF_Stamper::PAGES_ALL
    if (is_numeric($pdf_stamper_end_page)) {
        //Stamping upto a page
        $pages_to_stamp = $pdf_stamper_start_page . '-' . $pdf_stamper_end_page; //1-20
    } else if ($pdf_stamper_start_page != 1 && strtolower($pdf_stamper_end_page) == 'last') {
        //Starting Stamping from a set page number to last
        $pages_to_stamp = $pdf_stamper_start_page . '-' . $page_count;
    } else {
        $pages_to_stamp = 'all';
    }

    pdf_stamper_debug("Stamp position: ".$position.", TranslateX: ".$translateX.", TranslateY: ".$translateY.", Pages to Stamp: ".$pages_to_stamp, true);
    
    //Add the stamp	
    $stamper->addStamp($stamp, array(
        'position' => $position,
        'translateX' => $translateX,
        'translateY' => $translateY,
        'showOnPage' => $pages_to_stamp,
    ));

    // stamp the document
    $stamper->stamp();

    // save and finish writing the document
    $document->save()->finish();

    //Set the output file as the return vlaue
    $retVal = $output_file;
    pdf_stamper_debug("PDF stamp file writing complete.", true);
    
    
} catch (Exception $e) {
    $retVal = 'There was an exception when trying to stamp the file. Error Details: ' . $e->getMessage() . "\n";
    pdf_stamper_debug("Error! An error occured while trying to stamp and write the PDF file." . $e->getMessage(), false);
    //echo $retVal;
}

// ========= Lets check and do the PDF file encryption ========
if (get_option('wp_pdf_stamp_enable_encryption') == '1') {
    pdf_stamper_debug("PDF file encryption is enabled. Encrypting the file...", true);
    try {    
        $encrypted_input_file = $output_file;
        $encrypted_output_file = str_replace('.pdf', '', $encrypted_input_file) . '_e.pdf';

        // create a writer
        $writer = new SetaPDF_Core_Writer_File($encrypted_output_file);

        // get a document instance
        $document = SetaPDF_Core_Document::loadByFilename($encrypted_input_file, $writer);

        //Permissions
        $permissionsNew = 0;
        if ($allow_print) {
            $permissionsNew = $permissionsNew | SetaPDF_Core_SecHandler::PERM_PRINT;
        }
        if ($allow_modify){
            $permissionsNew = $permissionsNew | SetaPDF_Core_SecHandler::PERM_MODIFY;
        }
        if ($allow_copy){
            $permissionsNew = $permissionsNew | SetaPDF_Core_SecHandler::PERM_COPY;
        }

        //Security handler object
        $secHandler = SetaPDF_Core_SecHandler_Standard_Arcfour40::factory(
            $document,
            $ownerpass,
            $userpass,
            $permissionsNew
        );

        // Attach the handler to the document
        $document->setSecHandler($secHandler);

        $document->save()->finish();

        $retVal = $encrypted_output_file;
        pdf_stamper_debug("PDF file encryption complete.", true);
        pdf_stamper_debug("Deleting the temporary un-encrypted stamped copy of the file.", true);
        $file_deleted = unlink($output_file);
        if (!$file_deleted) {
            pdf_stamper_debug("Failed to delete the file from the server.", false);
        }
        
    } catch (Exception $e) {
        $retVal = 'There was an exception when trying to encrypt the file. Error Details: ' . $e->getMessage() . "\n";
        pdf_stamper_debug("Error! An error occured while trying to encrypt the PDF file." . $e->getMessage(), false);
        //echo $retVal;
    }
}

//Construct the final file URL and store value in the DB
if (strpos($retVal, "Error") !== false || empty($retVal)) {
    echo "Error! \n";
    echo $retVal;
    $stamping_success = 0;
    $stamping_msg = $retVal;
    return;
}

if (strpos($retVal, WP_PDF_STAMP_DOC_ROOT) !== false) {//convert the absolute path back to absolute URL
    $file_url = pdf_stamp_get_url_from_domain_path($retVal);
} else if (strpos($retVal, ABSPATH) !== false) {//convert the absolute path back to absolute URL
    $file_url = pdf_stamp_get_url_from_domain_path($retVal);
} else if (strpos($retVal, "../../../..") !== false) {//convert the relative url back to full url
    $file_url = pdf_stamp_get_full_url_from_relative_url($retVal);
} else {
    $file_url = $retVal;
}

//Add to the stamped file database    
$fields = array();
$fields['creation_time'] = current_time('mysql'); //date ("Y-m-d H:i:s");
$fields['file_url'] = $file_url;
$fields['cust_name'] = $customer_name;
$fields['cust_email'] = $customer_email;
$fields['txn_id'] = $transaction_id;
//$fields['stamped_text'] = $footer_text;
$updated = WpPdfStamperDbAccess::insert(WP_PDF_STAMPED_FILES_TABLE_NAME, $fields);

//echo "<br />File modified time: ".filemtime($src_file_to_process);	
//echo "<br />".$file_url." was last modified: " . date ("F d Y H:i:s.", filemtime($src_file_to_process));
pdf_stamper_debug("Stamping operation completed successfully.", true);
echo "Success! \n";
echo $file_url;

$stamping_success = 1;
$stamping_msg = 'File stamped successfully.';
$stamped_file_url = $file_url;

<?php

function manual_stamp_menu() {
    echo '<div class="wrap">';
    echo '<h2>WP PDF Stamper - Manual Stamping</h2>';
    echo '<div id="poststuff"><div id="post-body">';

    if (!wp_pdf_stamper_is_license_valid()) {
        echo '</div></div>';
        echo '</div>';
        return; //Do not display the page if licese key is invalid	
    }

    if (isset($_POST['stampPdf'])) {
        $message = "";
        $src_file_url = trim($_POST["wp_pdf_stamp_src_file_manual"]);
        //update_option('wp_pdf_stamp_line_distance_manual', (string)$_POST["wp_pdf_stamp_line_distance_manual"]);
        //update_option('wp_pdf_stamp_line_spacing_manual', (string)$_POST["wp_pdf_stamp_line_spacing_manual"]);
        $tmp_line_template = htmlentities(stripslashes($_POST['wp_pdf_stamp_line_template_manual']), ENT_COMPAT, "UTF-8");
        update_option('wp_pdf_stamp_line_template_manual', (string) $tmp_line_template);
        update_option('wp_pdf_stamp_src_file_manual', (string) $src_file_url);

        //Check URL validity
        $form_fields_validated = true;
        $validation_error_message = "";
        $stamper_url_validation_error_msg_ignore = "<p><i>If you know for sure that the URL is correct then ignore this message. You can copy and paste the URL in a browser's address bar to make sure the URL is correct.</i></p>";
        //if(WP_PDF_STAMP_DO_NOT_USE_CURL != '1')
        if (!pdf_stamper_is_valid_url_if_not_empty($src_file_url)) {
            $validation_error_message .= "<br /><strong>The URL that you specified in the \"URL of the source file\" field does not seem to be a valid URL! It seems to be giving a 404 error! Please check this value again:</strong>";
            $validation_error_message .= "<br />" . $src_file_url . "<br /><br />";
            $validation_error_message .= $stamper_url_validation_error_msg_ignore;
            $form_fields_validated = false;
            //$message .= $validation_error_message;
            echo '<div id="message" class="updated fade"><p>';
            echo $validation_error_message;
            echo '</p></div>';
        }

        $line_distance = ''; //It will take value from settings
        $line_space = ''; //It will take value from settings
        $returnValue = pdf_stamp_create_manual_stamp($line_distance, $line_space, $_POST['wp_pdf_stamp_line_template_manual'], $_POST["wp_pdf_stamp_src_file_manual"]);
        //print_r($returnValue);
        list ($status, $value) = explode("\n", $returnValue);
        if (strpos($status, "Success!") !== false) {
            $file_url = trim($value);
            $message .= "Files stamped successfully!";
            $message .= '<br /><br /><a href="' . $file_url . '" target="_blank">' . $file_url . '</a>';
        } else {
            $message .= "An error occurred while trying to stamp the file!";
            $message .= "<br />" . $value;
            print_r($returnValue);
        }
        if (!empty($message)) {
            echo '<div id="message" class="updated fade"><p>';
            echo $message;
            echo '</p></div>';
        }
    }
    ?>
    <div class="postbox">
        <h3 class="hndle"><label for="title">Manual PDF File Stamping</label></h3>
        <div class="inside">

            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

                <table class="form-table">

                    <tr valign="top">
                        <td colspan="2"><p class="description">It will take the rest of the required values (example: stamping position, line distance, font size etc.) from the <a href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php" target="_blank">settings section</a> of this plugin</p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Text to Stamp</th>
                        <td><textarea name="wp_pdf_stamp_line_template_manual" rows="3" cols="100"><?php echo get_option('wp_pdf_stamp_line_template_manual'); ?></textarea>
                            <br /><p class="description">This text will be stamped in the PDF file.</p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">URL of the Source File</th>
                        <td><input type="text" name=wp_pdf_stamp_src_file_manual value="<?php echo get_option('wp_pdf_stamp_src_file_manual'); ?>" size="100" />
                            <br /><p class="description">The URL of the file that you want to stamp. It will make a copy of this file and then stamp it.</p></td>
                    </tr>

                </table>

                <div class="submit">
                    <input type="submit" class="button-primary" name="stampPdf" value="Stamp It" />
                </div>

            </form>

        </div></div>

    <div style="background: #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0px 10px 0px;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
        <p>Manual stamping not working? Check the following resources:</p>
        <ul>    
            <li>1. <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper/?p=155" target="_blank">Manual stamping video tutorial</a></li>
            <li>2. <a href="http://www.tipsandtricks-hq.com/forum/topic/an-error-occurred-while-trying-to-stamp-the-file-pdf-file-creation-checklist" target="_blank">Manual stamping error checklist</a></li>
        </ul>
    </div>
    <?php
    echo '</div></div>';
    echo '</div>';
}

function pdf_stamp_create_manual_stamp($line_distance, $line_space, $footer_text, $src_file) {
    if (WP_PDF_STAMP_DO_NOT_USE_CURL == '1') {
        $returnValue = pdf_stamper_stamp_internal_file($src_file, '', '', '', '', '', $line_distance, $line_space, $footer_text);
    } else {
        $postURL = WP_PDF_STAMP_URL . "/api/stamp_api.php";
        // The Secret key
        $secretKey = get_option('wp_pdf_stamp_secret_key');
        // The site URL
        $domainURL = $_SERVER['SERVER_NAME'];
        // prepare the data
        $data = array();
        $data['secret_key'] = $secretKey;
        $data['requested_domain'] = $domainURL;
        $data['source_file'] = $src_file;
        $data['distance_from_bottom'] = $line_distance;
        $data['line_space'] = $line_space;
        $data['footer_text'] = $footer_text;

        // send data to post URL
        $ch = curl_init($postURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $returnValue = curl_exec($ch);
        curl_close($ch);
    }
    return $returnValue;
}

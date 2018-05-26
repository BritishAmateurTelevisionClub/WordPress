<?php


function pdf_stamper_misc_init_time_shortcodes_tasks() {
    if (isset($_POST['process_stamper_file_download_wp_user'])) {//Handle the stamper_download_button_for_wp_user shortcode
        //Sanitize data
        $file_url = base64_decode(sanitize_text_field($_POST['stamper_src_file_url']));
        //Stamp this file and process download
        //echo "<br />File to stamp: ".$file_url;

        if (!is_user_logged_in()) {//This is not a logged in WP User
            return; //Do not process this request
        }
        $current_user = wp_get_current_user();
        $src_file = $file_url;
        $customer_name = $current_user->user_firstname . " " . $current_user->user_lastname;
        $customer_email = $current_user->user_email;
        $customer_phone = '';
        $customer_address = '';
        $customer_business = '';
        $line_distance = '';
        $line_space = '';
        $footer_text = '';

        ob_start();
        $stamping_result = pdf_stamper_process_stamping_via_func($src_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $line_distance, $line_space, $footer_text);
        $output = ob_get_contents();
        ob_end_clean();
        
        if ($stamping_result['success']) {
            $stamped_file_url = trim($stamping_result['stamped_file_url']); //File stamped successfully!
            stamper_redirect_to_url($stamped_file_url);
        } else {
            //TODO - save message to session and show it above the download button area
            $message .= "An error occurred while trying to stamp the file!";
            $message .= "<br />" . $stamping_result['message'] . "<br />";
            echo $message;
        }
        exit;
    }
    
    if (isset($_POST['process_stamper_file_download_emember'])) {//Handle the stamper_download_button_for_emember shortcode
        //Sanitize data
        $file_url = base64_decode(sanitize_text_field($_POST['stamper_src_file_url']));
        //Stamp this file and process download        

        if (!wp_emember_is_member_logged_in()) {//This is not a logged in eMember user
            return; //Do not process this request
        }
        
        $auth = Emember_Auth::getInstance();
        
        $src_file = $file_url;
        $customer_name = $auth->getUserInfo('first_name') . " " . $auth->getUserInfo('last_name');
        $customer_email = $auth->getUserInfo('email');
        $customer_phone = $auth->getUserInfo('phone');
        $customer_address = $auth->getUserInfo('address_street').", " . $auth->getUserInfo('address_city').", ".$auth->getUserInfo('address_state').", ".$auth->getUserInfo('address_zipcode').", ".$auth->getUserInfo('country');
        $customer_business = $auth->getUserInfo('company_name');
        $line_distance = '';
        $line_space = '';
        $footer_text = '';

        ob_start();
        $stamping_result = pdf_stamper_process_stamping_via_func($src_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $line_distance, $line_space, $footer_text);
        $output = ob_get_contents();
        ob_end_clean();
        
        if ($stamping_result['success']) {
            $stamped_file_url = trim($stamping_result['stamped_file_url']); //File stamped successfully!
            stamper_redirect_to_url($stamped_file_url);
        } else {
            //TODO - save message to session and show it above the download button area
            $message .= "An error occurred while trying to stamp the file!";
            $message .= "<br />" . $stamping_result['message'] . "<br />";
            echo $message;
        }
        exit;
    }
    
}

add_shortcode('stamper_download_button_for_wp_user', 'stamper_download_button_for_wp_user_handler');
function stamper_download_button_for_wp_user_handler($args) {
    extract(shortcode_atts(array(
        'file_url' => '',
        'button_text' => 'Download Now',
        'button_image' => '',
        'new_window' => '',
    ), $args));

    if (empty($file_url)) {
        return '<p style="color: red;">Error! You must specify a value for the file_url parameter in this shortcode</p>';
    }

    if (!is_user_logged_in()) {//This is not a logged in WP User
        return '<div class="stamper_download_now_button_user_not_logged_in">You must be logged into the site to download the file.</div>';
    }

    $window_target = '';
    if (!empty($new_window)) {
        $window_target = 'target="_blank"';
    }
    
    $output .= '<div class="stamper_download_now_button_for_wp_user">';
    $output .= '<form method="post" action="" '.$window_target.'>';
    $output .= '<input type="hidden" name="stamper_src_file_url" value="' . base64_encode($file_url) . '" />';
    $output .= '<input type="hidden" name="stamper_form_time_value" value="' . strtotime("now") . '" />';
    $output .= '<input type="hidden" name="process_stamper_file_download_wp_user" value="1" />';

    if (!empty($button_image)) {//A button image is present in the shortcode
        $button_type .= '<input type="image" name="download_stamped_file_wp_user" src="' . $button_image . '" class="stamper_download_button_submit" />';
    } else {//Plain download button with text
        $button_type .= '<input type="submit" name="download_stamped_file_wp_user" class="stamper_download_button_submit" value="' . $button_text . '" />';
    }

    $output .= $button_type;
    $output .= '</form>';
    $output .= '</div>';
    return $output;
}

add_shortcode('stamper_download_button_for_emember', 'stamper_download_button_for_emember_handler');
function stamper_download_button_for_emember_handler($args) {
    extract(shortcode_atts(array(
        'file_url' => '',
        'button_text' => 'Download Now',
        'button_image' => '',
        'new_window' => '',
    ), $args));

    if (empty($file_url)) {
        return '<p style="color: red;">Error! You must specify a value for the file_url parameter in this shortcode</p>';
    }

    if (!function_exists('wp_eMember_install')){//eMember plugin is not installed.
        return '<div class="stamper_download_now_button_emember_missing">Error! You need to have the WP eMember plugin installed to be able to use this shortcode!';
    }
    if (!wp_emember_is_member_logged_in()) {//This is not a logged in eMember User
        return '<div class="stamper_download_now_button_user_not_logged_in">You must be logged into the site as a member to download the file.</div>';
    }

    $window_target = '';
    if (!empty($new_window)) {
        $window_target = 'target="_blank"';
    }
    
    $output = '';
    $output .= '<div class="stamper_download_now_button_for_emember">';
    $output .= '<form method="post" action="" '.$window_target.'>';
    $output .= '<input type="hidden" name="stamper_src_file_url" value="' . base64_encode($file_url) . '" />';
    $output .= '<input type="hidden" name="stamper_form_time_value" value="' . strtotime("now") . '" />';
    $output .= '<input type="hidden" name="process_stamper_file_download_emember" value="1" />';

    if (!empty($button_image)) {//A button image is present in the shortcode
        $button_type = '<input type="image" name="download_stamped_file_emember" src="' . $button_image . '" class="stamper_download_button_submit" />';
    } else {//Plain download button with text
        $button_type = '<input type="submit" name="download_stamped_file_emember" class="stamper_download_button_submit" value="' . $button_text . '" />';
    }

    $output .= $button_type;
    $output .= '</form>';
    $output .= '</div>';
    return $output;
}

add_action('ws_plugin__s2member_during_file_download_access', 'process_s2member_file_download_stamp');
function process_s2member_file_download_stamp($args) {
    $wp_ps_config = PDF_Stamper_Config::getInstance();
    if ($wp_ps_config->getValue('wp_pdf_stamp_enable_s2file_integration') != '1') {
        pdf_stamper_debug('s2member plugin integration is disabled in the settings. File stamping will not be done.', true);
        return;
    }

    if (isset($_REQUEST['s2member_file_download'])) {//S2Member file download
        $file_path = $args['file'];
        pdf_stamper_debug("s2member integration detected - file to stamp: " . $file_path, true);
        $file_type_pdf = stamper_is_file_pdf($file_path);
        if (!$file_type_pdf) {
            pdf_stamper_debug('Source file is not a PDF file so no stamping necessary for this file.', true);
            return;
        }

        //Get user details
        $current_user = wp_get_current_user();
        $src_file = $file_path;
        $customer_name = $current_user->user_firstname . " " . $current_user->user_lastname;
        $customer_email = $current_user->user_email;
        $customer_phone = '';
        $customer_address = '';
        $customer_business = '';
        $line_distance = '';
        $line_space = '';
        $footer_text = '';

        pdf_stamper_debug("s2member integration - invoking stamping for user: " . $customer_email, true);
        $stamping_result = pdf_stamper_process_stamping_via_func($src_file, $customer_name, $customer_email, $customer_phone, $customer_address, $customer_business, $line_distance, $line_space, $footer_text);

        if ($stamping_result['success']) {
            $stamped_file_url = trim($stamping_result['stamped_file_url']); //File stamped successfully!
            stamper_redirect_to_url($stamped_file_url);
        } else {
            //TODO - save message to session and show it above the download button area
            $message .= "An error occurred while trying to stamp the file!";
            $message .= "<br />" . $stamping_result['message'] . "<br />";
            echo $message;
        }
        exit;
    }
}

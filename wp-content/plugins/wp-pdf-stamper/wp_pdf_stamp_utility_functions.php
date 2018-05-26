<?php

function pdf_stamper_check_if_url_missing_http($src_file_url) {
    if (WP_PDF_STAMP_CHECK_URL_VALIDITY !== '1') {
        return true; //Admin turned off URL validation
    }
    if (strpos($src_file_url, "www.") !== false) {//This is a URL value
        if (strpos($src_file_url, "http") === false) {//This URL is missing the http keyword
            return false;
        }
    }
    return true;
}

function pdf_stamper_get_abs_path_from_src_file($src_file, $relative_path_to_wpurl = '') {
    if (preg_match("/http/", $src_file)) {
        $path = parse_url($src_file, PHP_URL_PATH);

        $path_conv_method = get_option('wp_pdf_stamp_file_path_conv_method');
        if (empty($path_conv_method) || $path_conv_method == '1') {//Method 1
            $abs_path = WP_PDF_STAMP_DOC_ROOT . $path; //$_SERVER['DOCUMENT_ROOT'].$path;
            //$abs_path = realpath($abs_path);
            $abs_path = str_replace('//', '/', $abs_path); //replace extra slashes
        } else if ($path_conv_method == '2') {//Method 2
            $abs_path = ABSPATH . $path;
            $abs_path = str_replace('//', '/', $abs_path);
        } else if ($path_conv_method == '3') {//Method 3
            $wpurl = pdf_stamper_get_wpurl();
            $abs_path = str_replace($wpurl, ABSPATH, $src_file);
            $abs_path = realpath($abs_path);
        } else if ($path_conv_method == '4') {//Method 4
            $abs_path = ABSPATH . $path;
            $abs_path = str_replace('//', '/', $abs_path);
        }

        //Method5
        //$relative_path = pdf_stamp_get_relative_url_from_full_url($src_file,$relative_path_to_wpurl);
        //$abs_path = realpath($relative_path);
        if (empty($abs_path)) {//Default fallback
            $wpurl = pdf_stamper_get_wpurl();
            $abs_path = str_replace($wpurl, ABSPATH, $src_file);
            $abs_path = realpath($abs_path);
        }
    } else {
        $relative_path = $src_file;
        $abs_path = realpath($relative_path);
    }
    return $abs_path;
}

function pdf_stamp_convert_to_domain_path_from_src_file($src_file) {
    $domain_path = "";
    if (strpos($src_file, $_SERVER['SERVER_NAME']) !== false) { //Full URL
        $domain_path = pdf_stamp_get_domain_path_from_url($src_file);
    } else { //Relative URL
        $domain_path = $src_file;
    }
    return $domain_path;
}

function pdf_stamp_get_domain_path_from_url($src_file_url) {
    $domain_url = $_SERVER['SERVER_NAME'];
    $absolute_path_root = WP_PDF_STAMP_DOC_ROOT; //$_SERVER['DOCUMENT_ROOT'];

    $domain_name_pos = strpos($src_file_url, $domain_url);
    $domain_name_length = strlen($domain_url);
    $total_length = $domain_name_pos + $domain_name_length;

    //Get the absolute path for the file
    $src_file = substr_replace($src_file_url, $absolute_path_root, 0, $total_length);
    return $src_file;
}

function pdf_stamp_get_url_from_domain_path($domain_path) {
    $path_conv_method = get_option('wp_pdf_stamp_file_path_conv_method');
    if (empty($path_conv_method) || $path_conv_method == '1') {
        $file_url = str_replace(WP_PDF_STAMP_DOC_ROOT, '/', $domain_path);
        $file_url_without_http = $_SERVER['SERVER_NAME'] . $file_url;
    } else if ($path_conv_method == '2') {
        $file_url = str_replace(ABSPATH, '/', $domain_path);
        $file_url_without_http = $_SERVER['SERVER_NAME'] . $file_url;
    } else if ($path_conv_method == '3') {
        //Do the opposit of what was done to convert to path for method 3
        $wpurl = pdf_stamper_get_wpurl();
        $wpurl_with_trailing_slash = rtrim($wpurl, '/') . '/';
        $full_file_url = str_replace(ABSPATH, $wpurl_with_trailing_slash, $domain_path);
        return $full_file_url;
    } else if ($path_conv_method == '4') {
        $wpurl = pdf_stamper_get_wpurl();
        $file_url = str_replace(ABSPATH, '/', $domain_path);
        $full_file_url = $wpurl . $file_url; //This is a complete URL so return this value
        return $full_file_url;
    }

    $file_url_without_http = str_replace('//', '/', $file_url_without_http);

    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    $pageURL .= $file_url_without_http;
    return $pageURL;
}

function pdf_stamp_get_relative_url_from_full_url($src_file_url, $relative_path_to_wpurl = '') {
    if (empty($relative_path_to_wpurl)) {
        $relative_path_to_wpurl = '../../../..';
    }
    $wpurl = get_bloginfo('wpurl');
    $relative_url = str_replace($wpurl, $relative_path_to_wpurl, $src_file_url);
    return $relative_url;
}

function pdf_stamp_get_full_url_from_relative_url($src_file_url, $relative_path_to_wpurl = '') {
    if (empty($relative_path_to_wpurl)) {
        $relative_path_to_wpurl = '../../../..';
    }
    $wpurl = get_bloginfo('wpurl');
    $full_url = str_replace($relative_path_to_wpurl, $wpurl, $src_file_url);
    return $full_url;
}

function pdf_stamper_getTime() {
    $a = explode(' ', microtime());
    return(double) $a[0] + $a[1];
}

function pdf_stamper_is_valid_url_if_not_empty($url) {
    if (empty($url)) {
        return true;
    } else {
        return pdf_stamper_is_valid_url($url);
    }
}

function pdf_stamper_is_valid_url($url) {
    if (WP_PDF_STAMP_CHECK_URL_VALIDITY != '1') {
        return true; //Admin turned off URL validation
    }
    //Validate the URL
    $orig_url = $url;
    $url = @parse_url($url);
    if (!$url) {
        return false;
    }
    $url = array_map('trim', $url);
    $scheme = $url['scheme'];
    if ($scheme == "https") {
        $url['port'] = 443;
    }
    $url['port'] = (!isset($url['port'])) ? 80 : (int) $url['port'];
    $path = (isset($url['path'])) ? $url['path'] : '';
    if ($path == '') {
        $path = '/';
    }
    $path .= ( isset($url['query']) ) ? "?$url[query]" : '';
    if (isset($url['host']) AND $url['host'] != gethostbyname($url['host'])) {
        if (PHP_VERSION >= 5) { //Primary checking method
            if (ini_get('allow_url_fopen') != '1') {
                //do nothing... it will fall back to the 2nd second checking method
            } else {
                $headers = get_headers("$url[scheme]://$url[host]:$url[port]$path");
                $headers = ( is_array($headers) ) ? implode("\n", $headers) : $headers;
                return (bool) preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
            }
        }

        if (function_exists('fsockopen')) { //Alternate checking method using fsockopen
            $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);
            if (!$fp) {
                return false;
            }
            fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
            $headers = fread($fp, 128);
            fclose($fp);
            $headers = ( is_array($headers) ) ? implode("\n", $headers) : $headers;
            return (bool) preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
        }


        if (function_exists('curl_init')) {//Alternate checking method using CURL
            $ch = curl_init($orig_url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpcode >= 200 && $httpcode < 300) {
                return true;
            } else {
                return false;
            }
        } else {
            return true; //Could not validate... just return true anyway.
        }
    }
    return false;
}

function pdf_stamper_post_data_using_curl($postURL, $data) {
    if (!function_exists('curl_init')) {
        return "Error! \nNO CURL";
    }
    // send data to post URL
    $ch = curl_init($postURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $returnValue = curl_exec($ch);
    curl_close($ch);
    return $returnValue;
}

function pdf_stamper_is_multisite_install() {
    if (function_exists('is_multisite') && is_multisite()) {
        return true;
    } else {
        return false;
    }
}

function pdf_stamper_get_wpurl() {
    if (pdf_stamper_is_multisite_install()) {//MS install
        $wpurl = site_url();
    } else {
        $wpurl = get_bloginfo('wpurl');
    }
    return $wpurl;
}

function stamper_is_file_pdf($src_file) {
    $file_info = pathinfo($src_file);
    $file_extension = $file_info['extension'];
    pdf_stamper_debug('Source file type check... file extension: ' . $file_extension, true);
    $pos = stripos($file_extension, 'pdf');
    if ($pos !== false) {
        return true;
    }
    return false;
}

function stamper_redirect_to_url($url, $delay = '0', $exit = '1') {
    if (empty($url)) {
        echo "<br /><strong>Error! The URL value is empty. Please specify a correct URL value to redirect to!</strong>";
        exit;
    }
    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        echo '<meta http-equiv="refresh" content="' . $delay . ';url=' . $url . '" />';
    }
    if ($exit == '1') {//exit
        exit;
    }
}

function pdf_stamp_delete_stamped_record($result) {
    $domain_path = pdf_stamper_get_abs_path_from_src_file($result->file_url);
    if (is_file($domain_path)) {
        $file_deleted = unlink($domain_path);
    }
    $cond = ' file_id = ' . $result->file_id;
    $result = WpPdfStamperDbAccess::delete(WP_PDF_STAMPED_FILES_TABLE_NAME, $cond);
    return true;
}

function pdf_stamp_get_user_ip() {
    $user_ip = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $user_ip = $_SERVER['REMOTE_ADDR'];
    }

    if (strstr($user_ip, ',')) {
        $ip_values = explode(',', $user_ip);
        $user_ip = $ip_values['0'];
    }

    return apply_filters('pdf_stamper_get_user_ip', $user_ip);
}
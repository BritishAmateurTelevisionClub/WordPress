<?php

/**
 *     Plugin Name:  WP PDF Stamper
 *     Plugin URI:   https://www.tipsandtricks-hq.com/wp-pdf-stamper-plugin-2332
 *     Description:  A plugin to stamp your PDF files with customer details and protect it with various security options to discourage sharing of the file.
 *     Version:      4.5.9
 *     Author:       Tips and Tricks HQ
 *     Author URI:   https://www.tipsandtricks-hq.com/
 */

if (!defined('ABSPATH')) {//Exit if accessed directly
    exit;
}

define('WP_PDF_STAMP_VERSION', "4.5.9");
define('WP_PDF_STAMPER_DB_VERSION', '1.1');
define('WP_PDF_STAMP_SITE_HOME_URL', home_url());
define('WP_PDF_STAMP_FOLDER', dirname(plugin_basename(__FILE__)));
define('WP_PDF_STAMP_URL', plugins_url('', __FILE__));
define('WP_PDF_STAMP_PATH', plugin_dir_path(__FILE__));
define("PDF_STAMPER_MANAGEMENT_PERMISSION", "manage_options");

if (!empty($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'])) {
    define('WP_PDF_STAMP_DOC_ROOT', $_SERVER['SUBDOMAIN_DOCUMENT_ROOT']);
} else {
    define('WP_PDF_STAMP_DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
}

define('WP_PDF_STAMP_USE_CACHE', "0");
define('WP_PDF_STAMP_USE_UTF_FONT', "1"); //Use a value of 2 for "mingliu" font. Use 3 for "Big5" font. Use 4 for "Japanese SJIS" font.
define('WP_PDF_STAMP_DISABLE_PHP_EXECUTION_TIMER', "0");
define('WP_PDF_STAMP_DO_NOT_USE_CURL', "1");
define('WP_PDF_STAMP_CHECK_URL_VALIDITY', "1");

include_once('pdf_stamper_configs.php');
$wp_ps_config = PDF_Stamper_Config::getInstance();
include_once('wp_pdf_stamp1.php');

//Installer
function wp_pdf_stamper_install() {
    require_once(dirname(__FILE__) . '/pdf_stamper_installer.php');
    wp_pdf_stamper_run_installer();
    wp_schedule_event(time(), 'daily', 'wppdfs_daily_cron_event');
}

register_activation_hook(__FILE__, 'wp_pdf_stamper_install');

function wp_pdf_stamper_uninstall() {
    wp_clear_scheduled_hook('wppdfs_daily_cron_event');
}

register_deactivation_hook(__FILE__, 'wp_pdf_stamper_uninstall');

function wp_pdf_stamper_add_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $settings_link = '<a href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'wp_pdf_stamper_add_settings_link', 10, 2);

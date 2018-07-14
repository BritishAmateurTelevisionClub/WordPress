<?php
/*
  Plugin Name: WooCommerce PDF Invoices, Packing Slips, Delivery Notes & Shipping Labels(Basic)
  Plugin URI: https://www.xadapter.com/product/print-invoices-packing-list-labels-for-woocommerce/
  Description: Prints Packing List,Invoice,Delivery Note & Shipping Label.
  Version: 2.3.6
  Author: WebToffee
  Author URI: https://www.webtoffee.com/
  Text Domain: wf-printinvoice-packingslip
  WC tested up to: 3.4.3
 */

// to check wether accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!defined('WF_INVOICE_MAIN_ROOT_PATH')) {
    define('WF_INVOICE_MAIN_ROOT_PATH', plugin_dir_url(__FILE__));
}
if (!defined('WF_INVOICE_CURRENT_VERSION')) {
    define("WF_INVOICE_CURRENT_VERSION", "2.3.6");
}
if (!defined('WF_INVOICE_MAIN_FILE_PATH')) {
    define('WF_INVOICE_MAIN_FILE_PATH', plugin_dir_path(__FILE__));
}
// for Required functions
if (!function_exists('wf_is_woocommerce_active')) {
    require_once ('wf-includes/wf-functions.php');
}

// to check woocommerce is active
if (!(wf_is_woocommerce_active())) {
    return;
}

function wf_pklist_pre_activation_check() {
    //check if basic version is there
    if (is_plugin_active('print-invoices-packing-slip-labels-for-woocommerce/wf-woocommerce-packing-list.php')) {
        deactivate_plugins(basename(__FILE__));
        wp_die(__("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete Print Invoices, Packing List & Labels for WooCommerce (BASIC) and then try again", "wf-woocommerce-packing-list"), "", array('back_link' => 1));
    } else if (is_plugin_active('shipping-labels-for-woo/wf-woocommerce-packing-list.php')) {
        deactivate_plugins(basename(__FILE__));
        wp_die(__("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete WooCommerce Shipping Labels (BASIC) and then try again", "wf-woocommerce-packing-list"), "", array('back_link' => 1));
    }
}

register_activation_hook(__FILE__, 'wf_pklist_pre_activation_check');

// to check if option is present
if (get_option(('woocommerce_wf_invoice_as_ordernumber')) === false) {
    update_option('woocommerce_wf_invoice_as_ordernumber', 'Yes');
}

// to check if option is present
if (get_option('woocommerce_wf_generate_for_orderstatus') === false) {
    $data = array("wc-completed");
    update_option('woocommerce_wf_generate_for_orderstatus', $data);
}

if (!class_exists('Wf_Document_common')) {
    require_once ('class-wf-document-common.php');
}

if (!class_exists('Wf_WooCommerce_Packing_List')) {

    // class for Invoice and Packing List Printing
    class Wf_WooCommerce_Packing_List extends Wf_Document_common {

        var $invoice;
        var $deliverynote;
        var $dispatchlabel;
        var $packinglist;
        var $shippinglabel;

        // initializing the class
        public function __construct() {

            if (isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list' && isset($_GET['theme']) && isset($_GET['active_tab']) && $_GET['active_tab'] === 'invoice') {
                if (!isset($_GET['deactive'])) {
                    update_option('wf_invoice_active_key', $_GET['theme']);
                } else {
                    update_option($_GET['theme'] . 'deactive', 'no');
                }
            }
            if (isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list' && isset($_GET['theme']) && isset($_GET['active_tab']) && $_GET['active_tab'] === 'packing_slip') {
                update_option('wf_packing_slip_active_key', $_GET['theme']);
            }
            if (isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list' && isset($_GET['theme']) && isset($_GET['active_tab']) && $_GET['active_tab'] === 'delivery_note') {
                update_option('wf_delivery_note_active_key', $_GET['theme']);
            }
            if (isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list' && isset($_GET['theme']) && isset($_GET['active_tab']) && $_GET['active_tab'] === 'shipping_label') {
                update_option('wf_shipping_label_active_key', $_GET['theme']);
            }

            if (isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list' && isset($_GET['theme']) && isset($_GET['active_tab']) && $_GET['active_tab'] === 'dispatch_label') {
                update_option('wf_dispatch_label_active_keys', $_GET['theme']);
            }
            
            add_action('init', array($this, 'load_plugin_textdomain'));
            add_action('init', array($this, 'init'));
            add_action('wp_ajax_wf_get_date_format_live', array($this, 'wf_get_date_format_live'));
            add_filter('woocommerce_admin_order_actions', array($this, 'wf_packinglist_alter_order_actions'), 10, 2); //to add print option at the end of each orders in orders page
            add_action('admin_init', array($this, 'wf_packinglist_print_window')); //to print the invoice and packinglist
            
            add_action('admin_menu', array($this, 'wf_packinglist_admin_menu')); //to add shipment label settings menu to main menu of woocommerce
            add_action('add_meta_boxes', array($this, 'wf_packinglist_add_box')); //to add meta box in every single detailed order page
            add_action('admin_print_scripts-edit.php', array($this, 'wf_packinglist_scripts')); //to load the js for label for client
            add_action('admin_print_scripts-post.php', array($this, 'wf_packinglist_scripts')); //to load the js for label for client
            add_action('admin_footer', array($this, 'wf_packinglist_bulk_admin_footer'), 10); //to add bulk option to orders page
            add_action('load-edit.php', array($this, 'wf_packinglist_order_bulk_action')); //to handle post id for bulk actions
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'wf_packinglist_action_links')); //to add settings, doc, etc options to plugins base
            add_filter('woocommerce_subscriptions_renewal_order_meta_query', array($this, 'wf_packinglist_remove_subscription_renewal_order_meta'), 10, 4);
            add_action('admin_enqueue_scripts', array($this, 'wf_packinglist_admin_scripts')); //to load the js for admin
            add_action('admin_print_styles', array($this, 'admin_scripts'));
            add_action('manage_shop_order_posts_custom_column', array($this, 'wf_custom_column_value_invoice'));
            add_filter('manage_edit-shop_order_columns', array($this, 'wf_custom_shop_order_column'));
            // add_action('woocommerce_email_after_order_table', array($this, 'wf_email_print_invoice_button'));
            add_filter('woocommerce_checkout_fields', array($this, 'custom_override_checkout_fields'));
            add_filter('woocommerce_email_attachments', array($this, 'attach_pdf_to_woo_email'), 10, 3);
            // uninstall feedback catch
            include_once 'includes/class-wf-plugin-uninstall-feedback.php';
        }

        /*
         * Initialize all required elements
         */

        public function init() {
            if (!class_exists('wf_order')) {
                include_once('includes/class-wf-legacy.php');
            }

            include_once ('includes/invoice/class-wf-invoice-base.php');
            $this->invoice = new Wf_Invoice_Base();

            $this->wf_pklist_init_fields(); //function to init values of the fields


            include_once ('includes/deliverynote/class-wf-deliverynote-base.php');
            $this->deliverynote = new Wf_Deliverynote_Base();

            include_once ('includes/dispatchlabel/class-wf-dispatchlabel-base.php');
            $this->dispatchlabel = new Wf_Dispatchlabel_Base();

            include_once ('includes/packinglist/class-wf-packinglist-base.php');
            $this->packinglist = new Wf_Packinglist_Base();

            include_once ('includes/shippinglabel/class-wf-shippinglabel-base.php');
            $this->shippinglabel = new Wf_Shippinglabel_Base();

            include_once ('theme/wf-invoice-template-theme.php');
            include_once ('theme/wf-invoice-template-theme_pdf.php');
            include_once ('theme/wf-packing-slip-template-theme.php');
            include_once ('theme/wf-packing-slip-template-theme_pdf.php');
            include_once ('theme/wf-delivery-note-template-theme.php');
            include_once ('theme/wf-shipping-label-template-theme.php');
            include_once ('theme/wf-dispatch-label-template-theme.php');
        }


        //function for initializing fields included from packaging type
        public function wf_pklist_init_fields() {
            $this->wf_package_type_options = array(
                'pack_items_individually' => __('Pack Items Individually', 'wf-woocommerce-packing-list'),
                'weight_based_packing' => __('Weight Based Packing', 'wf-woocommerce-packing-list'),
                'box_packing' => __('Box Packing', 'wf-woocommerce-packing-list'),
                'single_packing' => __('Single Package Per Order', 'wf-woocommerce-packing-list'),
            );
            $this->wf_weight_package_type_options = array(
                'pack_descending' => __('Pack Heavier Items First', 'wf-woocommerce-packing-list'),
                'pack_ascending' => __('Pack Lighter Items First', 'wf-woocommerce-packing-list'),
                'pack_simple' => __('Pack Purely Divided By Weight', 'wf-woocommerce-packing-list'),
            );
            $this->document_actions = array(
                'print_packing_list',
                'print_shipment_label',
                'print_delivery_note',
                'print_invoice',
                'print_dispatch_label',
                'download_invoice',
            );
            $this->create_package_documents = array(
                'print_packing_list',
                'print_shipment_label',
                'print_delivery_note',
            );
            $this->print_documents = array(
                'download_invoice' => 'Download Invoice',
            );
            $this->download_documents = array();
            $this->additional_data_fields = array(
                'Contact Number' => 'contact_number',
                'Email' => 'email',
            );
            $this->additional_invoice_data_fields = array(
                'Contact Number' => 'contact_number',
                'Email' => 'email',
                'SSN' => 'ssn',
                'VAT' => 'vat',
                'Customer Note' => 'cus_note',
            );
            $this->font_size_options = array(
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large',
            );
            $this->wf_pklist_font_list = $this->wf_pklist_get_fonts();
            
            $this->wf_package_type = get_option('woocommerce_wf_packinglist_package_type') != '' ? get_option('woocommerce_wf_packinglist_package_type') : 'single_packing';
            $this->wf_weight_package_type = get_option('woocommerce_wf_packinglist_weight_pacakge_type') != '' ? get_option('woocommerce_wf_packinglist_weight_pacakge_type') : 'pack_descending';
            $this->wf_maximum_pacakage_weight = get_option('woocommerce_wf_packinglist_weight_pacakge_maxweight') != '' ? get_option('woocommerce_wf_packinglist_weight_pacakge_maxweight') : '';
            $this->weight_unit = get_option('woocommerce_weight_unit');
            $this->dimension_unit = get_option('woocommerce_dimension_unit');
            $this->boxes = get_option('woocommerce_wf_packinglist_boxes', array());
            $this->wf_datamatrix = get_option('woocommerce_wf_packinglist_datamatrix_information') != '' ? get_option('woocommerce_wf_packinglist_datamatrix_information') : 'Yes';

            $this->woocommerce_wf_enable_invoice = get_option('woocommerce_wf_enable_invoice') != '' ? get_option('woocommerce_wf_enable_invoice') : 'Yes';
            $this->woocommerce_wf_enable_packing_slip = get_option('woocommerce_wf_enable_packing_slip') != '' ? get_option('woocommerce_wf_enable_packing_slip') : 'Yes';
            $this->woocommerce_wf_enable_delivery_note = get_option('woocommerce_wf_enable_delivery_note') != '' ? get_option('woocommerce_wf_enable_delivery_note') : 'Yes';
            $this->woocommerce_wf_packinglist_disable_total_weight = get_option('woocommerce_wf_packinglist_disable_total_weight') != '' ? get_option('woocommerce_wf_packinglist_disable_total_weight') : 'no';
            $this->woocommerce_wf_delivery_note_disable_total_weight = get_option('woocommerce_wf_delivery_note_disable_total_weight') != '' ? get_option('woocommerce_wf_delivery_note_disable_total_weight') : 'no';
            $this->woocommerce_wf_enable_dispath_label = get_option('woocommerce_wf_enable_dispath_label') != '' ? get_option('woocommerce_wf_enable_dispath_label') : 'Yes';
            
            $this->wf_enable_shipping_label = get_option('woocommerce_wf_enable_shipping_label') != '' ? get_option('woocommerce_wf_enable_shipping_label') : 'Yes';
            $this->wf_enable_multiple_shipping_label = get_option('woocommerce_wf_enable_multiple_shipping_label') != '' ? get_option('woocommerce_wf_enable_multiple_shipping_label') : 'No';
            $this->wf_add_variation = get_option('woocommerce_wf_packinglist_variation_data') != '' ? get_option('woocommerce_wf_packinglist_variation_data') : 'Yes';
            $this->wf_invoice_padding = get_option('woocommerce_wf_invoice_padding_number') != '' ? get_option('woocommerce_wf_invoice_padding_number') : 0;
            $this->wf_custom_footer = get_option('woocommerce_wf_packinglist_footer') != '' ? get_option('woocommerce_wf_packinglist_footer') : '';
            $this->wf_custom_footer_in = get_option('woocommerce_wf_packinglist_footer_in') != '' ? get_option('woocommerce_wf_packinglist_footer_in') : '';
            $this->wf_custom_footer_pk = get_option('woocommerce_wf_packinglist_footer_pk') != '' ? get_option('woocommerce_wf_packinglist_footer_pk') : '';
            $this->wf_custom_footer_dn = get_option('woocommerce_wf_packinglist_footer_dn') != '' ? get_option('woocommerce_wf_packinglist_footer_dn') : '';
            $this->wf_enable_delivery_note = get_option('woocommerce_wf_packinglist_deliverynote') != '' ? get_option('woocommerce_wf_packinglist_deliverynote') : 'No';
            $this->wf_attach_delivery_note = get_option('woocommerce_wf_attach_delivery_note') ? get_option('woocommerce_wf_attach_delivery_note') : array();
            $this->wf_attach_shipping_label = get_option('woocommerce_wf_attach_shipping_label', array());
            $this->wf_generate_invoice_for = get_option('woocommerce_wf_generate_for_orderstatus') ? get_option('woocommerce_wf_generate_for_orderstatus') : array("wc-completed");
            $this->woocommerce_wf_packinglist_rtl_settings_enable = get_option('woocommerce_wf_packinglist_rtl_settings_enable') != '' ? get_option('woocommerce_wf_packinglist_rtl_settings_enable') : 'No';

            $this->wf_pklist_font_name = get_option('woocommerce_wf_packinglist_font_name') ? get_option('woocommerce_wf_packinglist_font_name') : 'arial';
            $this->wf_pklist_font_size = get_option('woocommerce_wf_packinglist_font_size') ? get_option('woocommerce_wf_packinglist_font_size') : 'medium';
            if ($this->wf_pklist_font_name == 'big5') {
                if (!(file_exists(plugin_dir_path(__FILE__) . 'wf-template/pdf-templates/font/unifont/big5.ttf'))) {
                    $this->wf_pklist_font_name = 'arial';
                }
            }
            $this->wf_view_checkbox_data = get_option('wf_view_checkbox_data') != '' ? get_option('wf_view_checkbox_data') : 'No';
            $this->wf_view_checkbox_data_general = get_option('wf_view_checkbox_data_general') != '' ? get_option('wf_view_checkbox_data_general') : 'No';
            
            $this->wf_add_frontend_info = get_option('woocommerce_wf_packinglist_frontend_info') != '' ? get_option('woocommerce_wf_packinglist_frontend_info') : 'Yes';
            $this->wf_reduce_product_name = 'Yes';
            $this->wf_pklist_add_sku = get_option('woocommerce_wf_packinglist_add_sku') != '' ? get_option('woocommerce_wf_packinglist_add_sku') : 'No';
            $this->invoice_labels = apply_filters('wf_pklist_modify_invoice_labels', $this->invoice->get_invoice_labels());
            $this->packinglist_contactno_email = is_array(get_option('wf_packinglist_contactno_email')) ? get_option('wf_packinglist_contactno_email') : $this->additional_invoice_data_fields;
            $this->shippinglabel_contactno_email = is_array(get_option('wf_shippinglabel_contactno_email')) ? get_option('wf_shippinglabel_contactno_email') : $this->additional_data_fields;
            $this->deliverynote_contactno_email = is_array(get_option('wf_deliverynote_contactno_email')) ? get_option('wf_deliverynote_contactno_email') : $this->additional_invoice_data_fields;
            
            $this->wf_packinglist_plugin_path = $this->wf_packinglist_get_plugin_path();
            $this->wf_packinglist_brand_color = '080808';
        }   

        // Function to generate invoice number
        public function generate_invoice_number($order) {
            
            $order_num = $order->get_order_number();
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
            $wf_invoice_id = get_post_meta($order_id, 'wf_invoice_number', true);

            $wf_invoice_as_ordernumber = get_option('woocommerce_wf_invoice_as_ordernumber');
            if ($wf_invoice_as_ordernumber == "Yes") {
                if (!empty($wf_invoice_id)) {
                    return $wf_invoice_id;
                } else {
                    $padded_invoice_number = $this->invoice->add_invoice_padding($order_num);
                    $invoice_number = $this->invoice->add_postfix_prefix($padded_invoice_number);
                    update_post_meta($order_id, 'wf_invoice_number', $invoice_number);
                    return $invoice_number;
                }
            } else {
                if (!empty($wf_invoice_id)) {
                    return $wf_invoice_id;
                } else {
                    $Current_invoice_number = get_option('woocommerce_wf_Current_Invoice_number');
                    update_option('woocommerce_wf_Current_Invoice_number', ++$Current_invoice_number);
                    $new_invoice_number = get_option('woocommerce_wf_Current_Invoice_number');
                    $padded_invoice_number = $this->invoice->add_invoice_padding($new_invoice_number);
                    $invoice_number = $this->invoice->add_postfix_prefix($padded_invoice_number);
                    update_post_meta($order_id, 'wf_invoice_number', $invoice_number);
                    return $invoice_number;
                }
            }
        }    

        public function wf_email_print_invoice_button($order) {
            $my_wc_order = $order;
            if (get_class($order) == 'WC_Order') {
                $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
                $woocommerce_wf_add_invoice_in_mail = get_option('woocommerce_wf_add_invoice_in_mail');
                
                if ($woocommerce_wf_add_invoice_in_mail == "Yes") {
                        $this->wf_send_new_email($my_wc_order, 'print_invoice');
                    
                }
            }
        }

        public function wf_send_new_email($order, $action) { 

            $order_num = $order->get_order_number();            
            ob_start();
            $content1 = '';
            $content1 .= ob_get_clean();
            $attachments = array();
            $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
            ob_start();
            if ($action === 'print_invoice') {
                
                $subject = ' ';
                $content1 = ' ';
                $content1 .= ob_get_clean();
                $woocommerce_wf_add_invoice_in_mail = get_option('woocommerce_wf_add_invoice_in_mail');
                if ($woocommerce_wf_add_invoice_in_mail == "Yes") {

                    $attachments = $this->wf_convert_html_to_pdf($attachments, $order, $action);
                    ob_start();
                    $subject = 'Invoice for your order';
                    $content1 = 'Hi,'.'<br><br>'.'Please find your invoice for the order'.''.$order_num;
                }
            }
            if ($action === 'print_packing_list') {
                $content1 = 'Packing List PDF';
                $create_order_packages = $this->wf_pklist_create_order_single_package($order);
                foreach ($create_order_packages as $order_package_id => $order_package) {

                    include $this->wf_packinglist_template('dir', 'wf-packinglist-pdf-template.php') . 'wf-packinglist-pdf-template.php';
                }
                $subject = 'Packing Slip for the Order';
            }
            if ($action === 'print_delivery_note') {
                $content1 = 'Delivery Note PDF';
                $create_order_packages = $this->wf_pklist_create_order_single_package($order);
                foreach ($create_order_packages as $order_package_id => $order_package) {
                    include $this->wf_packinglist_template('dir', 'wf-deliverynote-pdf-template.php') . 'wf-deliverynote-pdf-template.php';
                }
                $subject = 'Delivery Note for the Order';
            }

            $content1 .= ob_get_clean();
            $to = (WC()->version < '2.7.0' ) ? $order->billing_email : $order->get_billing_email();
            $message = $content1;
            $headers = array(
                'From: ' . get_option('woocommerce_email_from_name') . '  <' . get_option('woocommerce_email_from_address') . '>',
                'Reply-To: ' . get_option('woocommerce_email_from_name') . ' <' . get_option('woocommerce_email_from_address') . '>',
                'Content-Type: text/html; charset=UTF-8',
            );

            wp_mail($to, $subject, $message, $headers, $attachments);
        }

        public function wf_convert_html_to_pdf($attachments, $order, $action) {

            require_once 'includes/class-wf-pdf-creator.php';
            require_once 'includes/vendor/dompdf/src/Options.php';
            $dompdf = new wf_pdf_obj();
            // instantiate and use the dompdf class
            $invoice_number   = $this->generate_invoice_number($order);
            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/wf-invoices';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0700);
            }

            $dompdf->tempDir = $upload_dir;
            ob_start();
            $content = '';
            require_once $this->wf_packinglist_template('dir', 'wf-template-header.php') . 'wf-template-header.php';
            $content .= ob_get_clean();
            $content2 = '';
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-invoice-template-body.php') . 'wf-invoice-template-body.php';
            $content2 .= ob_get_clean();
            $content .= $content2;
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-template-footer.php') . 'wf-template-footer.php';
            $content .= ob_get_clean();
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('defaultFont', 'dejavu sans');
            // $dompdf->loadHtml('<p style ="";">&#8377</p>');
            $dompdf->loadHtml($content);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->set_option('font_subsetting', true);
            // Render the HTML as PDF
            $dompdf->render();
            if($action == 'download_invoice')
            {
                $dompdf->stream($upload_dir .'/'. $invoice_number.'.pdf');
                exit;

            }
            file_put_contents($upload_dir .'/'. $invoice_number.'.pdf', $dompdf->output());
            // Output the generated PDF to Browser
            $attachments = array($upload_dir .'/'. $invoice_number.'.pdf');
            return $attachments;
        }

        public function attach_pdf_to_woo_email($attachments, $status, $order) {
             $action = 'print_invoice';
            if(method_exists($order,'get_status'))
            {
                
                    $woocommerce_wf_add_invoice_in_mail = get_option('woocommerce_wf_add_invoice_in_mail');
                    if ($woocommerce_wf_add_invoice_in_mail == "Yes") {
                        $attachments = $this->wf_convert_html_to_pdf($attachments, $order, $action);
                    }
                return $attachments;
            }
        }

        public function wf_own_decode_method($data) {
            return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        }

        public function wf_get_date_format_live() {

            $src1 = $_POST['date_format'];
            echo date($src1, strtotime('now'));
            die();
        }

        public function load_plugin_textdomain() {
            load_plugin_textdomain('wf-woocommerce-packing-list', false, dirname(plugin_basename(__FILE__)) . '/lang');
        }

        // Our hooked in function - $fields is passed via the filter!
        public function custom_override_checkout_fields($fields) {
            $additional_options = array();
            if (get_option('woocommerce_wf_additional_fields')) {
                $additional_options = get_option('woocommerce_wf_additional_fields');
            }
            if (count(array_filter($additional_options)) > 0) {
                foreach ($additional_options as $value) {
                    $fields['billing']['billing_' . $value] = array(
                        'text' => 'text',
                        'label' => __(str_replace('_', ' ', $value), 'woocommerce'),
                        'placeholder' => _x('Enter ' . str_replace('_', ' ', $value), 'placeholder', 'woocommerce'),
                        'required' => false,
                        'class' => array('form-row-wide', 'align-left'),
                        'clear' => true
                    );
                }
                return $fields;
            }
            return $fields;
        }

        // function to add column "Invoice" in orders settings page
        public function wf_custom_shop_order_column($columns) {
            //add columns
            $columns['Invoice'] = __('Invoice', 'wf-woocommerce-packing-list');
            return $columns;
        }

        // function to add value in "Invoice" column 
        public function wf_custom_column_value_invoice($column) {

            global $post, $woocommerce, $the_order;
            if ($column == "Invoice") {
                $generate_invoice_for = array();
                $order_num = $post->ID;
                if (get_option('woocommerce_wf_generate_for_orderstatus')) {
                    $generate_invoice_for = get_option('woocommerce_wf_generate_for_orderstatus');
                }

                if (in_array(get_post_status($post->ID), $generate_invoice_for)) {
                    $invoice_number = $this->generate_invoice_number($the_order);
                    _e($invoice_number, 'wf-woocommerce-packing-list');
                } else {
                    _e("-", 'wf-woocommerce-packing-list');
                }
            }

            if ($this->woocommerce_wf_enable_invoice === 'Yes') {
                $this->print_documents['print_invoice'] = 'Print Invoice';
            }
            if ($this->woocommerce_wf_enable_packing_slip === 'Yes') {
                $this->print_documents['print_packing_list'] = 'Print Packing List';
            }
            if ($this->wf_enable_shipping_label === 'Yes') {
                $this->print_documents['print_shipment_label'] = 'Print Shipping Label';
            }
            if ($this->woocommerce_wf_enable_delivery_note === 'Yes') {
                $this->print_documents['print_delivery_note'] = 'Print Delivery Note';
            }
            if ($this->woocommerce_wf_enable_dispath_label === 'Yes') {
                $this->print_documents['print_dispatch_label'] = 'Print Dispatch Label';
            }
            // Get the order
            $wc_order = wc_get_order($post->ID);
            // Hidden content that will be injected as order button actions tooltip content in js
            if ($wc_order && ('order_actions' === $column or 'wc_actions' === $column)) {

                $wc_order_id = (WC()->version < '2.7.0') ? $wc_order->id : $wc_order->get_id();
                ?>
                <div id="wf-pklist-print-tooltip-order-actions-<?php echo $wc_order_id; ?>" class="wf-pklist-print-tooltip-order-actions wf-packing-list-link" style="display:none;">
                    <div class="wf-pklist-print-tooltip-content">

                        <ul><?php foreach ($this->print_documents as $id => $value) { ?>
                                <li>
                                    <a class="wf-pklist-print-document-tooltip-order-action wf-packing-list-link"
                                       href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . $wc_order_id . '&type=' . $id), 'print-packinglist'); ?>"
                                       target="_blank"><?php echo esc_html(_e($value, 'wf-woocommerce-packing-list')); ?>
                                    </a>
                                </li><?php } ?>
                        </ul>
                    </div>
                </div><?php
            }
        }

        // function to add print invoice packinglist button in admin orders page

        public function wf_packinglist_alter_order_actions($actions, $order) {
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
            $wf_pklist_print_options = array(
                array(
                    'name' => '',
                    'action' => 'wf_pklist_print_document',
                    'url' => sprintf('#%s', $order_id)
                ),
            );


            return array_merge($actions, $wf_pklist_print_options);
        }

        // function to add settings link to invoice packing-list-print plugin view
        public function wf_packinglist_action_links($links) {

            $plugin_links = array(
                '<a href="' . admin_url('admin.php?page=wf_woocommerce_packing_list') . '">' . __('Settings', 'wf-woocommerce-packing-list') . '</a>',
                '<a href="https://www.xadapter.com/category/product/print-invoices-packing-list-labels-for-woocommerce/" target="_blank">' . __('Documentation', 'wf-woocommerce-packing-list') . '</a>',
                '<a href="https://www.xadapter.com/online-support/" target="_blank">' . __('Support', 'wf-woocommerce-packing-list') . '</a>',
            );
            if (array_key_exists('deactivate', $links)) {
                    $links['deactivate'] = str_replace('<a', '<a class="wfinvoice-deactivate-link"', $links['deactivate']);
                }
            return array_merge($plugin_links, $links);
        }

        // function to start invoice and packinglist printing window
        public function wf_packinglist_print_window() {
            if (isset($_GET['print_packinglist'])) {
                $client = false;
                //	to check current user has rights to get invoice and packing list
                if (!isset($_GET['attaching_pdf'])) {
                    $nonce = key_exists('_wpnonce', $_GET) ? $_GET['_wpnonce'] : '';
                    if (!(wp_verify_nonce($nonce, 'print-packinglist')) || !(is_user_logged_in())) {
                        die(_e('You are not allowed to view this page.', 'wf-woocommerce-packing-list'));
                    }
                }

                remove_action('wp_footer', 'wp_admin_bar_render', 1000);
                // to get the orders number
                if (isset($_GET['email']) && isset($_GET['post']) && isset($_GET['user_print'])) {
                    $email_data_get = $this->wf_own_decode_method($_GET['email']);
                    $order_data_get = $this->wf_own_decode_method($_GET['post']);
                    $bulk_order_data = wc_get_order($order_data_get);
                    if ($email_data_get === ((WC()->version < '2.7.0') ? $bulk_order_data->billing_email : $bulk_order_data->get_billing_email())) {
                        $orders = explode(',', $this->wf_own_decode_method($_GET['post']));
                    } else {
                        die(_e('You are not allowed to view this page.', 'wf-woocommerce-packing-list'));
                    }
                } else {
                    $orders = explode(',', $_GET['post']);
                }
                $action = $_GET['type'];
                $attachments = array();
                $number_of_orders = count($orders);
                $order_loop = 0;
                $is_shipping_from_address_available = 0;
                // function to check that the shipping from address is added or not
                if ($this->wf_packinglist_check_from_address()) {
                    $is_shipping_from_address_available = 1;
                }
                switch ($action) {
                    case 'print_invoice':
                        $this->print_invoice($orders, $action);
                        break;
                    case 'print_packing_list':
                        $this->print_packinglist($orders, $action);
                        break;
                    case 'print_shipment_label':
                        $this->print_shippinglabel($orders, $action, $is_shipping_from_address_available);
                        break;
                    case 'print_delivery_note':
                        $this->print_deliverynote($orders, $action);
                        break;
                    case 'print_dispatch_label':
                        $this->print_dispatch_label($orders, $action);
                        break;
                    case 'download_invoice' :
                    foreach ($orders as $order_id)
                    {
                        $order = new WC_Order($order_id);
                        $this->wf_convert_html_to_pdf($attachments, $order, $action);
                    }
                        break;
                }
            }
        }

        private function print_shippinglabel($orders, $action, $is_shipping_from_address_available) {
            
            $number_of_orders = count($orders);
            $order_loop = 0;
            $enable_single_page_print = $this->wf_enable_multiple_shipping_label;

            $label_column_number = get_option('wf_shipping_label_column_number');
            if ((int) $label_column_number != $label_column_number || (int) $label_column_number <= 0) {
                $label_column_number = 4;
            }

            // building shipment label headers
            ob_start();
            $content = '';
            require_once $this->wf_packinglist_template('dir', 'wf-label-template-header.php') . 'wf-label-template-header.php';
            $content .= ob_get_clean();
            // function to check that the shipping from address is added or not
            if ($is_shipping_from_address_available == 1) {
                $content .= __("You need to Add Shipping from Address to Print shipping labels", 'wf-woocommerce-packing-list');
            } else {
                // building shipment label body
                $content1 = '';
                $count = 0;
                foreach ($orders as $order_id) {

                    if ($count % $label_column_number == 0 && 'Yes' == $enable_single_page_print) {
                        // addd div
                        $content1 .= '<div style="display: flex;flex-direction: row;justify-content: space-between;padding-bottom: 150px;">';
                    }

                    $order_loop++;
                    $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order_id) : new wf_order($order_id);
                    $order_additional_information = array(
                        'order' => $order
                    );
                    $order_additional_information = apply_filters('wf_pklist_label_add_additional_information', $order_additional_information);
                    ob_start();
                    $create_order_packages;
                    if (in_array($action, $this->create_package_documents)) {
                        $create_order_packages = $this->wf_pklist_create_order_package($order);
                    }
                    $order_package_loop = 0;
                    $number_of_order_package = count($create_order_packages);
                    if (!empty($create_order_packages)) {
                        foreach ($create_order_packages as $order_package_id => $order_package) {
                            $order_package_loop++;
                            ob_start();
                            include $this->wf_packinglist_template('dir', 'wf-label-template-body.php') . 'wf-label-template-body.php';
                            $content1 .= ob_get_clean();
                            if ('Yes' !== $enable_single_page_print) {
                                if ($number_of_order_package > 1 && $order_package_loop < $number_of_order_package) {
                                    $content1 .= "<p class=\"pagebreak\"></p><br/>";
                                } else {
                                    $content1 .= "<p class=\"no-page-break\"></p>";
                                }
                            }
                        }
                        if ('Yes' !== $enable_single_page_print) {
                            if ($number_of_orders > 1 && $order_loop < $number_of_orders) {
                                $content1 .= "<p class=\"pagebreak\"></p><br/>";
                            } else {
                                $content1 .= "<p class=\"no-page-break\"></p>";
                            }
                        }
                    } else {
                        wp_die(__("Unable to print Shipping Labels. Please check the items in the order.", "wf-woocommerce-packing-list"), "", array());
                    }
                    $count++;
                    if ($count % $label_column_number == 0 && 'Yes' == $enable_single_page_print) {
                        // close div
                        $content1 .= '</div>';
                    }
                }
                $content .= $content1;
            }
            // building shipment label footer
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-label-template-footer.php') . 'wf-label-template-footer.php';
            $content .= ob_get_clean();
            // outputing content to client window
            echo $content;
            exit;
        }

        private function print_dispatch_label($orders, $action) {


            $number_of_orders = count($orders);
            $order_loop = 0;
            // building packinglist headers
            ob_start();
            $content = '';
            require_once $this->wf_packinglist_template('dir', 'wf-template-header.php') . 'wf-template-header.php';
            $content .= ob_get_clean();
            // building packinglist body
            $content1 = '';
            foreach ($orders as $order_id) {
                $order_loop++;
                $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order_id) : new wf_order($order_id);
                ob_start();
                include $this->wf_packinglist_template('dir', 'wf_dispatch_label_body.php') . 'wf_dispatch_label_body.php';
                $content1 .= ob_get_clean();
                if ($number_of_orders > 1 && $order_loop < $number_of_orders) {
                    $content1 .= "<p class=\"pagebreak\"></p>";
                } else {
                    $content1 .= "<p class=\"no-page-break\"></p>";
                }
            }
            $content .= $content1;
            // building packinglist footer
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-template-footer.php') . 'wf-template-footer.php';
            $content .= ob_get_clean();
            // outputing content to client window
            echo $content;
            exit;
        }

        private function print_packinglist($orders, $action) {
            $number_of_orders = count($orders);
            $order_loop = 0;
            //building packinglist headers
            ob_start();
            $content = '';
            require_once $this->wf_packinglist_template('dir', 'wf-template-header.php') . 'wf-template-header.php';
            $content .= ob_get_clean();
            //building packinglist body
            $content1 = '';
            foreach ($orders as $order_id) {
                $order_loop++;
                $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order_id) : new wf_order($order_id);
                $create_order_packages;
                if (in_array($action, $this->create_package_documents)) {
                    $create_order_packages = $this->wf_pklist_create_order_package($order);
                }
                if ($this->wf_weight_package_type == 'pack_simple' && $this->wf_package_type == 'weight_based_packing') {
                    $create_order_packages = $this->wf_pklist_create_order_single_package($order);
                }
                $order_package_loop = 0;
                $number_of_order_package = count($create_order_packages);
                if (!empty($create_order_packages)) {
                    foreach ($create_order_packages as $order_package_id => $order_package) {
                        $order_package_loop++;
                        ob_start();
                        include $this->wf_packinglist_template('dir', 'wf-packinglist-template-body.php') . 'wf-packinglist-template-body.php';
                        $content1 .= ob_get_clean();
                        if ($number_of_order_package > 1 && $order_package_loop < $number_of_order_package) {
                            $content1 .= "<p class=\"pagebreak\"></p><br/>";
                        } else {
                            $content1 .= "<p class=\"no-page-break\"></p>";
                        }
                    }
                    if ($number_of_orders > 1 && $order_loop < $number_of_orders) {
                        $content1 .= "<p class=\"pagebreak\"></p><br/>";
                    } else {
                        $content1 .= "<p class=\"no-page-break\"></p>";
                    }
                } else {
                    wp_die(__("Unable to print Packing List. Please check the items in the order.", "wf-woocommerce-packing-list"), "", array());
                }
            }
            $content .= $content1;
            // building packinglist footer
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-template-footer.php') . 'wf-template-footer.php';
            $content .= ob_get_clean();
            // outputing content to client window
            echo $content;
            exit;
        }

        private function print_invoice($orders, $action) {


            $number_of_orders = count($orders);
            $order_loop = 0;
            // building packinglist headers
            ob_start();
            $content = '';
            
            $content .= ob_get_clean();
            // building packinglist body
            $content1 = '';
            foreach ($orders as $order_id) {
                $order_loop++;
                $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order_id) : new wf_order($order_id);
                ob_start();
                $invoice_number = $this->generate_invoice_number($order);
                require_once $this->wf_packinglist_template('dir', 'wf_template_header_for_invoice.php') . 'wf_template_header_for_invoice.php';
                include $this->wf_packinglist_template('dir', 'wf-invoice-template-body.php') . 'wf-invoice-template-body.php';
                $content1 .= ob_get_clean();
                if ($number_of_orders > 1 && $order_loop < $number_of_orders) {
                    $content1 .= "<p class=\"pagebreak\"></p>";
                } else {
                    $content1 .= "<p class=\"no-page-break\"></p>";
                }
            }
            $content .= $content1;
            // building packinglist footer
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-template-footer.php') . 'wf-template-footer.php';
            $content .= ob_get_clean();
            // outputing content to client window
            echo $content;
            exit;
        }

        private function print_deliverynote($orders, $action) {

            $number_of_orders = count($orders);
            $order_loop = 0;
            //building packinglist headers
            ob_start();
            $content = '';
            require_once $this->wf_packinglist_template('dir', 'wf-template-header.php') . 'wf-template-header.php';
            $content .= ob_get_clean();
            //building packinglist body
            $content1 = '';
            foreach ($orders as $order_id) {
                $order_loop++;
                $order = ( WC()->version < '2.7.0' ) ? new WC_Order($order_id) : new wf_order($order_id);

                $create_order_packages;
                if (in_array($action, $this->create_package_documents)) {
                    $create_order_packages = $this->wf_pklist_create_order_package($order);
                }
                if ($this->wf_weight_package_type == 'pack_simple' && $this->wf_package_type == 'weight_based_packing') {
                    $create_order_packages = $this->wf_pklist_create_order_single_package($order);
                }
                $order_package_loop = 0;
                $number_of_order_package = count($create_order_packages);
                if (!empty($create_order_packages)) {
                    foreach ($create_order_packages as $order_package_id => $order_package) {
                        $order_package_loop++;
                        ob_start();
                        include $this->wf_packinglist_template('dir', 'wf-deliverynote-template-body.php') . 'wf-deliverynote-template-body.php';
                        $content1 .= ob_get_clean();
                        if ($number_of_order_package > 1 && $order_package_loop < $number_of_order_package) {
                            $content1 .= "<p class=\"pagebreak\"></p><br/>";
                        } else {
                            $content1 .= "<p class=\"no-page-break\"></p>";
                        }
                    }
                    if ($number_of_orders > 1 && $order_loop < $number_of_orders) {
                        $content1 .= "<p class=\"pagebreak\"></p><br/>";
                    } else {
                        $content1 .= "<p class=\"no-page-break\"></p>";
                    }
                } else {
                    wp_die(__("Unable to print Delivery Note. Please check the items in the order.", "wf-woocommerce-packing-list"), "", array());
                }
            }
            $content .= $content1;
            // building packinglist footer
            ob_start();
            include $this->wf_packinglist_template('dir', 'wf-template-footer.php') . 'wf-template-footer.php';
            $content .= ob_get_clean();
            // outputing content to client window
            echo $content;
            exit;
        }

        public function wf_packinglist_template($type, $template) {


            $templates = array();
            if (file_exists(trailingslashit(get_stylesheet_directory()) . 'woocommerce/wf-template/' . $template)) {
                $templates['uri'] = trailingslashit(get_stylesheet_directory_uri()) . 'woocommerce/wf-template/';
                $templates['dir'] = trailingslashit(get_stylesheet_directory()) . 'woocommerce/wf-template/';
            } else {
                $templates['uri'] = $this->wf_packinglist_get_plugin_url() . '/wf-template/';
                $templates['dir'] = $this->wf_packinglist_get_plugin_path() . '/wf-template/';
            }

            return $templates[$type];
        }

        // to check preview is enabled for packinglist
        public function wf_packinglist_preview() {
            if (get_option('woocommerce_wf_packinglist_preview') != 'disabled') {
                return true;
            }
        }

        public function wf_packinglist_get_signature() {
            $logo_url = '';
            $logo_url = get_option('woocommerce_wf_packinglist_invoice_signature');
            return $logo_url;
        }

        // function to add company name
        public function wf_packinglist_get_companyname() {
            if (get_option('woocommerce_wf_packinglist_companyname') != '') {
                return stripslashes(get_option('woocommerce_wf_packinglist_companyname'));
            }
        }

        // function to get template body table body content
        public function wf_packinglist_get_table_content($order, $order_package, $show_price = false) {

            $return = "";
            $weight_of_item_and_box = !empty($order_package[0]['package_weight']) ? $order_package[0]['package_weight'] : ' ';
            $box_name = !empty($order_package[0]['title']) ? $order_package[0]['title'] : ' ';
            $weight_of_item = 0;
            foreach ($order_package as $order_package_individual_item) {
                $weight_of_item += (!empty($order_package_individual_item['weight'])) ? $order_package_individual_item['weight'] * $order_package_individual_item['quantity'] : __('0', 'wf-woocommerce-packing-list');
            }

            if (key_exists('Value', $order_package)) {
                $weight = ($order_package['Value'] != '') ? $order_package['Value'] : __('n/a', 'wf-woocommerce-packing-list');
            } else {
                $weight = apply_filters('wf_shipping_label_weight_customization', $weight_of_item, $weight_of_item_and_box);
            }

            $orderdetails = array(
                'order_id' => $order->get_order_number(),
                'weight' => ($weight != '') ? $weight . ' ' . get_option('woocommerce_weight_unit') : __('n/a', 'wf-woocommerce-packing-list'),
                'name' => !empty($order_package[0]['title']) ? $order_package[0]['title'] : ' '
            );
            return apply_filters('wf_pklist_modify_label_order_details', $orderdetails);
        }

        // function to add return policy
        public function wf_packinglist_get_return_policy() {
            if (get_option('woocommerce_wf_packinglist_return_policy') != '') {
                return nl2br(stripslashes(get_option('woocommerce_wf_packinglist_return_policy')));
            }
        }

        // fucntion to add footer
        public function wf_packinglist_get_footer($order, $document_type) {

            if (($document_type === 'print_invoice' || $document_type === 'print_dispatch_label') && $this->wf_custom_footer_in != '') {
                return $this->invoice->get_footer($order, $document_type);
            }
            if ($document_type === 'print_packing_list' && $this->wf_custom_footer_pk != '') {
                return $this->packinglist->get_footer($order, $document_type);
            }
            if ($document_type === 'print_delivery_note' && $this->wf_custom_footer_dn != '') {
                return $this->deliverynote->get_footer($order, $document_type);
            }
            return stripslashes(apply_filters('wf_pklist_customize_footer_information', $this->wf_custom_footer, $order, $document_type));
        }

        // fucntion to load client scripts
        public function wf_packinglist_client_scripts() {
            $version = '2.4.2';
            wp_register_script('woocommerce-packinglist-client-js', $this->wf_packinglist_get_plugin_url() . '/js/woocommerce-packinglist-client.js', array('jquery'), $version, true);
            if (is_page(get_option('woocommerce_view_order_page_id'))) {
                wp_enqueue_script('woocommerce-packinglist-client-js');
            }
        }

        // function to add menu in woocommerce
        public function wf_packinglist_admin_menu() {
            global $packinglist_settings_page;
            add_menu_page(__('Invoice/Pack List', 'wf-woocommerce-packing-list'), __('Invoice/Pack List', 'wf-woocommerce-packing-list'), 'manage_woocommerce', 'wf_woocommerce_packing_list', '', 'dashicons-welcome-view-site', 56);
            $packinglist_settings_page = add_submenu_page('wf_woocommerce_packing_list', __('Print Options', 'wf-woocommerce-packing-list'), __('Print Options', 'wf-woocommerce-packing-list'), 'manage_woocommerce', 'wf_woocommerce_packing_list', array(
                $this,
                'wf_woocommerce_packinglist_printing_page'
            ));
            add_submenu_page('null', __('Customize', 'wf-woocommerce-packing-list'), __('Customize', 'wf-woocommerce-packing-list'), 'manage_woocommerce', 'wf_template_customize_for_invoice', array(
                $this,
                'wf_woocommerce_invoice_customization_screen'
            ));
        }

        public function wf_woocommerce_invoice_customization_screen() {
            ?>
                        <div class="wrap">
                            <div id="icon-options-general" class="icon32"><br/></div>
                            <h2><?php _e('WooCommerce - Print Invoice, Packing Slip, Delivery Note & Label Settings', 'wf-woocommerce-packing-list'); ?></h2><?php
            include 'includes/wf-menu-content.php';
            include_once('wf-template/wf_template_choose.php');
            ?>
                        </div><?php
        }

        // function to add settings options in settings menu
        public function wf_woocommerce_packinglist_printing_page() {
            // check user access limit
            if (!current_user_can('manage_woocommerce')) {
                die("You are not authorized to view this page");
            }
            // functions to upload media
            wp_enqueue_media();
            ?>
            <div class="wrap">
                <div id="icon-options-general" class="icon32"><br/></div>
                <h2><?php _e('WooCommerce - Print Invoice, Packing Slip, Delivery Note & Label Settings', 'wf-woocommerce-packing-list'); ?></h2><?php
                if (isset($_POST['wf_packinglist_fields_submitted']) && $_POST['wf_packinglist_fields_submitted'] == 'submitted') {
                    $this->wf_packinglist_settings_data_validate();
                    foreach ($_POST as $key => $value) {
                        if (get_option($key) != $value) {
                            if ($key == "woocommerce_wf_packinglist_boxes") {
                                $value = $this->validate_box_packing_field($value);
                            }
                            update_option($key, $value);
                        } else {
                            if ($key == "woocommerce_wf_packinglist_boxes") {
                                $value = $this->validate_box_packing_field($value);
                            }
                            $status = add_option($key, $value, '', 'no');
                        }
                    }
                    ?>
                    <div id="message" class="updated"><p><strong><?php _e('Your settings have been saved.', 'wf-woocommerce-packing-list'); ?></strong></p></div><?php
                    $this->wf_pklist_init_fields();
                }
                ?>
                <div id="content"><?php
                    $plugin_name = 'packinglist';
                    ?>			

                    <script type="text/javascript">

                        $(document).ready(function () {

                            var url = document.location.toString();
                            if (url.match('#')) {
                                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
                            }

                            // Change hash for page-reload
                            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                                window.location.hash = e.target.hash;
                            })

                        });

                    </script>

                    <div class="wfinvoice-main-box"><?php include 'includes/wf-menu-content.php'; ?><?php include 'includes/wf-menu-settings-tab.php'; ?> 
                        
                        <div class="tool-box bg-white p-20p wfinvoice-view" style="padding-top:0px !important;">
                            <form method="post" action="" id="packinglist_settings">
                                <input type="hidden" name="wf_packinglist_fields_submitted" value="submitted">
                                <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
                                    <div class="tab-content"><?php
                                        include 'includes/wf-menu-callback-content.php';
                                        ?>              

                                    </div>


                                </nav>	

                            </form>
                        </div><?php include('includes/premium/market.php'); ?>
                        <div class="clearfix"></div>
                    </div
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.my-color-field').wpColorPicker();
                });
            </script><?php
        }

        // function to add admin meta box
        public function wf_packinglist_add_box() {
            add_meta_box('woocommerce-packinglist-box', __('Print Actions', 'wf-woocommerce-packing-list'), array(
                $this,
                'woocommerce_packinglist_create_box_content'
                    ), 'shop_order', 'side', 'default');
        }

        // function to add content to meta boxes
        function woocommerce_packinglist_create_box_content() {
            global $post;
            $order = ( WC()->version < '2.7.0' ) ? new WC_Order($post->ID) : new wf_order($post->ID);
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
            ?>
            <table class="form-table">
                <tr><?php if ($this->woocommerce_wf_enable_invoice === 'Yes') { ?><?php _e('<strong>Invoice Number: </strong> ' . get_post_meta($order_id, 'wf_invoice_number', true)); ?>
                        <td style="padding:6px 10px !important; ">

                            <a class="button tips wf-packing-list-link" target="_blank" data-tip="<?php esc_attr_e('Print Invoice', 'wf-woocommerce-packing-list'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=print_invoice'), 'print-packinglist'); ?>"><img src="<?php
                                echo $this->wf_packinglist_get_plugin_url() . '/assets/images/invoice-icon.png'; //exit();
                                ?>" alt="<?php esc_attr_e('Print Invoice', 'wf-woocommerce-packing-list'); ?>" width="14"><?php _e('Print Invoice ', 'wf-woocommerce-packing-list'); ?> </a> 
                                <a target = "_blank" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=download_invoice'), 'print-packinglist'); ?>"><img src = "<?php
                                echo $this->wf_packinglist_get_plugin_url() . '/assets/images/invoice-icon.png';
                                ?>"></a>
                        </td>
                    </tr><?php } ?><?php if ($this->woocommerce_wf_enable_packing_slip === 'Yes') { ?>
                    <tr>
                        <td style="padding:6px 10px !important; "><a class="button tips wf-packing-list-link" target="_blank" data-tip="<?php esc_attr_e('Print Packing List', 'wf-woocommerce-packing-list'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=print_packing_list'), 'print-packinglist'); ?>"><img src="<?php
                                echo $this->wf_packinglist_get_plugin_url() . '/assets/images/packinglist-icon.png'; //exit();
                                ?>" alt="<?php esc_attr_e('Print Packing List', 'wf-woocommerce-packing-list'); ?>" width="14"><?php _e('Print Packing List', 'wf-woocommerce-packing-list'); ?></a>
                        </td>
                    </tr><?php } ?><?php if ($this->wf_enable_shipping_label === 'Yes') { ?>
                    <tr>
                        <td style="padding:6px 10px !important; "><a class="button tips wf-packing-list-link" target="_blank" data-tip="<?php esc_attr_e('Print Shipping Label', 'wf-woocommerce-packing-list'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=print_shipment_label'), 'print-packinglist'); ?>"><img src="<?php
                                echo $this->wf_packinglist_get_plugin_url() . '/assets/images/Label-print-icon.png'; //exit();
                                ?>" alt="<?php esc_attr_e('Print Shipping Label', 'wf-woocommerce-packing-list'); ?>" width="14"><?php _e('Print Shipping Label', 'wf-woocommerce-packing-list'); ?></a>
                        </td>
                    </tr><?php } ?><?php if ($this->woocommerce_wf_enable_delivery_note === 'Yes') { ?>
                    <tr>
                        <td style="padding:6px 10px !important; "><a class="button tips wf-packing-list-link" target="_blank" data-tip="<?php esc_attr_e('Print Delivery Note', 'wf-woocommerce-packing-list'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=print_delivery_note'), 'print-packinglist'); ?>"><img src="<?php
                                echo $this->wf_packinglist_get_plugin_url() . '/assets/images/packinglist-icon.png'; //exit();
                                ?>" alt="<?php esc_attr_e('Print Delivery Note', 'wf-woocommerce-packing-list'); ?>" width="14"><?php _e('Print Delivery Note', 'wf-woocommerce-packing-list'); ?></a>
                        </td>
                    </tr><?php } ?><?php if ($this->woocommerce_wf_enable_dispath_label === 'Yes') { ?>
                    <td style="padding:6px 10px !important; ">

                        <a class="button tips wf-packing-list-link" target="_blank" data-tip="<?php esc_attr_e('Dispatch Label', 'wf-woocommerce-packing-list'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_packinglist=true&post=' . ($order_id) . '&type=print_dispatch_label'), 'print-packinglist'); ?>"><img src="<?php
                            echo $this->wf_packinglist_get_plugin_url() . '/assets/images/dlprint.png'; //exit();
                            ?>" alt="<?php esc_attr_e('Print Dispatch Label', 'wf-woocommerce-packing-list'); ?>" width="14"><?php _e('Print Dispatch Label', 'wf-woocommerce-packing-list'); ?> </a> 
                    </td>
                </tr><?php } ?>
            </table><?php
        }

        // Function to add required javascript files
        function wf_packinglist_scripts() {
            wp_register_script('woocommerce-packinglist-js', untrailingslashit(plugins_url('/', __FILE__)) . '/assets/js/woocommerce-packinglist.js', array('jquery'), '');
            wp_enqueue_script('woocommerce-packinglist-js');
        }

        function admin_scripts() {
            wp_enqueue_script('wc-enhanced-select');
            $plugin_url = untrailingslashit(plugins_url('/', __FILE__));
            wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css');
            wp_enqueue_style('woocommerce_admin_box_pack_styles', $plugin_url . '/assets/css/box_packing.css');
            wp_enqueue_style('wf_order_admin_styles', $plugin_url . '/assets/css/order-admin.css');

            if ((isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_packing_list') || (isset($_GET['page']) && $_GET['page'] === 'wf_template_customize_for_invoice')) {

                wp_enqueue_style('wf_invoice_customization_bootstrap_css', $plugin_url . '/assets/new_invoice_css_js/dist/css/bootstrap.min.css');
                wp_enqueue_style('wf_invoice_customization_font_awsome', $plugin_url . '/assets/new_invoice_css_js/font-awesome/css/font-awesome.min.css');
                wp_enqueue_style('wf_invoice_customization_custom_css', $plugin_url . '/assets/new_invoice_css_js/css/custom.css');
                wp_enqueue_script('wf_invoice_customization_jquery', $plugin_url . '/assets/new_invoice_css_js/dist/jquery.min.js');
                wp_enqueue_script('wf_invoice_customization_bootstrap', $plugin_url . '/assets/new_invoice_css_js/dist/js/bootstrap.min.js');
                wp_enqueue_script('wf_invoice_customization_jscolor', $plugin_url . '/assets/new_invoice_css_js/dist/js/jscolor.min.js');
                wp_enqueue_script('wf_invoice_customization', $plugin_url . '/assets/new_invoice_css_js/js/New_invoice_custom.js');
                wp_enqueue_script('wf_invoice_customization_sl', $plugin_url . '/assets/new_invoice_css_js/js/sl_custom.js');
            }
        }

        // Function to load scripts required for admin
        function wf_packinglist_admin_scripts($hook) {
            
            global $packinglist_settings_page;
            $plugin_url = $this->wf_packinglist_get_plugin_url();
            wp_enqueue_script('wf-order-admin-js', $plugin_url . '/assets/js/wf_order_admin.js', array('jquery'), '');
            if ($hook != $packinglist_settings_page) {
                return;
            }
            // Version number for scripts
            $version = '2.4.2';
            wp_register_script('wf-packinglist-admin-js', $plugin_url . '/assets/js/woocommerce-packinglist-admin.js', array('jquery'), $version);
            wp_register_script('wf-packinglist-validate', $plugin_url . '/assets/js/jquery.validate.min.js', array('jquery'), $version);
            $handle = 'wf_common';
            $list = 'enqueued';
            if (wp_script_is($handle, $list)) {
                wp_deregister_script('wf_common', $plugin_url . '/assets/js/wf_common.js', array('jquery'), $version);
            }
            wp_register_script('wf_common', $plugin_url . '/assets/js/wf_common.js', array('jquery'), $version);
            wp_enqueue_script('wf-packinglist-admin-js');
            wp_enqueue_script('wf-packinglist-validate');
            wp_enqueue_script('wf_common');
            wp_localize_script('wf_common', 'wf_common_params', array('ajaxurl' => admin_url('admin-ajax.php'), 'is_multiple_inonepage_enabled' => get_option('woocommerce_wf_enable_multiple_shipping_label') != '' ? get_option('woocommerce_wf_enable_multiple_shipping_label') : 'No',));
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        }

        // Function to handle bulk actions

        function wf_packinglist_bulk_admin_footer() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    if (jQuery('[name=woocommerce_wf_invoice_as_ordernumber]').is(':checked')) {
                        jQuery('.invoice_hide').hide();
                    }
                    jQuery('[name=woocommerce_wf_invoice_as_ordernumber]').click(function () {
                        if (this.checked) {
                            jQuery('.invoice_hide').hide();
                        } else
                            jQuery('.invoice_hide').show();
                    });
                    jQuery('[name=woocommerce_wf_invoice_regenerate]').click(function () {
                        if (this.checked) {
                            jQuery('[name=woocommerce_wf_invoice_start_number]').prop("readonly", false);
                        } else
                            jQuery('[name=woocommerce_wf_invoice_start_number]').prop("readonly", true);
                    });
                    jQuery('#order_status').change(function () {
                        //  Select options for Invoice select Box 
                        var multipleValues = jQuery('#order_status').val();
                        var multipletext = [];
                        jQuery('#order_status :selected').each(function (i, selected) {
                            multipletext[i] = jQuery(selected).text();
                        });
                        jQuery('#invoice_pdf').select2('val', '');
                        var select = jQuery('#invoice_pdf');
                        jQuery('option', select).remove();
                        for (i = 0; i < multipleValues.length; i++) {
                            jQuery('<option>').val(multipleValues[i]).text(multipletext[i]).appendTo("select[id='invoice_pdf']");
                        }
                        //  Select options for Packinglist select Box 
                        jQuery('#packinglist_pdf').select2('val', '');
                        var select = jQuery('#packinglist_pdf');
                        jQuery('option', select).remove();
                        for (i = 0; i < multipletext.length; i++) {
                            jQuery('<option>').val(multipleValues[i]).text(multipletext[i]).appendTo("select[id='packinglist_pdf']");
                        }
                        //  Select options for Delivery Note select Box 
                        jQuery('#deliverynote_pdf').select2('val', '');
                        var select = jQuery('#deliverynote_pdf');
                        jQuery('option', select).remove();
                        for (i = 0; i < multipletext.length; i++) {
                            jQuery('<option>').val(multipleValues[i]).text(multipletext[i]).appendTo("select[id='deliverynote_pdf']");
                        }
                    });
                });
            </script><?php
            global $post_type;
            if ('shop_order' == $post_type) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {<?php if ($this->woocommerce_wf_enable_packing_slip === 'Yes') { ?>
                            jQuery('<option>').val('print_packing_list').text("<?php _e('Print Packing List', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action']");

                            jQuery('<option>').val('print_packing_list').text("<?php _e('Print Packing List', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action2']");<?php } ?><?php if ($this->woocommerce_wf_enable_invoice === 'Yes') { ?>
                            jQuery('<option>').val('print_invoice').text("<?php _e('Print Invoice', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action']");

                            jQuery('<option>').val('print_invoice').text("<?php _e('Print Invoice', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action2']");<?php } ?><?php if ($this->wf_enable_shipping_label === 'Yes') { ?>
                            jQuery('<option>').val('print_shipment_label').text("<?php _e('Print Shipping Labels', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action']");

                            jQuery('<option>').val('print_shipment_label').text("<?php _e('Print Shipping Labels', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action2']");<?php } ?><?php if ($this->woocommerce_wf_enable_delivery_note === 'Yes') { ?>
                            jQuery('<option>').val('print_delivery_note').text("<?php _e('Print Delivery Note', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action']");

                            jQuery('<option>').val('print_delivery_note').text("<?php _e('Print Delivery Note', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action2']");<?php } ?><?php if ($this->woocommerce_wf_enable_delivery_note === 'Yes') { ?>
                            jQuery('<option>').val('print_dispatch_label').text("<?php _e('Print Dispatch Labels', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action']");
                            jQuery('<option>').val('print_dispatch_label').text("<?php _e('Print Dispatch Labels', 'wf-woocommerce-packing-list') ?>").appendTo("select[name='action2']");<?php } ?>
                    });
                </script><?php
            }
        }

        // Function to bulk action
        public function wf_packinglist_order_bulk_action() {

            $wp_list_table = _get_list_table('WP_Posts_List_Table');
            $action = $wp_list_table->current_action();
            if (in_array($action, $this->document_actions)) {
                $posts = '';
                if ($action != '') {
                    foreach ($_REQUEST['post'] as $post_id) {
                        if (empty($posts)) {
                            $posts = $post_id;
                        } else {
                            $posts .= ',' . $post_id;
                        }
                    }
                    $forward = wp_nonce_url(admin_url(), 'print-packinglist');
                    $forward = add_query_arg(array(
                        'print_packinglist' => 'true',
                        'post' => $posts,
                        'type' => $action
                            ), $forward);
                    wp_redirect($forward);
                    exit();
                }
            }
        }

        // Function to validate the length of the settings options
        public function wf_packinglist_settings_data_validate() {

            if (!empty($_POST)) {

                $current_tab = (!empty($_GET['tab'])) ? ($_GET['tab']) : '';
                switch ($current_tab) {
                    case "packing_slip" :
                        $this->packinglist->validate_settings();
                        break;
                    case "delivery_note" :
                        $this->deliverynote->validate_settings();
                        break;
                    case "shipping_label" :
                        $this->shippinglabel->validate_settings();
                        break;
                    case "dispatch_label" :
                        $this->dispatchlabel->validate_settings();
                        break;
                    case "general" :
                        $this->validate_general_settings();
                        break;
                    case "invoice":
                    default :
                        $this->invoice->validate_settings();
                        break;
                }
            }
        }

        // Function to check wheter the user has added shipping from address
        function wf_packinglist_check_from_address() {
            if (!(get_option('woocommerce_wf_packinglist_sender_name') != '' && get_option('woocommerce_wf_packinglist_sender_address_line1') != '' && get_option('woocommerce_wf_packinglist_sender_city') != '' && get_option('woocommerce_wf_packinglist_sender_country') != '' && get_option('woocommerce_wf_packinglist_sender_postalcode') != '')) {
                return true;
            } else {
                return false;
            }
        }

        // Function to determine the packaging type
        public function wf_pklist_create_order_package($order) {

            switch ($this->wf_package_type) {
                case 'box_packing':
                    return $this->wf_pklist_create_order_box_shipping_package($order);
                    break;
                case 'weight_based_packing':
                    return $this->wf_pklist_create_order_weight_package($order);
                    break;
                case 'pack_items_individually':
                    return $this->wf_pklist_create_order_indvidual_item_package($order);
                    break;
                default:
                    return $this->wf_pklist_create_order_single_package($order);
                    break;
            }
        }
        public function wf_pklist_create_order_weight_package($order) {
            
        }
        public function validate_box_packing_field($value) {
            
            $new_boxes = array();
            foreach ($value as $key => $value) {
                if ($value['length'] != '') {
                    $value['enabled'] = isset($value['enabled']) ? true : false;
                    $new_boxes[] = $value;
                }
            }
            return $new_boxes;
        }


        /*
         * Admin Page for general settings.
         */

        public function render_general_settings() {
            ?>  
            <div id="general_tab" class="tab-pane fade in active"><?php include('includes/settings/generic_settings.php'); ?>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wf-woocommerce-packing-list'); ?>" />
                </p>
            </div><?php
        }
        

        /**
         * Validate submission of settings page for general.
         */
        public function validate_general_settings() {

            if (isset($_POST['woocommerce_wf_packinglist_companyname']) and strlen($_POST['woocommerce_wf_packinglist_companyname']) > 25) {
                $_POST['woocommerce_wf_packinglist_companyname'] = $_POST['woocommerce_wf_packinglist_companyname'];
            }
            if (isset($_POST['woocommerce_wf_packinglist_return_policy']) and strlen($_POST['woocommerce_wf_packinglist_return_policy']) > 75) {
                $_POST['woocommerce_wf_packinglist_return_policy'] = $_POST['woocommerce_wf_packinglist_return_policy'];
            }
            if (isset($_POST['woocommerce_wf_packinglist_footer']) and strlen($_POST['woocommerce_wf_packinglist_footer']) > 75) {
                $_POST['woocommerce_wf_packinglist_footer'] = $_POST['woocommerce_wf_packinglist_footer'];
            }
            if (isset($_POST['woocommerce_wf_packinglist_sender_name']) and strlen($_POST['woocommerce_wf_packinglist_sender_name']) > 25) {
                $_POST['woocommerce_wf_packinglist_sender_name'] = $_POST['woocommerce_wf_packinglist_sender_name'];
            }
            if (isset($_POST['woocommerce_wf_packinglist_sender_address_line1']) and strlen($_POST['woocommerce_wf_packinglist_sender_address_line1']) > 25) {
                $_POST['woocommerce_wf_packinglist_sender_address_line1'] = $_POST['woocommerce_wf_packinglist_sender_address_line1'];
            }
            if (!isset($_POST['woocommerce_wf_packinglist_rtl_settings_enable'])) {
                $_POST['woocommerce_wf_packinglist_rtl_settings_enable'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_currency_support'])) {
                $_POST['woocommerce_wf_currency_support'] = 'no';
            }
            
            if (isset($_POST['woocommerce_wf_packinglist_sender_address_line2']) and strlen($_POST['woocommerce_wf_packinglist_sender_address_line2']) > 25) {
                $_POST['woocommerce_wf_packinglist_sender_address_line2'] = $_POST['woocommerce_wf_packinglist_sender_address_line2'];
            }
            if (!isset($_POST['wf_view_checkbox_data_general'])) {
                $_POST['wf_view_checkbox_data_general'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_packinglist_datamatrix_information'])) {
                $_POST['woocommerce_wf_packinglist_datamatrix_information'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_packinglist_variation_data'])) {
                $_POST['woocommerce_wf_packinglist_variation_data'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_packinglist_add_sku'])) {
                $_POST['woocommerce_wf_packinglist_add_sku'] = 'no';
            }
            if (!isset($_POST['woocommerce_wf_state_code_disable'])) {
                $_POST['woocommerce_wf_state_code_disable'] = 'no';
            }
        }

    }

    new Wf_WooCommerce_Packing_List();
}
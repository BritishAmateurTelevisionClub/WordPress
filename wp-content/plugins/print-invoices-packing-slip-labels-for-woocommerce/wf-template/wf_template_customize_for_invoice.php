<?php
$current_loaded_invoice_theme = '';
$invoice_data = '';
if (isset($_GET['theme']) && !empty($_GET['theme'])) {
    $customize_data = get_option($_GET['theme']);
    $main_data_value = eh_theme_value_assign($_GET['theme']);
    $invoice_data = $_GET['theme'];
    $current_loaded_invoice_theme = $_GET['theme'];
} else {
    if (get_option('wf_invoice_active_key')) {
        $customize_data = get_option(get_option('wf_invoice_active_key')); //active 
        $main_data_value = eh_theme_value_assign(get_option('wf_invoice_active_key'));
        $invoice_data = get_option('wf_invoice_active_key');
        $current_loaded_invoice_theme = get_option('wf_invoice_active_key');
    } else {
        $customize_data = '<h3>Choose Template</h3>'; //active template want to customize
        $main_data_value = '0|0|0|';
    }
}
$acive_template = get_option('wf_invoice_active_key');
$default_active_value = get_option('wf_invoice_active_value');
$default_active_array = explode('|', $default_active_value);
$main_data_array = explode('|', $main_data_value);
$main_data_array[94] = isset($main_data_array[94]) ? $main_data_array[94] : 'no';
if (isset($_POST['logo_save'])) {
    $main_data_array[0] = is_numeric($_POST['logowidth']) ? $_POST['logowidth'] : $default_active_array[0];
    $main_data_array[1] = is_numeric($_POST['logoheight']) ? $_POST['logoheight'] : $default_active_array[1];
    $main_data_array[2] = isset($_POST['wf_company_logo_switch']) ? 'yes' : 'no';
    $main_data_array[3] = $_POST['company_logo_or_text'];
    $main_data_array[4] = isset($_POST['wf_invoice_number_switch']) ? 'yes' : 'no';
    $main_data_array[94] = isset($_POST['wf_order_number_switch']) ? 'yes' : 'no';
    $main_data_array[5] = is_numeric($_POST['wf_invoice_font']) ? $_POST['wf_invoice_font'] : $default_active_array[5];
    $main_data_array[6] = $_POST['wf_invoice_number_font_weight'];
    $main_data_array[7] = !empty($_POST['wf_invoice_number_text']) ? $_POST['wf_invoice_number_text'] : $default_active_array[7];
    $main_data_array[8] = isset($_POST['wf_invoice_number_color_code_default']) ? $default_active_array[8] : $_POST['wf_invoice_number_color_code'];
    $main_data_array[9] = isset($_POST['wf_invoice_date_switch']) ? 'yes' : 'no';
    $main_data_array[10] = !empty($_POST['wf_invoice_date_format']) ? $_POST['wf_invoice_date_format'] : $default_active_array[10];
    $main_data_array[11] = is_numeric($_POST['wf_invoice_date_font']) ? $_POST['wf_invoice_date_font'] : $default_active_array[11];
    $main_data_array[12] = !empty($_POST['wf_invoice_date_text']) ? stripslashes($_POST['wf_invoice_date_text']) : $default_active_array[12];
    $main_data_array[13] = $_POST['wf_invoice_date_font_weight'];
    $main_data_array[14] = isset($_POST['wf_invoice_date_color_code_default']) ? $default_active_array[14] : $_POST['wf_invoice_date_color'];
    $main_data_array[15] = isset($_POST['wf_order_date_switch']) ? 'yes' : 'no';
    $main_data_array[16] = !empty($_POST['wf_order_date_format']) ? $_POST['wf_order_date_format'] : $default_active_array[16];
    $main_data_array[17] = is_numeric($_POST['wf_order_date_font']) ? $_POST['wf_order_date_font'] : $default_active_array[17];
    $main_data_array[18] = !empty($_POST['wf_order_date_text']) ? $_POST['wf_order_date_text'] : $default_active_array[18];
    $main_data_array[19] = $_POST['wf_order_date_font_weight'];
    $main_data_array[20] = isset($_POST['wf_order_date_color_code_default']) ? $default_active_array[20] : $_POST['wf_order_date_color'];
    $main_data_array[21] = isset($_POST['wf_from_address_switch']) ? 'yes' : 'no';
    $main_data_array[22] = !empty($_POST['wf_from_address_title']) ? $_POST['wf_from_address_title'] : $default_active_array[22];
    $main_data_array[23] = $_POST['wf_from_address_text_align'];
    $main_data_array[24] = isset($_POST['wf_from_address_color_code_default']) ? $default_active_array[24] : $_POST['wf_from_address_color_code'];
    $main_data_array[25] = isset($_POST['wf_billing_address_switch']) ? 'yes' : 'no';
    $main_data_array[26] = !empty($_POST['wf_billing_address_title']) ? $_POST['wf_billing_address_title'] : $default_active_array[26];
    $main_data_array[27] = $_POST['wf_billing_address_text_align'];
    $main_data_array[28] = isset($_POST['wf_billing_address_color_code_default']) ? $default_active_array[28] : $_POST['wf_billing_address_color_code'];
    $main_data_array[29] = isset($_POST['wf_shipping_address_switch']) ? 'yes' : 'no';
    $main_data_array[30] = !empty($_POST['wf_shipping_address_title']) ? $_POST['wf_shipping_address_title'] : $default_active_array[30];
    $main_data_array[31] = $_POST['wf_shipping_address_text_align'];
    $main_data_array[32] = isset($_POST['wf_shipping_address_color_code_default']) ? $default_active_array[32] : $_POST['wf_shipping_address_color_code'];
    
    $main_data_array[53] = isset($_POST['wf_tp_switch']) ? 'yes' : 'no';
    $main_data_array[54] = is_numeric($_POST['tp_font']) ? $_POST['tp_font'] : $default_active_array[54];
    $main_data_array[55] = !empty($_POST['tp_text']) ? stripslashes($_POST['tp_text']) : $default_active_array[55];
    $main_data_array[56] = $_POST['wf_tp_text_align'];
    $main_data_array[57] = isset($_POST['wf_tp_color_code_default']) ? $default_active_array[57] : $_POST['wf_tp_color_code'];

    $main_data_array[58] = isset($_POST['wf_tn_switch']) ? 'yes' : 'no';
    $main_data_array[59] = is_numeric($_POST['tn_font']) ? $_POST['tn_font'] : $default_active_array[59];
    $main_data_array[60] = !empty($_POST['tn_text']) ? stripslashes($_POST['tn_text']) : $default_active_array[60];
    $main_data_array[61] = $_POST['wf_tn_text_align'];
    $main_data_array[62] = isset($_POST['wf_tn_color_code_default']) ? $default_active_array[62] : $_POST['wf_tn_color_code'];

    $main_data_array[63] = isset($_POST['wf_product_switch']) ? 'yes' : 'no';
    $main_data_array[64] = isset($_POST['wf_head_back_color_code_default']) ? $default_active_array[64] : $_POST['wf_head_back_code'];
    $main_data_array[65] = isset($_POST['wf_head_front_color_code_default']) ? $default_active_array[65] : $_POST['wf_head_front_code'];
    $main_data_array[66] = $_POST['wf_get_text_align_head'];
    $main_data_array[67] = isset($_POST['wf_body_front_color_code_default']) ? $default_active_array[67] : $_POST['wf_body_front_code'];
    $main_data_array[68] = $_POST['wf_get_text_align_body'];
    $main_data_array[69] = !empty($_POST['sku_text']) ? stripslashes($_POST['sku_text']) : $default_active_array[69];
    $main_data_array[70] = !empty($_POST['product_text']) ? stripslashes($_POST['product_text']) : $default_active_array[70];
    $main_data_array[71] = !empty($_POST['qty_text']) ? stripslashes($_POST['qty_text']) : $default_active_array[71];
    $main_data_array[72] = !empty($_POST['total_text']) ? stripslashes($_POST['total_text']) : $default_active_array[72];

    $main_data_array[73] = !empty($_POST['wf_subtotal_text']) ? stripslashes($_POST['wf_subtotal_text']) : $default_active_array[73];
    $main_data_array[74] = !empty($_POST['wf_shipping_text']) ? stripslashes($_POST['wf_shipping_text']) : $default_active_array[74];
    $main_data_array[75] = !empty($_POST['wf_cd_text']) ? stripslashes($_POST['wf_cd_text']) : $default_active_array[75];
    $main_data_array[76] = !empty($_POST['wf_od_text']) ? stripslashes($_POST['wf_od_text']) : $default_active_array[76];
    $main_data_array[77] = !empty($_POST['wf_tt_text']) ? stripslashes($_POST['wf_tt_text']) : $default_active_array[77];
    $main_data_array[78] = !empty($_POST['wf_total_text']) ? stripslashes($_POST['wf_total_text']) : $default_active_array[78];
    $main_data_array[79] = !empty($_POST['wf_paym_text']) ? stripslashes($_POST['wf_paym_text']) : $default_active_array[79];
    $main_data_array[80] = !empty($_POST['logo_extra_details']) ? stripslashes(str_replace('|', '-*-', $_POST['logo_extra_details'])) : $default_active_array[80];
    $main_data_array[81] = is_numeric($_POST['logo_extra_details_font']) ? $_POST['logo_extra_details_font'] : $default_active_array[81];

    $main_data_array[82] = isset($_POST['wf_sign_switch']) ? 'yes' : 'no';
    $main_data_array[83] = (isset($_POST['sign_font'])&& is_numeric($_POST['sign_font'])) ? $_POST['sign_font'] : '14';
    $main_data_array[84] = !empty($_POST['sign_text']) ? stripslashes($_POST['sign_text']) : 'Signature';
    $main_data_array[85] = isset($_POST['wf_sign_text_align']) ? $_POST['wf_sign_text_align'] : 'left';
    $main_data_array[86] = isset($_POST['wf_sign_img_width']) ? $_POST['wf_sign_img_width'] : 'auto';
    $main_data_array[87] = isset($_POST['wf_sign_img_height']) ? $_POST['wf_sign_img_height'] : '60';
    
    $main_data_array[89] = is_numeric($_POST['wf_order_number_font']) ? $_POST['wf_order_number_font'] : $default_active_array[89];
    $main_data_array[90] = !empty($_POST['wf_order_number_text']) ? $_POST['wf_order_number_text'] : $default_active_array[90];
    $main_data_array[91] = isset($_POST['wf_order_number_font_weight']) ? $_POST['wf_order_number_font_weight'] : $default_active_array[91];
    $main_data_array[92] = isset($_POST['wf_order_number_color_code_default']) ? $default_active_array[92] : $_POST['wf_order_number_color_code'];
    $main_data_array[92] = isset($_POST['wf_order_number_color_code_default']) ? $default_active_array[92] : $_POST['wf_order_number_color_code'];
    $main_data_array[93] = !empty($_POST['wf_fee_text']) ? stripslashes($_POST['wf_fee_text']) : $default_active_array[93];
    
    
    $my_main_data = implode('|', $main_data_array);
    eh_data_save_customize($acive_template, $my_main_data);
}


function eh_data_save_customize($acive_template, $my_main_data) {
    $template_name = !empty($_POST['custom_invoice_nameholder']) ? sanitize_text_field($_POST['custom_invoice_nameholder']) : '';
    $i = 1;
    if (isset($_GET['theme']) && !empty($_GET['theme'])) {
        if (get_option($_GET['theme'] . 'custom') === 'yes') {
            update_option($_GET['theme'] . 'value', $my_main_data);
            update_option('wf_invoice_active_key', $_GET['theme']);
            update_option($_GET['theme']. 'name', $template_name);
        } else {

            for ($f = 1; get_option('wf_invoice_template_' . $f) != ''; $f++) {
                $i +=1;
            }
            update_option('wf_invoice_template_' . $i, get_option($_GET['theme']));
            update_option('wf_invoice_template_' . $i . 'value', $my_main_data);
            update_option('wf_invoice_template_' . $i . 'custom', 'yes');
            update_option('wf_invoice_template_' . $i . 'from', $_GET['theme']);
            update_option('wf_invoice_active_key', 'wf_invoice_template_' . $i);
            update_option('wf_invoice_template_' . $i . 'name', $template_name);
            wp_redirect(admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_' . $i));
        }
    } else {
        if (get_option($acive_template . 'custom') === 'yes') {
            update_option($acive_template . 'value', $my_main_data);
            update_option('wf_invoice_active_key', $acive_template);
            update_option($acive_template. 'name', $template_name);
        } else {
            for ($f = 1; get_option('wf_invoice_template_' . $f) != ''; $f++) {
                $i +=1;
            }
            update_option('wf_invoice_template_' . $i, get_option($acive_template));
            update_option('wf_invoice_template_' . $i . 'value', $my_main_data);
            update_option('wf_invoice_template_' . $i . 'custom', 'yes');
            update_option('wf_invoice_template_' . $i . 'from', $acive_template);
            update_option('wf_invoice_active_key', 'wf_invoice_template_' . $i);
            update_option('wf_invoice_template_' . $i . 'name', $template_name);
            wp_redirect(admin_url('admin.php?page=wf_template_customize_for_invoice&theme=wf_invoice_template_' . $i));
        }
    }
}

function eh_theme_value_assign($given_template) {
    if (get_option($given_template . 'value') === false) {
        return get_option('wf_invoice_active_value');
    } else {
        return get_option($given_template . 'value');
    }
}
?>

<style type="text/css" >

    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 21px;
    }
    .clickable
    {
        cursor: pointer;
    }
    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 21px;
        width: 21px;
        right:12px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #0085ba;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #0085ba;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(12px);
        -ms-transform: translateX(12px);
        transform: translateX(12px);
    }
    .panel-body{
        background: #F2F5F7;
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 50px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .tooltips {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltips .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        top: 100%;
        left: 50%;
        margin-left: -60px;
    }

    .tooltips:hover .tooltiptext {
        visibility: visible;
    }


</style>
<script>
    $(document).ready(function () {
        $(document).on('mouseenter', '.x_content', function () {
            $(this).find(":button").show();
        }).on('mouseleave', '.x_content', function () {
            $(this).find(":button").hide();
        });

    });
    
  function askForCustomName() {
    
    if(document.getElementById("custom_invoice_nameholder").value != ''){
        return true;
    }
    var custom_template_name = prompt("Please enter name of custom template", "My Custom Invoice");
    if (custom_template_name != null) {
        document.getElementById("custom_invoice_nameholder").value =  custom_template_name;
    }
    }
</script>

<ul class="subsubsub">
    <li><a style="color: #0073aa;" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list'); ?>" class=""><?php _e('Settings', 'wf-woocommerce-packing-list'); ?></a> | </li>
    <li><a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=').get_option('wf_invoice_active_key'); ?>" class="current"><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></a></li>
</ul>


<form method="post" action="">
    <div class="container bodyclass">
        <div class="main_container">

            <!-- page content -->
            <div class="right_col" role="main">
                <div class=""><br>

                    <div class="clearfix"></div>

                    <div class="">

                        <div class="col-md-8 col-sm-8 col-xs-12" style="background: white;padding-top: 10px;">
                            <div class="x_panel">
                                <div class="x_content" >
                                    <div class="accordion"  style="min-height: 28cm;" id="my_new_invoice" >
                                        <style type="text/css">
                                            @import url('https://fonts.googleapis.com/css?family=Oxygen:300,400,700');

                                            *{ margin:0; padding:0; outline:none; box-sizing:border-box;}

                                            ul,ol, li{ list-style:none;}
                                            h1,h2,h3,h4,h5,h6{ font-weight:normal;}
                                            a{ text-decoration:none; outline:none;}
                                            img{ border:0px; outline:none; display:block;}

                                            .left{float:left !important;}
                                            .right{float:right !important;}
                                            .clr{ clear:both;}
                                            .wf_invoice_label { font-size:13px !important;}
                                            .wf_invoice_date_label { font-size:13px !important;}
                                            .wf_order_date_label { font-size:13px !important;}
                                            .Invoice .wrapper{ width:100%; margin:0 auto;}
                                            .Invoice header, .Invoice footer{ float:left; width:100%;}
                                            .Invoice header{ padding-top:10px; padding-bottom:10px;}
                                            .full-row{ clear:both; width:100%;}
                                            .Invoice header .left, .Invoice header .right{ float:left; width:50%; text-align:left;}
                                            .logo{ padding-bottom:10px;}
                                            .Invoice header .right{ width:100%;}
                                            .Invoice header .left p{ float:left; width:100%; text-align:left; font-size:20px; line-height:30px; color:#252525;}
                                            .bar-code{ float:right; display:block; text-align:right; padding-bottom:10px;}
                                            .Invoice header .right table{  float:right; clear:both;}
                                            .Invoice header .right table td{ font-size:18px; color:#242424;}
                                            .Invoice header, .Invoice footer {margin-left:0px !important;}
                                            .Invoice section{ float:left; width:100%; padding-bottom:20px;}
                                            .address-full{ float:left; width:100%;}
                                            .address-full .halfwidth{ float:left; width:35%; padding-right:10px;}
                                            .address-full .halfwidth h3{ /*color:#0d99ce;*/ text-transform:uppercase; font-weight:700; font-size:18px; padding-bottom:5px;}
                                            .address-full .halfwidth p{ font-size:18px; line-height:25px; /*color:#363636;*/}

                                            .moneyback{ float:right; background:#F0F8F6; width:30%; padding:10px;}
                                            .moneyback p{ float:left; width:100%; font-size:18px; color:#000; text-align:center; line-height:40px;}
                                            .moneyback p span{ color:#00aeef; font-size:40px; text-align:center;}

                                            .product-summary { float:left; width:100%; padding:30px 0 0 0;}
                                            .product-summary table{ width:100%; border:none !important;}
                                            .product-summary table th{ text-align:left; background:#0D99CE; color:#fff; padding-left:20px; padding-top:20px; padding-bottom:20px; font-size:20px; font-weight:400;}
                                            .product-summary table td{ padding-left:20px; padding-top:20px; padding-bottom:20px; color:#363636;}
                                            .product-summary table tr{ background-color:#fff;}
                                            .product-summary table tr:nth-child(even) {background-color: #F9F9F9;}
                                            .product-summary table tr:last-child{ border-bottom:#CCCCCC 1px solid !important;}

                                            .payment-summary{ float:left; width:100%; border-top:#ccc 1px solid; padding-top:35px;}
                                            .payment-summary .left{ float:left; width:50%;}
                                            .payment-summary .left h4{ font-size:25px; line-height:30px; color:#363636; font-weight:700; width:100%; float:left;}
                                            .payment-summary .left p{ font-size:20px; line-height:30px; color:#888888; font-weight:400; width:100%; float:left;}
                                            .payment-summary .right{ float:right; width:50%;}
                                            .payment-summary .right table{ float:right !important;}
                                            .payment-summary .right table td{ font-size:18px; line-height:30px;color:black;}
                                            .new_amount{ float:left; width:100%; background:#0D99CE; color:#FFF; font-size:18px; padding:20px; text-align:center;}
                                            .payment-summary .right p.note{ float:left; width:100%; color:#888; font-size:16px; padding-top:15px; padding-bottom:15px; text-align:center;}
                                            .terms{ float:left; width:100%;}
                                            .terms h3{ float:left; width:100%; text-align:left; font-size:20px; font-weight:400; padding-bottom:10px;}
                                            .terms ul{ float:left; width:100%; margin:0; padding:0; padding-left:20px;}
                                            .terms li{ float:left; width:100%; list-style:disc !important; color:#888; font-size:16px; line-height:30px;}

                                            .Invoice footer p{ float:left; width:100%; border-top:#E5E5E5 1px solid; line-height:90px; text-align:center; color:#888;}
                                        </style><?php
$customize_data = str_replace("[invoice main height and width]", 'height:100%; width:100%;', $customize_data);
$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/font-awesome/css/font-awesome.css' rel='stylesheet'>", '', $customize_data);
$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/css/custom.min.css' rel='stylesheet'>", '', $customize_data);
$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/dist/css/bootstrap.css' rel='stylesheet'>", '', $customize_data);

$customize_data = str_replace('[company name]', $this->wf_packinglist_get_companyname() ? $this->wf_packinglist_get_companyname() : 'Company Name', $customize_data);

$customize_data = str_replace('[company1 name]', '', $customize_data);

//------------------------------
//------------------------------

if ($main_data_array[2] === 'no') {
    $customize_data = str_replace('[company logo visible]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[company logo visible]', '', $customize_data);
}
$customize_data = str_replace('[wffootor style]', 'bottom: 1px; ', $customize_data);
if ($main_data_array[3] === 'logo') {
    if ($this->wf_packinglist_get_logo('print_invoice') != '') {

        $customize_data = str_replace('[image url for company logo]', $this->wf_packinglist_get_logo('print_invoice'), $customize_data);
    } else {
        $customize_data = str_replace('[image url for company logo]', WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/logo.png', $customize_data);
    }
    $customize_data = str_replace('[company text show hide]', 'display:none;', $customize_data);
    $customize_data = str_replace('[logo width]', $main_data_array[0], $customize_data);
    $customize_data = str_replace('[logo height]', $main_data_array[1], $customize_data);
} else {
    $customize_data = str_replace('[image url for company logo]', '', $customize_data);
    $customize_data = str_replace('[company text show hide]', '', $customize_data);
    $customize_data = str_replace('[logo width]', '', $customize_data);
    $customize_data = str_replace('[logo height]', '', $customize_data);
}

if ($main_data_array[4] === 'no') {
    $customize_data = str_replace('[invoice number switch]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[invoice number switch]', '', $customize_data);
}
if ($main_data_array[94] === 'no') {
    $customize_data = str_replace('[order number switch]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[order number switch]', '', $customize_data);
}
if ($main_data_array[8] != 'default') {
    $customize_data = str_replace('[invoice_number_color]', 'color:#' . $main_data_array[8] . ';', $customize_data);
} else {
    $customize_data = str_replace('[invoice_number_color]', '', $customize_data);
}
$customize_data = str_replace('[invoice number prob]', 'font-size:' . $main_data_array[5] . 'px;', $customize_data);
$customize_data = str_replace('[invoice font weight]', 'font-weight:' . $main_data_array[6] . ';', $customize_data);


if ($main_data_array[92] != 'default') {
    $customize_data = str_replace('[order_number_color]', 'color:#' . $main_data_array[92] . ';', $customize_data);
} else {
    $customize_data = str_replace('[order_number_color]', '', $customize_data);
}
$customize_data = str_replace('[order number prob]', 'font-size:' . $main_data_array[89] . 'px;', $customize_data);
$customize_data = str_replace('[order font weight]', 'font-weight:' . $main_data_array[91] . ';', $customize_data);


if ($main_data_array[9] === 'no') {
    $customize_data = str_replace('[invoice date show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[invoice date show hide]', '', $customize_data);
}
$customize_data = str_replace('[invoice date font size]', 'font-size:' . $main_data_array[11] . 'px;', $customize_data);
$customize_data = str_replace('[invoice Date label text]', $main_data_array[12], $customize_data);
$customize_data = str_replace('[invoice date label font weight]', 'font-weight:' . $main_data_array[13] . ';', $customize_data);

if ($main_data_array[14] != 'default') {
    $customize_data = str_replace('[invoice date color code]', 'color:#' . $main_data_array[14] . ';', $customize_data);
} else {
    $customize_data = str_replace('[invoice date color code]', '', $customize_data);
}
if ($main_data_array[15] === 'no') {
    $customize_data = str_replace('[order date show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[order date show hide]', '', $customize_data);
}
$customize_data = str_replace('[invoice return policy hide]', 'display:none;', $customize_data);
$customize_data = str_replace('[payment method show hide]', '', $customize_data);
$customize_data = str_replace('[invoice head font size]', '16', $customize_data);
$customize_data = str_replace('[invoice name]', $main_data_array[7], $customize_data);
$customize_data = str_replace('[invoice number]', '123456', $customize_data);
$customize_data = str_replace('[order number label]', __('Order No', 'wf-woocommerce-packing-list'), $customize_data);
$customize_data = str_replace('[order number]', '123456', $customize_data);
$customize_data = str_replace('[order date title size]', '16', $customize_data);
$customize_data = str_replace('[invoice created date]', date($main_data_array[10], strtotime('now')), $customize_data);
$customize_data = str_replace('[order date label]', $main_data_array[18], $customize_data);
$customize_data = str_replace('[order date]', date($main_data_array[16], strtotime('now')), $customize_data);
$customize_data = str_replace('[order date font size]', 'font-size:' . $main_data_array[17] . 'px;', $customize_data);

$customize_data = str_replace('[order date label font weight]', 'font-weight:' . $main_data_array[19] . ';', $customize_data);
if ($main_data_array[20] != 'default') {
    $customize_data = str_replace('[order date color code]', 'color:#' . $main_data_array[20] . ';', $customize_data);
} else {
    $customize_data = str_replace('[order date color code]', '', $customize_data);
}

if ($main_data_array[21] === 'no') {
    $customize_data = str_replace('[from address show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[from address show hide]', '', $customize_data);
}
$customize_data = str_replace('[from address label]', $main_data_array[22], $customize_data);

$customize_data = str_replace('[from address left right]', 'text-align:' . $main_data_array[23] . ';', $customize_data);

if ($main_data_array[24] != 'default') {
    $customize_data = str_replace('[from address text color]', 'color:#' . $main_data_array[24] . ';', $customize_data);
} else {
    $customize_data = str_replace('[from address text color]', '', $customize_data);
}

$customize_data = str_replace('[from address font size]', '14', $customize_data);
$customize_data = str_replace('[from address]', 'Name<br>Company name<br>Address1<br>Address2<br>State<br>Country', $customize_data);
$customize_data = str_replace('[billing address title size]', '14', $customize_data);
$customize_data = str_replace('[billing address label]', $main_data_array[26], $customize_data);

if ($main_data_array[25] === 'no') {
    $customize_data = str_replace('[billing address show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[billing address show hide]', '', $customize_data);
}

$customize_data = str_replace('[billing address left right]', 'text-align:' . $main_data_array[27] . ';', $customize_data);

if ($main_data_array[28] != 'default') {
    $customize_data = str_replace('[billing address text color]', 'color:#' . $main_data_array[28] . ';', $customize_data);
} else {
    $customize_data = str_replace('[billing address text color]', '', $customize_data);
}




$customize_data = str_replace('[billing address font size]', '14', $customize_data);
$customize_data = str_replace('[billing address data]', 'Name<br>Company name<br>Address1<br>Address2<br>State<br>Country<br>', $customize_data);

$customize_data = str_replace('[email label]', $main_data_array[35], $customize_data);
$customize_data = str_replace('[email address]', 'info@invoice.com', $customize_data);


    $customize_data = str_replace('[wf email show hide]', 'display:none;', $customize_data);


$customize_data = str_replace('[mobile label]', $main_data_array[40], $customize_data);
$customize_data = str_replace('[mobile number]', '+123 4567890', $customize_data);


    $customize_data = str_replace('[wf tel show hide]', 'display:none;', $customize_data);



$customize_data = str_replace('[VAT label]', $main_data_array[45], $customize_data);
$customize_data = str_replace('[VAT data]', '4544123', $customize_data);
$customize_data = str_replace('[SSN label]', $main_data_array[50], $customize_data);
$customize_data = str_replace('[SSN data]', 'SSN54542S', $customize_data);




    $customize_data = str_replace('[wf vat show hide]', 'display:none;', $customize_data);


$customize_data = str_replace('[invoice extra field font size]', 'font-size:' . $main_data_array[81] . 'px;', $customize_data);

if ($main_data_array[80] != 'none') {
    $customize_data = str_replace('[Extra data below logo]', str_replace('-*-', '|', $main_data_array[80]), $customize_data);
    $customize_data = str_replace('[wf extra filed show hide]', '', $customize_data);
} else {
    $customize_data = str_replace('[wf extra filed show hide]', '', $customize_data);

    $customize_data = str_replace('[Extra data below logo]', '', $customize_data);
}


    $customize_data = str_replace('[wf ssn show hide]', 'display:none;', $customize_data);


$customize_data = str_replace('[shipping address title size]', '16', $customize_data);

if ($main_data_array[29] === 'no') {
    $customize_data = str_replace('[shipping address show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[shipping address show hide]', '', $customize_data);
}

$customize_data = str_replace('[shipping address left right]', 'text-align:' . $main_data_array[31] . ';', $customize_data);

if ($main_data_array[32] != 'default') {
    $customize_data = str_replace('[shipping address text color]', 'color:#' . $main_data_array[32] . ';', $customize_data);
} else {
    $customize_data = str_replace('[shipping address text color]', '', $customize_data);
}
if (empty($main_data_array[82])) {
    $main_data_array[82] = 'yes';
}
if (empty($main_data_array[83])) {
    $main_data_array[83] = '14';
}
if (isset($main_data_array[84])) {
    $main_data_array[84] = 'Signature';
}
if (empty($main_data_array[85])) {
    $main_data_array[85] = 'left';
}
if (empty($main_data_array[86])) {
    $main_data_array[86] = 'auto';
}
if (empty($main_data_array[87])) {
    $main_data_array[87] = '60';
}

$customize_data = str_replace('[shipping address title]', $main_data_array[30], $customize_data);
$customize_data = str_replace('[shipping address content size]', '14', $customize_data);
$customize_data = str_replace('[shipping address data]', 'Name<br>Company name<br>Address1<br>Address2<br>State<br>Country<br>', $customize_data);
$customize_data = str_replace('[tracking label]', $main_data_array[55], $customize_data);
$customize_data = str_replace('[tracking data]', 'DHL Express', $customize_data);
$customize_data = str_replace('[tracking number label]', $main_data_array[60], $customize_data);
$customize_data = str_replace('[tracking number data]', '2786382178322', $customize_data);

$xa_wf_sign = get_option('woocommerce_wf_select_sign');
if (!empty($xa_wf_sign) && $xa_wf_sign == 'sign_yes' && $main_data_array[82] == 'yes') {
    $customize_data = str_replace('[Signature label]', $main_data_array[84], $customize_data);
    $customize_data = str_replace('signature_text_option_style_data', 'font-size:' . $main_data_array[83] . 'px', $customize_data);
    $customize_data = str_replace('[sign align]', 'text-align:' . $main_data_array[85], $customize_data);
} else {

    $customize_data = str_replace('[Signature label]', $main_data_array[84], $customize_data);
    $customize_data = str_replace('[sign align]', 'text-align:' . $main_data_array[85], $customize_data);
    $customize_data = str_replace('signature_text_option_style_data', 'display:none', $customize_data);
}
$xa_chk_image = $this->wf_packinglist_get_signature();
if (!empty($xa_chk_image) && $main_data_array[82] == 'yes') {
    $customize_data = str_replace('[image url for signature]', $this->wf_packinglist_get_signature(), $customize_data);
    $customize_data = str_replace('[image width]', $main_data_array[86], $customize_data);
    $customize_data = str_replace('[image height]', $main_data_array[87], $customize_data);
    $customize_data = str_replace('signature_img_option_style_data', 'float:' . $main_data_array[85], $customize_data);
} else {
    $customize_data = str_replace('signature_img_option_style_data', 'display:none; ', $customize_data);
    $customize_data = str_replace('[image url for signature]', $this->wf_packinglist_get_signature(), $customize_data);
    $customize_data = str_replace('[image width]', $main_data_array[86], $customize_data);
    $customize_data = str_replace('[image height]', $main_data_array[87], $customize_data);
}

if ($main_data_array[53] === 'no') {
    $customize_data = str_replace('[wf tp show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[wf tp show hide]', '', $customize_data);
    $customize_data = str_replace('[wf tp font size]', 'font-size:' . $main_data_array[54] . 'px;', $customize_data);

    $customize_data = str_replace('[wf tp position set]', 'text-align:' . $main_data_array[56] . ';', $customize_data);


    if ($main_data_array[57] != 'default') {
        $customize_data = str_replace('[wf_tp color code default]', 'color:#' . $main_data_array[57] . ';', $customize_data);
    } else {
        $customize_data = str_replace('[wf_tp color code default]', '', $customize_data);
    }
}




if ($main_data_array[58] === 'no') {
    $customize_data = str_replace('[wf tn show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[wf tn show hide]', '', $customize_data);
    $customize_data = str_replace('[wf tn font size]', 'font-size:' . $main_data_array[59] . 'px;', $customize_data);

    $customize_data = str_replace('[wf tn position set]', 'text-align:' . $main_data_array[61] . ';', $customize_data);

    if ($main_data_array[62] != 'default') {
        $customize_data = str_replace('[wf_tn color code default]', 'color:#' . $main_data_array[62] . ';', $customize_data);
    } else {
        $customize_data = str_replace('[wf_tn color code default]', '', $customize_data);
    }
}


if ($main_data_array[63] === 'no') {
    $customize_data = str_replace('[wf product table show hide]', 'display:none;', $customize_data);
} else {
    $customize_data = str_replace('[wf product table show hide]', '', $customize_data);
    if ($main_data_array[64] != 'default') {
        $customize_data = str_replace('[wf product table head color]', 'background:#' . $main_data_array[64] . ';border:' . $main_data_array[64] . ';', $customize_data);
        $customize_data = str_replace('[border-base-theme-color]', $main_data_array[64], $customize_data);
    } else {
        $customize_data = str_replace('[border-base-theme-color]', '66BDA9', $customize_data);
        $customize_data = str_replace('[wf product table head color]', '', $customize_data);
    }

    if ($main_data_array[65] != 'default') {
        $customize_data = str_replace('[wf product table head text color]', 'color:#' . $main_data_array[65] . ';', $customize_data);
    } else {
        $customize_data = str_replace('[wf product table head text color]', '', $customize_data);
    }
    $customize_data = str_replace('[wf product table text align]', 'text-align:' . $main_data_array[66] . ';', $customize_data);
    if ($main_data_array[67] != 'default') {
        $customize_data = str_replace('[wf product table text color main]', 'color:#' . $main_data_array[67] . ';', $customize_data);
    } else {
        $customize_data = str_replace('[wf product table text color main]', '', $customize_data);
    }

    $customize_data = str_replace('[wf product table body text align]', 'text-align:' . $main_data_array[68] . ';', $customize_data);
    $customize_data = str_replace('[product label text]', $main_data_array[70], $customize_data);

    if ($this->wf_pklist_add_sku === 'Yes') {
        $customize_data = str_replace('[sku label text]', $main_data_array[69], $customize_data);
        $customize_data = str_replace('[table colum span]', '1', $customize_data);

        $customize_data = str_replace('[table colum span hide]', '', $customize_data);
        $customize_data = str_replace('[table quantity text]', $main_data_array[71], $customize_data);
        $customize_data = str_replace('[table price text]', $default_active_array[88], $customize_data);
        $customize_data = str_replace('[table toatl price text]', $main_data_array[72], $customize_data);
    } else {
        $customize_data = str_replace('[table colum span]', '2', $customize_data);

        $customize_data = str_replace('[table colum span hide]', 'display:none !important;', $customize_data);
        $customize_data = str_replace('[sku label text]', '', $customize_data);
        $customize_data = str_replace('[table quantity text]', $main_data_array[71], $customize_data);
        $customize_data = str_replace('[table price text]', $default_active_array[88], $customize_data);
        $customize_data = str_replace('[table toatl price text]', $main_data_array[72], $customize_data);
    }
}


$customize_data = str_replace('[table border top color]', $this->wf_packinglist_brand_color, $customize_data);
$customize_data = str_replace('[table background color]', $this->wf_packinglist_brand_color, $customize_data);

$customize_data = str_replace('[table coloum brand color]', $this->wf_packinglist_brand_color, $customize_data);
$customize_data = str_replace('[table tax items]', '', $customize_data);
$customize_data = str_replace('[table tfoot content size]', '12', $customize_data);
$customize_data = str_replace('[table coupon show hide]', 'display:none;', $customize_data);


$customize_data = str_replace('[table subtotal label]', $main_data_array[73], $customize_data);
$customize_data = str_replace('[fee text]', $main_data_array[93], $customize_data);
$customize_data = str_replace('[fee total value]', '$10.10', $customize_data);
$customize_data = str_replace('[table shipping select text]', $main_data_array[74], $customize_data);
$customize_data = str_replace('[table cart discount text]', $main_data_array[75], $customize_data);
$customize_data = str_replace('[table Order discount text]', $main_data_array[76], $customize_data);
$customize_data = str_replace('[table Total Tax text]', $main_data_array[77], $customize_data);
$customize_data = str_replace('[table invoice total label]', $main_data_array[78], $customize_data);
$customize_data = str_replace('[table payment info label]', $main_data_array[79], $customize_data);


$customize_data = str_replace('[table subtotal value]', '$100.10', $customize_data);
$customize_data = str_replace('[table shipping select value]', '$10.00', $customize_data);
$customize_data = str_replace('[table cart discount value]', '-$5.00', $customize_data);
$customize_data = str_replace('[table Order discount value]', '$0.00', $customize_data);
$customize_data = str_replace('[table tax item value]', '$10.00', $customize_data);
$customize_data = str_replace('[table invoice total value]', '$105.10', $customize_data);
$customize_data = str_replace('[table payment info value]', 'PayPal', $customize_data);

if ($this->wf_pklist_add_sku === 'Yes') {
    $customize_data = str_replace('[table tbody content value]', '<tr><td class="qty" style="text-align:unset;">Red_Ball</td><td class="desc" style="text-align:unset;">Jumbing LED Light Wall Ball</td><td class="unit" style="text-align:unset;">5</td><td class="" style="text-align:unset;">$20.00</td><td class="total" style="text-align:unset;">$100.00</td></tr>', $customize_data);
} else {
    $customize_data = str_replace('[table tbody content value]', '<tr><td class="desc" style="text-align:unset;" colspan="2">Jumbing LED Light Wall Ball</td><td class="unit" style="text-align:unset;">5</td><td class="" style="text-align:unset;">$20.00</td><td class="total" style="text-align:unset;">$100.00</td></tr>', $customize_data);
}
$customize_data = str_replace('[invoice barcode data]', '', $customize_data);
$customize_data = str_replace('[invoice return policy data]', '', $customize_data);
$customize_data = str_replace('[invoice footor data]', '', $customize_data);
$customize_data = str_replace('', '', $customize_data);

$customize_data = str_replace('[invoice extra firlds import]', '', $customize_data);
$customize_data = str_replace('[invoice extra firlds import old one]', '', $customize_data);
echo $customize_data;
?>
                                        <div style="position: absolute;top:10px;"><button class="button button-secondary " style="display:none;" onclick="PrintElem('my_new_invoice', '<?php echo WF_INVOICE_MAIN_ROOT_PATH; ?> ', 'show')"  type="button"><i class="fa fa-eye"></i></button> 
                                        </div>
                                    </div>
                                    <!-- end of accordion -->
                                </div>
                            </div>
                        </div><?php
                            $thisindex_template_name = '';
                            $thisindex_template_name = get_option($current_loaded_invoice_theme.'name');
                            
                        ?>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2><i class="fa fa-align-left"></i><?php _e('Field Attributes', 'wf-woocommerce-packing-list'); ?><small><?php if($thisindex_template_name !== '' && $thisindex_template_name !== false){echo $thisindex_template_name;}else{echo 'Invoice ' . substr($invoice_data, -1);} ?></small>  </h2>
                                    <div class="pull-right">
                                        <span class="">                                            
                                            <input id="custom_invoice_nameholder" type="hidden" name="custom_invoice_nameholder" value="<?php if($thisindex_template_name !== ''){echo $thisindex_template_name;}else{echo '';} ?>"/>										

                                            <div class="tooltips"><button onclick="askForCustomName(); return true;" id="logo_save" name="logo_save" class="button button-primary" style="font-size: 12px;" ><?php echo __('Save & Activate', 'wf-woocommerce-packing-list'); ?></button>
                                                <span class="tooltiptext"><?php echo __('Save and Activate', 'wf-woocommerce-packing-list'); ?></span>
                                            </div>


                                        </span>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>


                                <div class="x_content">
                                    <!-- start accordion -->
                                    <div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                                        
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >

                                                <label class="switch pull-right ">
                                                    <input type="checkbox" id="wf_company_logo_switch" name="wf_company_logo_switch" value="company_logo"<?php echo $main_data_array[2] === 'no' ? '' : 'checked'; ?>/>  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="headingTwo1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Company Logo', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>


                                            <div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">

                                                            <label class="input-group-addon" for="logoheight"><?php _e('Display', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control clickable" id="company_logo_or_text" name="company_logo_or_text" ><?php
                                        if ($main_data_array[3] === 'logo') {
                                            echo '<option value="logo" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>
																<option value="name" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>';
                                        } else {
                                            echo '<option value="logo" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>
																<option value="name"  selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>';
                                        }
                                        ?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="logowidth"><?php _e('Width', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="logowidth" name="logowidth" placeholder="logo width" type="text" value="<?php echo $main_data_array[0]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="logoheight"><?php _e('Height', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="logoheight" name="logoheight" placeholder="logo height" type="text" value="<?php echo $main_data_array[1]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="logo_extra_details"><?php _e('Extra Details', 'wf-woocommerce-packing-list'); ?></label>
                                                            <textarea class="form-control" id="logo_extra_details" name="logo_extra_details" placeholder="Extra Details" ><?php echo str_replace('-*-', '|', $main_data_array[80]); ?></textarea>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="logo_extra_details_font"><?php _e('Font Size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="logo_extra_details_font" name="logo_extra_details_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[81]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                    </div>   

                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">

                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input id="wf_invoice_number_switch" name="wf_invoice_number_switch" value="invoice_number" type="checkbox"<?php echo $main_data_array[4] === 'no' ? '' : 'checked'; ?>/> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="headingThree1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Invoice Number', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>

                                            <div id="collapseThree1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_invoice_font" name="wf_invoice_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[5]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_number_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_invoice_number_text" name="wf_invoice_number_text" placeholder="invoice text" type="text" value="<?php echo $main_data_array[7]; ?>">

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_number_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control clickable" id="wf_invoice_number_font_weight" name="wf_invoice_number_font_weight" ><?php
                                        if ($main_data_array[6] === 'normal') {
                                            echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
                                        } else {
                                            echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
                                        }
                                        ?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_number_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_invoice_number_color_code" name="wf_invoice_number_color_code" value="<?php if ($main_data_array[8] != 'default') {
                                            echo $main_data_array[8];
                                        } else {
                                            echo '';
                                        } ?>" class="form-control jscolor" />

                                                            <span class="input-group-addon"><input type="checkbox" id='wf_invoice_number_color_code_default' name='wf_invoice_number_color_code_default'<?php echo $main_data_array[8] === $default_active_array[8] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                        
                                        <div class="panel">
                                            
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" id="wf_order_number_switch" name="wf_order_number_switch" value="order_number"<?php echo $main_data_array[94] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="headingEighty9" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseEighty9" aria-expanded="false" aria-controls="collapseEighty"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Order Number', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>

                                            <div id="collapseEighty9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEighty9">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_number_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_order_number_font" name="wf_order_number_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[89]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_number_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_order_number_text" name="wf_order_number_text" placeholder="invoice text" type="text" value="<?php echo $main_data_array[90]; ?>">

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_number_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control clickable" id="wf_order_number_font_weight" name="wf_order_number_font_weight" ><?php
                                        if ($main_data_array[91] === 'normal') {
                                            echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
                                        } else {
                                            echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
                                        }
                                        ?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_number_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_order_number_color_code" name="wf_order_number_color_code" value="<?php if ($main_data_array[92] != 'default') {
                                            echo $main_data_array[92];
                                        } else {
                                            echo '';
                                        } ?>" class="form-control jscolor" />

                                                            <span class="input-group-addon"><input type="checkbox" id='wf_order_number_color_code_default' name='wf_order_number_color_code_default'<?php echo $main_data_array[92] === $default_active_array[8] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" id="wf_invoice_date_switch" name="wf_invoice_date_switch" value="invoice_date"<?php echo $main_data_array[9] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed"  role="tab" id="headingFour1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseFour1" aria-expanded="false" aria-controls="collapseFour"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Invoice Date', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>

                                            <div id="collapseFour1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_date_format"><?php _e('Format', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_invoice_date_format" name="wf_invoice_date_format" placeholder="Date Format" type="text" value="<?php echo $main_data_array[10]; ?>">

                                                            <span class="input-group-addon" >

                                                                <select id = 'wf_date_format_selection' name='wf_date_format_selection' style="width:auto;height:auto;padding:0px;" >
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value="0">-SELECT-</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/Y'>d/m/Y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/y'>d/m/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/y'>d/M/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/Y'>d/M/Y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/Y'>m/d/Y</option>										
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/y'>m/d/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/y'>M/d/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/Y'>M/d/Y</option>
                                                                </select>
                                                            </span></div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_date_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_invoice_date_font" name="wf_invoice_date_font" placeholder="size" type="text" value="<?php echo $main_data_array[11]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_date_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_invoice_date_text" name="wf_invoice_date_text" placeholder="Invoice Date Text" type="text" value="<?php echo $main_data_array[12]; ?>">

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_date_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control clickable" id="wf_invoice_date_font_weight" name="wf_invoice_date_font_weight" ><?php
if ($main_data_array[13] === 'normal') {
    echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																	<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
} else {
    echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																	<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
}
?>
                                                            </select>

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_invoice_date_color"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_invoice_date_color" name="wf_invoice_date_color" value="<?php if ($main_data_array[14] != 'default') {
    echo $main_data_array[14];
} else {
    echo '';
} ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_invoice_date_color_code_default' name='wf_invoice_date_color_code_default'<?php echo $main_data_array[14] === $default_active_array[14] ? 'checked' : ''; ?> />Default</span>
                                                        </div>

                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" id="wf_order_date_switch" name="wf_order_date_switch" value="order_date"<?php echo $main_data_array[15] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed"  role="tab" id="heading41" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse41" aria-expanded="false" aria-controls="collapse4"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Order Date', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>

                                            <div id="collapse41" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_date_format"><?php _e('Format', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_order_date_format" name="wf_order_date_format" placeholder="Date Format" type="text" value="<?php echo $main_data_array[16]; ?>">

                                                            <span class="input-group-addon" >

                                                                <select id = 'wf_order_date_format_selection' name='wf_order_date_format_selection' style="width:auto;height:auto;padding:0px;" >
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value="0">-SELECT-</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/Y'>d/m/Y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/y'>d/m/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/y'>d/M/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/Y'>d/M/Y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/Y'>m/d/Y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/y'>m/d/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/y'>M/d/y</option>
                                                                    <option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/Y'>M/d/Y</option>
                                                                </select>
                                                            </span></div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_date_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_order_date_font" name="wf_order_date_font" placeholder="size" type="text" value="<?php echo $main_data_array[17]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_date_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_order_date_text" name="wf_order_date_text" placeholder="Order Date Text" type="text" value="<?php echo $main_data_array[18]; ?>">

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_date_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control clickable" id="wf_order_date_font_weight" name="wf_order_date_font_weight" ><?php
if ($main_data_array[19] === 'normal') {
    echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																		<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
} else {
    echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																		<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
}
?>
                                                            </select>

                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_order_date_color"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_order_date_color" name="wf_order_date_color" value="<?php if ($main_data_array[20] != 'default') {
    echo $main_data_array[20];
} else {
    echo '';
} ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_order_date_color_code_default' name='wf_order_date_color_code_default'<?php echo $main_data_array[20] === $default_active_array[20] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="from_address" id="wf_from_address_switch" name="wf_from_address_switch"<?php echo $main_data_array[21] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="headingFive1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseFive1" aria-expanded="false" aria-controls="collapseFive"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('From Address', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapseFive1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_from_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_from_address_title" name="wf_from_address_title" placeholder="From Address Title" type="text" value="<?php echo $main_data_array[22]; ?>">

                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_from_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id="wf_from_address_text_align" name="wf_from_address_text_align" ><?php
if ($main_data_array[23] === 'right') {
    echo "<option selected='true' >right</option> <option>left</option>";
} else {
    echo "<option >right</option> <option selected='true' >left</option>";
}
?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_from_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_from_address_color_code" name="wf_from_address_color_code" value="<?php if ($main_data_array[24] != 'default') {
    echo $main_data_array[24];
} else {
    echo '';
} ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_from_address_color_code_default' name='wf_from_address_color_code_default'<?php echo $main_data_array[24] === $default_active_array[24] ? 'checked' : ''; ?> />Default</span>
                                                        </div>

                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="billing_address" id="wf_billing_address_switch" name="wf_billing_address_switch"<?php echo $main_data_array[25] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="headingsix1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapsesix1" aria-expanded="false" aria-controls="collapsesix"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Billing Address', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapsesix1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsix">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_billing_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_billing_address_title" name="wf_billing_address_title" placeholder="Billing title" type="text" value="<?php echo $main_data_array[26]; ?>">

                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_billing_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id="wf_billing_address_text_align" name="wf_billing_address_text_align" ><?php
if ($main_data_array[27] === 'right') {
    echo "<option selected='true' >right</option> <option>left</option>";
} else {
    echo "<option >right</option> <option selected='true' >left</option>";
}
?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_billing_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_billing_address_color_code" name="wf_billing_address_color_code" value="<?php if ($main_data_array[28] != 'default') {
                                                                    echo $main_data_array[28];
                                                                } else {
                                                                    echo '';
                                                                } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_billing_address_color_code_default' name='wf_billing_address_color_code_default'<?php echo $main_data_array[28] === $default_active_array[28] ? 'checked' : ''; ?> />Default</span>
                                                        </div>

                                                    </div>     
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="shipping_address" id="wf_shiping_address_switch" name="wf_shipping_address_switch"<?php echo $main_data_array[29] === 'no' ? '' : 'checked'; ?>  />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="heading71" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse71" aria-expanded="false" aria-controls="collapse7"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Shipping Address', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapse71" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7">
                                                <div class="panel-body">
                                                    <div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_shipping_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_shipping_address_title" name="wf_shipping_address_title" placeholder="shipping Title" type="text" value="<?php echo $main_data_array[30]; ?>">

                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_shipping_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id="wf_shipping_address_text_align" name="wf_shipping_address_text_align" ><?php
if ($main_data_array[31] === 'right') {
    echo "<option selected='true' >right</option> <option>left</option>";
} else {
    echo "<option >right</option> <option selected='true' >left</option>";
}
?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_shipping_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_shipping_address_color_code" name="wf_shipping_address_color_code" value="<?php if ($main_data_array[32] != 'default') {
    echo $main_data_array[32];
} else {
    echo '';
} ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_shipping_address_color_code_default' name='wf_shipping_address_color_code_default'<?php echo $main_data_array[32] === $default_active_array[32] ? 'checked' : ''; ?> />Default</span>
                                                        </div>

                                                    </div>    
                                                </div>
                                            </div>
                                        </div>

                                                                
                      
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="tp" id="wf_tp_switch" name="wf_tp_switch"<?php echo $main_data_array[53] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="heading121" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse121" aria-expanded="false" aria-controls="collapse12"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Shipping Method', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapse121" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading12">
                                                <div class="panel-body">
                                                    <div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="tp_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="tp_font" name="tp_font" placeholder="size" type="Font size" value="<?php echo $main_data_array[54]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="tp_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="tp_text" name="tp_text" placeholder="character" type="text" value="<?php echo $main_data_array[55]; ?>">
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_tp_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id="wf_tp_text_align" name="wf_tp_text_align" ><?php
if ($main_data_array[56] === 'right') {
    echo "<option selected='true' >right</option> <option>left</option>";
} else {
    echo "<option >right</option> <option selected='true' >left</option>";
}
?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_tp_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_tp_color_code" name="wf_tp_color_code" value="<?php if ($main_data_array[57] != 'default') {
                                                                    echo $main_data_array[57];
                                                                } else {
                                                                    echo '';
                                                                } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_tp_color_code_default' name='wf_tp_color_code_default'<?php echo $main_data_array[57] === $default_active_array[57] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="tn" id="wf_tn_switch" name="wf_tn_switch"<?php echo $main_data_array[58] === 'no' ? '' : ''; ?> />
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="heading131" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse131" aria-expanded="false" aria-controls="collapse13"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Tracking Number', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapse131" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading13">
                                                <div class="panel-body">
                                                    <div>
                                                        <span class="more-details pro-more-details-tracking" id="">
                                                            <small style="color:white;">(Pro version)</small>
                                                        </span>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="tn_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="tn_font" name="tn_font" placeholder="size" type="font size" value="<?php echo $main_data_array[59]; ?>">
                                                            <span class="input-group-addon">px</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="ssn_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly class="form-control" id="tn_text" name="tn_text" placeholder="character" type="text" value="<?php echo $main_data_array[60]; ?>" >
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_tn_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id="wf_tn_text_align" name="wf_tn_text_align" ><?php
if ($main_data_array[61] === 'right') {
    echo "<option selected='true' >right</option> <option>left</option>";
} else {
    echo "<option >right</option> <option selected='true' >left</option>";
}
?>

                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_tn_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly type="text" id="wf_tn_color_code" name="wf_tn_color_code" value="<?php if ($main_data_array[62] != 'default') {
                                                                    echo $main_data_array[62];
                                                                } else {
                                                                    echo '';
                                                                } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_tn_color_code_default' name='wf_tn_color_code_default'<?php echo $main_data_array[62] === $default_active_array[62] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <label class="switch pull-right ">
                                                    <input type="checkbox" value="product" id="wf_product_switch" name="wf_product_switch"<?php echo $main_data_array[63] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
                                                </label>
                                                <h4 class="panel-title collapsed" role="tab" id="heading141" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse141" aria-expanded="false" aria-controls="collapse14"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Product Table', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapse141" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading14">
                                                <div class="panel-body">
                                                    <div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_head_back_code"><?php _e('Head Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly type="text" id="wf_head_back_code" name="wf_head_back_code" value="<?php if ($main_data_array[64] != 'default') {
                                            echo $main_data_array[64];
                                        } else {
                                            echo '';
                                        } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_head_back_color_code_default' name='wf_head_back_color_code_default'<?php echo $main_data_array[64] === $default_active_array[64] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_head_front_code"><?php _e('Text Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input type="text" id="wf_head_front_code" name="wf_head_front_code" value="<?php if ($main_data_array[65] != 'default') {
                                            echo $main_data_array[65];
                                        } else {
                                            echo '';
                                        } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_head_front_color_code_default' name='wf_head_front_color_code_default'<?php echo $main_data_array[65] === $default_active_array[65] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_get_text_align_head"><?php _e('Head Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id='wf_get_text_align_head' name='wf_get_text_align_head'><?php
                                                                if ($main_data_array[66] === 'right') {

                                                                    echo"<option value='right' selected='true'>right</option>
																		<option value='left'>left</option>
																		<option value='center'>Center</option> ";
                                                                } else if ($main_data_array[66] === 'left') {

                                                                    echo"	<option value='right'>right</option>
																		<option value='left' selected='true'>left</option>
																		<option value='center'>Center</option> ";
                                                                } else {

                                                                    echo"<option value='right'>right</option>
																		<option value='left'>left</option>
																		<option value='center' selected='true'>Center</option> ";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_body_front_code"><?php _e('Body Color', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly type="text" id="wf_body_front_code" name='wf_body_front_code' value="<?php if ($main_data_array[67] != 'default') {
                                                                    echo $main_data_array[67];
                                                                } else {
                                                                    echo '';
                                                                } ?>" class="form-control jscolor" />
                                                            <span class="input-group-addon"><input type="checkbox" id='wf_body_front_color_code_default' name='wf_body_front_color_code_default'<?php echo $main_data_array[67] === $default_active_array[67] ? 'checked' : ''; ?> />Default</span>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_get_text_align_body"><?php _e('Body Align', 'wf-woocommerce-packing-list'); ?></label>
                                                            <select class="form-control" id='wf_get_text_align_body' name='wf_get_text_align_body'><?php
if ($main_data_array[68] === 'right') {
    echo"<option value='right' selected='true'>right</option>
																		<option value='left'>left</option>
																		<option value='center'>Center</option> ";
} else if ($main_data_array[68] === 'left') {

    echo"	<option value='right'>right</option>
																		<option value='left' selected='true'>left</option>
																		<option value='center'>Center</option> ";
} else {
    echo"<option value='right'>right</option>
																		<option value='left'>left</option>
																		<option value='center' selected='true'>Center</option> ";
}
?>
                                                            </select>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="sku_text"><?php _e('SKU', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly class="form-control" id="sku_text" name="sku_text" placeholder="character" type="SKU column text" value="<?php echo $main_data_array[69]; ?>">
                                                        </div>

                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="product_text"><?php _e('Product', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly class="form-control" id="product_text" name="product_text" placeholder="Product column text" type="text" value="<?php echo $main_data_array[70]; ?>">
                                                        </div>


                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="qty_text"><?php _e('Qty', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly class="form-control" id="qty_text" name="qty_text" placeholder="character" type="Qty column text" value="<?php echo $main_data_array[71]; ?>">
                                                        </div>


                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="total_text"><?php _e('Total', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input readonly class="form-control" id="total_text" name="total_text" placeholder="character" type="total column text" value="<?php echo $main_data_array[72]; ?>">
                                                        </div>

                                                    </div>   
                                                </div>
                                            </div>
                                        </div>

                                        
                                            
                                       
                                        
                                        <div class="panel">
                                            <div class="panel-heading  clickable" >
                                                <h4 class="panel-title collapsed" role="tab" id="heading151" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse151" aria-expanded="false" aria-controls="collapse15"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Extra Charges Fields', 'wf-woocommerce-packing-list'); ?></h4>
                                            </div>
                                            <div id="collapse151" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading15">
                                                        
                                                <div class="panel-body"> 
                                                    <div>
                                                <span class="more-details pro-more-details" id="">
                                                        <small style="color:white;">(Pro version)</small>
                                                </span>
                                                        <p><strong><?php _e('Sub Total:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_subtotal_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_subtotal_text" name="wf_subtotal_text" placeholder="sub total text" type="text" value="<?php echo $main_data_array[73]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Shipping:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_shipping_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_shipping_text" name="wf_shipping_text" placeholder="shipping text" type="text" value="<?php echo $main_data_array[74]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Cart Discount:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_cd_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_cd_text" name="wf_cd_text" placeholder="size" type="cart discount text" value="<?php echo $main_data_array[75]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Order Discount:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_od_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_od_text" name="wf_od_text" placeholder="size" type="order discount text" value="<?php echo $main_data_array[76]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Total Tax:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_tt_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_tt_text" name="wf_tt_text" placeholder="size" type="Total tax text" value="<?php echo $main_data_array[77]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Total:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_total_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_total_text" name="wf_total_text" placeholder="Total text" type="text" value="<?php echo $main_data_array[78]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Fee:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_fee_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_fee_text" name="wf_fee_text" placeholder="Total fee" type="text" value="<?php echo $main_data_array[93]; ?>">
                                                        </div>
                                                        <p><strong><?php _e('Payment Method:', 'wf-woocommerce-packing-list'); ?> </strong> </p>
                                                        <div class="input-group input-group-sm">
                                                            <label class="input-group-addon" for="wf_paym_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
                                                            <input class="form-control" id="wf_paym_text" name="wf_paym_text" placeholder="payment method text" type="text" value="<?php echo $main_data_array[79]; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- end of accordion -->


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /page content -->

            <!-- footer content -->   <!-- /footer content -->
        </div>
    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
</form>

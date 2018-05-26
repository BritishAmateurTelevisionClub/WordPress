<?php
$woocommerce_wf_enable_invoice = get_option('woocommerce_wf_enable_invoice') != '' ? get_option('woocommerce_wf_enable_invoice') : 'Yes';
$wf_generate_invoice_for = get_option('woocommerce_wf_generate_for_orderstatus') ? get_option('woocommerce_wf_generate_for_orderstatus') : array("wc-completed");

if (isset($_POST['new_custom_click'])) {
    if (get_option('wf_invoice_new_custom_field_for_checkout')) {
        if (isset($_POST['wf_new_custom_filed'])) {
            $tem_data = get_option('wf_invoice_new_custom_field_for_checkout');
            $tem_array = explode('|', $tem_data);
            $tem_array[] = $_POST['wf_new_custom_filed'];
            update_option('wf_invoice_new_custom_field_for_checkout', implode('|', $tem_array));

            $data_slected_array = get_option('woocommerce_wf_additional_fields') != '' ? get_option('woocommerce_wf_additional_fields') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_new_custom_filed']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_new_custom_filed']);
                update_option('woocommerce_wf_additional_fields', $data_slected_array);
            }
        }
    } else {
        if (isset($_POST['wf_new_custom_filed'])) {
            update_option('wf_invoice_new_custom_field_for_checkout', $_POST['wf_new_custom_filed']);

            $data_slected_array = get_option('woocommerce_wf_additional_fields') != '' ? get_option('woocommerce_wf_additional_fields') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_new_custom_filed']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_new_custom_filed']);
                update_option('woocommerce_wf_additional_fields', $data_slected_array);
            }
        }
    }
    if (get_option('wf_invoice_own_meta_field_import')) {
        if (isset($_POST['wf_old_custom_filed']) && isset($_POST['wf_old_custom_filed_meta'])) {
            $data_array = array();
            $data_array = get_option('wf_invoice_own_meta_field_import');
            $data_array[str_replace(' ', '_', $_POST['wf_old_custom_filed_meta'])] = $_POST['wf_old_custom_filed'];
            update_option('wf_invoice_own_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_invoice_contactno_email') != '' ? get_option('wf_invoice_contactno_email') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_custom_filed_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_custom_filed_meta']);
                update_option('wf_invoice_contactno_email', $data_slected_array);
            }
        }
    } else {
        if (isset($_POST['wf_old_custom_filed']) && isset($_POST['wf_old_custom_filed_meta'])) {
            $data_array = array();
            $data_array[str_replace(' ', '_', $_POST['wf_old_custom_filed_meta'])] = $_POST['wf_old_custom_filed'];
            update_option('wf_invoice_own_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_invoice_contactno_email') != '' ? get_option('wf_invoice_contactno_email') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_custom_filed_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_custom_filed_meta']);
                update_option('wf_invoice_contactno_email', $data_slected_array);
            }
        }
    }

    if (get_option('wf_invoice_own_product_meta_field_import')) {
        if (isset($_POST['wf_old_product_custom_filed']) && isset($_POST['wf_old_product_custom_filed_meta'])) {
            $data_array = array();
            $data_array = get_option('wf_invoice_own_product_meta_field_import');
            $data_array[$_POST['wf_old_product_custom_filed_meta']] = $_POST['wf_old_product_custom_filed'];
            update_option('wf_invoice_own_product_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_invoice_product_meta_fields') != '' ? get_option('wf_invoice_product_meta_fields') : array();

            if (!in_array($_POST['wf_old_product_custom_filed_meta'], $data_slected_array)) {
                $data_slected_array[] = $_POST['wf_old_product_custom_filed_meta'];
                update_option('wf_invoice_product_meta_fields', $data_slected_array);
            }
        }
    } else {
        if (isset($_POST['wf_old_product_custom_filed']) && isset($_POST['wf_old_product_custom_filed_meta'])) {
            $data_array = array();
            $data_array[str_replace(' ', '_', $_POST['wf_old_product_custom_filed_meta'])] = $_POST['wf_old_product_custom_filed'];
            update_option('wf_invoice_own_product_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_invoice_product_meta_fields') != '' ? get_option('wf_invoice_product_meta_fields') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_product_custom_filed_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_product_custom_filed_meta']);
                update_option('wf_invoice_product_meta_fields', $data_slected_array);
            }
        }
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><button name="new_custom_click" id="new_custom_click" class="btn btn-info btn-sm">ADD</a></div></div>'
        });

        // Custom jQuery to hide popover on click of the close button
        $(document).on("click", ".popover-footer .btn", function () {
            $(this).parents(".popover").popover('hide');
        });
    });
</script>
<style type="text/css">
    .bs-example{
        margin: 150px 50px;
    }
    /* Styles for custom popover template */
    .popover-footer{
        padding: 6px 14px;
        background-color: #f7f7f7;
        border-top: 1px solid #ebebeb;
        text-align: right;
    }
    .toggle-box {
        display: none;
        visibility: hidden;
    }

    .toggle-box + label {
        cursor: pointer;
        display: block;
        font-weight: bold;
        line-height: 21px;
        margin-bottom: 5px;
    }

    .toggle-box + label + div {
        display: none;
        margin-bottom: 10px;
    }

    .toggle-box:checked + label + div {
        display: block;
    }

    .toggle-box + label:before {
        background-color: #4F5150;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        color: #FFFFFF;
        content: "+";
        display: block;
        float: left;
        font-weight: bold;
        height: 25px;
        line-height: 25px;
        margin-right: 5px;
        text-align: center;
        width: 25px;
    }

    .toggle-box:checked + label:before {
        content: "\2212";
    }
   

    .woocommerce-help-tip .tooltiptext {
    visibility: hidden;
    width: 120px;
    bottom: 100%;
    
     left: 50%;
    background-color: black;
    color: #fff;
    
    margin-left: -60px;
    text-align: center;
    padding: 5px 0;
    border-radius: 3px;
    font-size:10px;
 
    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
}
.spinner {


}
/* Show the tooltip text when you mouse over the tooltip container */
.woocommerce-help-tip:hover .tooltiptext {
    visibility: visible;
}

</style>
<div id="Invoice" class="tabcontent">

    <h3 class="settings_headings"><?php _e('Invoice Settings : ', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside packinglist-printing-preview">
        <table class="form-table">
            <tr>
                <th>
                    
                    <span><?php _e('Enable Invoice', 'wf-woocommerce-packing-list'); ?></span>
                    <div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:20px;"><span class="tooltiptext"><?php _e('Check to enable invoice','wf-woocommerce-packing-list') ?></span></div>
                </th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_enable_invoice" class=""<?php
if ($woocommerce_wf_enable_invoice == 'Yes')
    echo 'checked';
?> >

                </td>
            </tr>
            <tr>
                <th><span><?php _e('Templates', 'wf-woocommerce-packing-list'); ?></span>
                    <div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:20px;"><span class="tooltiptext"><?php _e('Select and activate a standard template for invoice or click customize to create your own template.','wf-woocommerce-packing-list') ?></span></div>
                </th>
                <td>
                    <div class="theme-browser rendered" style="width:100%;">
                        <div class="themes wp-clearfix"><?php

    // Default active invoice = wf_invoice_template_4

    if(get_option('wf_invoice_active_key') !== false ) {
        $active_template = get_option('wf_invoice_active_key'); 
        $extension = 'png';
        $active_template_num = str_replace(array('wf_invoice_template_', 'wf_invoice_template_new_'), '', $active_template);
        if(strstr($active_template, 'new')) {$active_template_num = '_new1';$extension = 'jpg';}
        
        if (get_option('wf_invoice_template_' . $active_template_num . 'custom', false) == false){
            
    ?>
                                        <div class="theme" tabindex="0">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=' . get_option('wf_invoice_active_key')) ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:220px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice'.$active_template_num.'.'.$extension ?>" alt=""> 
                                                </div>
                                                <span class="more-details more-details-btn" id="">Customize<br/>
                                                <!--<small style="color:red;">(Pro version) </small>-->
                                                </span>
                                            </a>
                                            <h2 class="theme-name" id="" style="height:50%" ><?php if (get_option('wf_invoice_active_key') !== false) { 
                                                    ?>
                                                 <div class="pull-right" style="color:#26B99A;font-size:13px;font-weight:normal;" ><div style="width:20px;height:20px;border-radius:50%;background:#00B196;"> <span class="dashicons dashicons-yes" style="font-size:20px;color:white;" ></span></div></div><?php } else {
        ?>
                                                        <a class="btn btn-sm btn-info pull-right" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_4') ?>">Activate</a><?php
                                        }

                        
                        
                        if ($active_template === 'wf_invoice_template_1') {
                                echo __('Classic', 'wf-woocommerce-packing-list');
                            } else if ($active_template === 'wf_invoice_template_2') {
                               echo __('Radiant', 'wf-woocommerce-packing-list');
                            } else if ($active_template === 'wf_invoice_template_3') {
                               echo __('Refined', 'wf-woocommerce-packing-list');
                            } else if ($active_template === 'wf_invoice_template_new_1') {
                               echo __('Elegant', 'wf-woocommerce-packing-list');
                            }else{
                            
                            $thisindex_template_name = get_option('wf_invoice_template_'.$active_template_num.'name');
                                                                                                                        
                            if($thisindex_template_name !=='' && $thisindex_template_name !==false){echo $thisindex_template_name;}else{echo __('Invoice ', 'wf-woocommerce-packing-list').$active_template_num; }    
                                  
                            }
                                        ?>
                                            </h2>
                                        </div><?php
        }
    }




                                 //custom templates
                                 include 'wf-custom-invoice-templates.php'; ?>
                            
                            <div class="theme add-new-theme wf-add-new-theme"><a href="#"><div class="theme-screenshot"><span></span></div><h2 class="theme-name"><?php _e('Create a Design', 'wf-woocommerce-packing-list'); ?></h2></a></div>
                            
                        </div>
                        <div id="wf-default-templates" style="display:none;">
                            <div class="close-default-invoice" style="cursor:pointer;">X</div><?php include_once 'wf-default-invoice-templates.php'; ?>
                        </div>
                    </div>
                </td>	
            </tr>
            
            <tr>
                <th>
                <span><?php _e('Attach PDF invoice in email', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Enable to attach send invoice to the buyer’s email as a PDF file.','wf-woocommerce-packing-list')?> </span></div></th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_add_invoice_in_mail" class=""<?php
                                    if (get_option('woocommerce_wf_add_invoice_in_mail') == "Yes")
                                        echo 'checked';
                                    ?> >
                </td>
            </tr>
            <tr>
                <th> <span><?php _e('Order Status to Generate Invoice', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Select the statuses of the order to which an invoice will be generated.','wf-woocommerce-packing-list')?> </span></div></th>
                <td><?php
                            ?>
                    <select style="width:350px" class="wc-enhanced-select" id="order_status" data-placeholder='Choose Order Status' name="woocommerce_wf_generate_for_orderstatus[]" multiple="multiple"><?php
                            $statuses = wc_get_order_statuses();
                            foreach ($statuses as $key => $value) {

                                if (in_array($key, $wf_generate_invoice_for)) {
                                    echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                } else {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                }
                            }
                            ?>
                    </select>
                </td>	
            </tr>
            <tr>
                <th><span><?php _e('Use Order Number as Invoice Number', 'wf-woocommerce-packing-list'); ?></span> <div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Check to use the order number as the invoice number. ', 'wf-woocommerce-packing-list'); ?></span></div></th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_invoice_as_ordernumber" class=""<?php
                                        if (get_option('woocommerce_wf_invoice_as_ordernumber') == "Yes")
                                            echo 'checked';
                                        ?> >
                </td>
            </tr>
            <tr class="invoice_hide">

                <th> <span><?php _e('Invoice Start Number', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Set the number to mark the start of the invoice number.','wf-woocommerce-packing-list')?> </span></div></th>
                <td>	
                    <input type="number" min="0" name="woocommerce_wf_invoice_start_number" readonly class=""
                           value="<?php echo stripslashes(get_option('woocommerce_wf_invoice_start_number')); ?>"><span class="spinner"></span>

                    &nbsp;<input id="reset_invoice_button" type="button"  class="button button-primary" value="<?php _e('Reset Invoice no', 'wf-woocommerce-packing-list'); ?>" />
                    <input style="display:none;" id="save_invoice_button" type="button"  class="button button-primary" value="<?php _e('Save', 'wf-woocommerce-packing-list'); ?>" />
                    <div class="success_div" style="padding:10px;background:green;display:none;width:250px;height:50px;"><p style="color:white;">Saved successfully</p></div>
                </td>	

            </tr> 
           
            <tr>                

            <script type="text/javascript">
jQuery('.wf-add-new-theme').click(function(e) {
    e.preventDefault();
    jQuery('#wf-default-templates').show();
});

jQuery('.close-default-invoice').click(function(e) {
    e.preventDefault();
    jQuery('#wf-default-templates').hide();
});
            </script>


            <tr>
                <th> <label for="woocommerce_wf_packinglist_invoice_logo"><b><?php _e('Custom Logo', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px"><span class="tooltiptext"><?php _e('Choose a custom logo to be included in the invoice if needed', 'wf-woocommerce-packing-list'); ?></span></div> </th>
                <td><input id="woocommerce_wf_packinglist_invoice_logo" type="text" size="36" name="woocommerce_wf_packinglist_invoice_logo" value="<?php echo get_option('woocommerce_wf_packinglist_invoice_logo'); ?>" />
                    <input id="invoice_upload_image_button" type="button" class="btn btn-info btn-sm" value="<?php _e('Upload Image', 'wf-woocommerce-packing-list'); ?>" /><br />
                    <span class="description"><?php
echo '<strong>' . __('Note:', 'wf-woocommerce-packing-list') . '</strong> ';
echo __('Leave blank to not use a custom logo for invoice.', 'wf-woocommerce-packing-list');
?>
                    </span>
                </td>
            </tr>

        </table>

    </div>
</div>
<?php
    $woocommerce_wf_packinglist_rtl_settings_enable = get_option('woocommerce_wf_packinglist_rtl_settings_enable') != '' ? get_option('woocommerce_wf_packinglist_rtl_settings_enable') : 'No';

    $woocommerce_wf_currency_support = get_option('woocommerce_wf_currency_support') != '' ? get_option('woocommerce_wf_currency_support') : 'No';
    

?>
<div id="General" class="tabcontent">
    <h3 class="settings_headings"><?php _e('Generic Settings :', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside packinglist-printing-preview">
        <style type="text/css">
            .tooltips {
                position: relative;
                display: inline-block;
                border-bottom: 1px dotted black;
            }

            .tooltips .tooltiptext {
                visibility: hidden;
                width: auto;
                background-color: black;
                opacity: 0.7;
                color: #fff;
                text-align: center;
                border-radius: 6px;
                padding: 5px 0;
                /* Position the tooltip */
                position: absolute;
                z-index: 1;
                top: -5px;
                bottom: 105%;
            }

            .tooltips:hover .tooltiptext {
                visibility: visible;
                position: relative;
            }
        </style>
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

        </style>
        </style>
        <table class="form-table">
            <tr>
                <th><label for="woocommerce_wf_packinglist_companyname"><b><?php _e('Company Name', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px"><span class="tooltiptext"><?php _e('Set your company name if required.
', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    <input type="text" name="woocommerce_wf_packinglist_companyname" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_companyname')); ?>" />                        
                    
                    <br/>
                    <span class="description"><?php
                            echo '<strong>' . __('Note:', 'wf-woocommerce-packing-list') . '</strong> ';
                            echo __('Leave blank to not print a company name.', 'wf-woocommerce-packing-list');
?>
                    </span>
                </td>
            </tr>
            <tr>
                <th> <label for="woocommerce_wf_packinglist_logo"><b><?php _e('Custom Logo', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px"><span class="tooltiptext"><?php _e('Set URL or upload a custom image for adding logo to all labels.
', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td><input id="woocommerce_wf_packinglist_logo" type="text" size="36" name="woocommerce_wf_packinglist_logo" value="<?php echo get_option('woocommerce_wf_packinglist_logo'); ?>" />
                    <input id="upload_image_button" type="button"  class="btn btn-info btn-sm" value="<?php _e('Upload Image', 'wf-woocommerce-packing-list'); ?>" /><br />
                    <span class="description"><?php
                            echo '<p>We recommend you to upload a 150 x 30 px logo </p>';?></span>                  
                    <span class="description"><?php
                        echo '<strong>' . __('Note:', 'wf-woocommerce-packing-list') . '</strong> ';
                        echo __('Leave blank to not use a custom logo.', 'wf-woocommerce-packing-list');
                        ?>
                    </span>
                </td>
            </tr>
            </tr>
        </table>
    </div>
    <h3 class="settings_headings"><?php _e('Shipping From Address : ', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside shipment-label-printing-preview">
        <table class="form-table">
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_name"><b><?php _e('Sender Name', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e(' Set the name of the sender i.e.,  the Woocommerce shop owner 
', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    <input type="text" name="woocommerce_wf_packinglist_sender_name" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_name')); ?>" />                        
                    

                </td>
            </tr>
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_address_line1"><b><?php _e('Sender Address Line1', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e(' Set the sender’s first line of address i.e.,  the Woocommerce shop owner', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>                   
                        <input type="text" name="woocommerce_wf_packinglist_sender_address_line1" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_address_line1')); ?>" />                        

                </td>
            </tr>
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_address_line2"><b><?php _e('Sender Address Line2', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('  Set the sender’s second line of address i.e.,  the Woocommerce shop owner', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                        <input type="text" name="woocommerce_wf_packinglist_sender_address_line2" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_address_line2')); ?>" />
                    
                </td>
            </tr>
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_city"><b><?php _e('Sender City', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Set the sender’s city ', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                        <input type="text" name="woocommerce_wf_packinglist_sender_city" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_city')); ?>" />
                        
                </td>
            </tr>
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_country"><b><?php _e('Sender Country', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Set the sender’s country ', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    
                        <input type="text" name="woocommerce_wf_packinglist_sender_country" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_country')); ?>" />
                    
                </td>
            </tr>
            
            <tr>
                <th><label for="woocommerce_wf_packinglist_sender_postalcode"><b><?php _e('Sender Postal Code', 'wf-woocommerce-packing-list'); ?></b></label><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Set the sender’s postal code ', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    
                        <input type="text" name="woocommerce_wf_packinglist_sender_postalcode" class="regular-text" value="<?php echo stripslashes(get_option('woocommerce_wf_packinglist_sender_postalcode')); ?>" />
                        
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="woocommerce_wf_packinglist_RTL"><b><?php _e('RTL enable/disable','wf-woocommerce-packing-list');?></b></label></th>
                <td>
                    <input type="checkbox" id="woocommerce_wf_packinglist_rtl_settings_enable" name="woocommerce_wf_packinglist_rtl_settings_enable" value="Yes" class=""
                    <?php
                        if ($woocommerce_wf_packinglist_rtl_settings_enable == 'Yes')
                        echo 'checked';
                    ?> >

            </td>   
            </tr>
            
        </table>
        <input class="toggle-box" id="identifier-2" type="checkbox"  value='Yes' name='wf_view_checkbox_data_general'<?php
                            if ($this->wf_view_checkbox_data_general == "Yes")
                                echo 'checked';
                            ?> >
        
    </div>
</div>
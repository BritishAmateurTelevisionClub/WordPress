<?php
$wf_enable_contact_number = get_option('woocommerce_wf_packinglist_contact_number') != '' ? get_option('woocommerce_wf_packinglist_contact_number') : 'Yes';
$wf_enable_shipping_label = get_option('woocommerce_wf_enable_shipping_label') != '' ? get_option('woocommerce_wf_enable_shipping_label') : 'Yes';
?>
<div id="Invoice" class="tabcontent">
    <h3 class="settings_headings"><?php _e('Shipping Label Settings :', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside shipment-label-printing-preview">
        <table class="form-table">
            <tr>
                <th><span><?php _e('Enable Shipping Label', 'wf-woocommerce-packing-list'); ?></span>
                    <div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Check to enable shipping label.
	', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_enable_shipping_label" class=""<?php
                    if ($wf_enable_shipping_label == 'Yes')
                        echo 'checked';
                    ?> >

                </td>
            </tr>
            <tr>
                <th><label for="label_size"><b><?php _e('Shipping Label Size', 'wf-woocommerce-packing-list'); ?></b></label></th>
                <td>
                    <input type="text" name="woocommerce_wf_packinglist_label_size" class="regular-text" value="Full page" disabled="true" />
                </td>
            </tr>

        </table>
    </div>
</div>

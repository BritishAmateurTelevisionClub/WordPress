<div id="Invoice" class="tabcontent">
    <h3 class="settings_headings"><?php _e('Dispatch Label Settings :', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside shipment-label-printing-preview">
        <table class="form-table">
            <tr>
                <th><span><?php _e('Enable Dispatch Label', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e(' Check to enable dispatch label	

', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td><?php $woocommerce_wf_enable_dispath_label = get_option('woocommerce_wf_enable_dispath_label') != '' ? get_option('woocommerce_wf_enable_dispath_label') : 'Yes' ?>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_enable_dispath_label" class=""<?php
                    if ($woocommerce_wf_enable_dispath_label == 'Yes')
                        echo 'checked';
                    ?> >
                </td>
            </tr>
        </table>
    </div>
</div>

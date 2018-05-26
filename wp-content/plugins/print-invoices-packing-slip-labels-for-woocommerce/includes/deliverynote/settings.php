<?php
$woocommerce_wf_enable_delivery_note = get_option('woocommerce_wf_enable_delivery_note') != '' ? get_option('woocommerce_wf_enable_delivery_note') : 'Yes';
$woocommerce_wf_delivery_note_disable_total_weight = get_option('woocommerce_wf_delivery_note_disable_total_weight') != '' ? get_option('woocommerce_wf_delivery_note_disable_total_weight') : 'no';

if (isset($_POST['new_custom_click'])) {
    if (get_option('wf_delivery_note_own_meta_field_import')) {
        if (isset($_POST['wf_old_custom_filed_dn']) && isset($_POST['wf_old_custom_filed_dn_meta'])) {
            $data_array = array();
            $data_array = get_option('wf_delivery_note_own_meta_field_import');
            $data_array[str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta'])] = $_POST['wf_old_custom_filed_dn'];
            update_option('wf_delivery_note_own_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_deliverynote_contactno_email') != '' ? get_option('wf_deliverynote_contactno_email') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta']);
                update_option('wf_deliverynote_contactno_email', $data_slected_array);
            }
        }
    } else {
        if (isset($_POST['wf_old_custom_filed_dn']) && isset($_POST['wf_old_custom_filed_dn_meta'])) {
            $data_array = array();
            $data_array[str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta'])] = $_POST['wf_old_custom_filed_dn'];
            update_option('wf_delivery_note_own_meta_field_import', $data_array);

            $data_slected_array = get_option('wf_deliverynote_contactno_email') != '' ? get_option('wf_deliverynote_contactno_email') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_custom_filed_dn_meta']);
                update_option('wf_deliverynote_contactno_email', $data_slected_array);
            }
        }
    }

    if (get_option('wf_packing_list_own_product_meta_field_import_dn')) {
        if (isset($_POST['wf_old_product_custom_filed_dn']) && isset($_POST['wf_old_product_custom_filed_dn_meta'])) {
            $data_array = array();
            $data_array = get_option('wf_packing_list_own_product_meta_field_import_dn');
            $data_array[$_POST['wf_old_product_custom_filed_dn_meta']] = $_POST['wf_old_product_custom_filed_dn'];
            update_option('wf_packing_list_own_product_meta_field_import_dn', $data_array);

            $data_slected_array = get_option('wf_packing_list_product_meta_fields_dn') != '' ? get_option('wf_packing_list_product_meta_fields_dn') : array();

            if (!in_array($_POST['wf_old_product_custom_filed_dn_meta'], $data_slected_array)) {
                $data_slected_array[] = $_POST['wf_old_product_custom_filed_dn_meta'];
                update_option('wf_packing_list_product_meta_fields_dn', $data_slected_array);
            }
        }
    } else {
        if (isset($_POST['wf_old_product_custom_filed_dn']) && isset($_POST['wf_old_product_custom_filed_dn_meta'])) {
            $data_array = array();
            $data_array[str_replace(' ', '_', $_POST['wf_old_product_custom_filed_dn_meta'])] = $_POST['wf_old_product_custom_filed_dn'];
            update_option('wf_packing_list_own_product_meta_field_import_dn', $data_array);

            $data_slected_array = get_option('wf_packing_list_product_meta_fields_dn') != '' ? get_option('wf_packing_list_product_meta_fields_dn') : array();

            if (!in_array(str_replace(' ', '_', $_POST['wf_old_product_custom_filed_dn_meta']), $data_slected_array)) {
                $data_slected_array[] = str_replace(' ', '_', $_POST['wf_old_product_custom_filed_dn_meta']);
                update_option('wf_packing_list_product_meta_fields_dn', $data_slected_array);
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

</style>
<div id="Invoice" class="tabcontent">
    <h3 class="settings_headings"><?php _e('Delivery Note Settings : ', 'wf-woocommerce-packing-list'); ?></h3>
    <div class="inside shipment-label-printing-preview">
        <table class="form-table">
            <tr>
                <th><span><?php _e('Enable Delivery Note', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px"><span class="tooltiptext"><?php _e('Check to enable delivery note.
', 'wf-woocommerce-packing-list') ?> </span></div></th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_enable_delivery_note" class=""<?php
if ($woocommerce_wf_enable_delivery_note == 'Yes')
    echo 'checked';?> >
                </td>
            </tr>
            <tr>
                <th>
                    <span><?php _e('Include Item/Product Image', 'wf-woocommerce-packing-list'); ?></span>
                    <div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e(' Check to include item/product image in deliver note
', 'wf-woocommerce-packing-list') ?> </span></div>
                </th>
                <td>
                    <input type="checkbox" value="Yes" name="woocommerce_wf_attach_image_delivery_note" class=""<?php
                            if (get_option('woocommerce_wf_attach_image_delivery_note') == "Yes")
                                echo 'checked';
                            ?> >
                    <br>
                    <span class="description"><?php _e('Enable to include Item/Product image in delivery note', ''); ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

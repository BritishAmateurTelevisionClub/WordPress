<?php
if (!defined('ABSPATH')) {
    exit;
}

$tab = (!empty($_GET['tab'])) ? ($_GET['tab']) : '';
if(!$tab)
$tab = (!empty($_GET['active_tab'])) ? ($_GET['active_tab']) : '';

switch ($tab) {
    case "packing_slip" :
        $this->packinglist->render_settings();
        break;
    case "delivery_note" :
        $this->deliverynote->render_settings();
        break;
    case "shipping_label" :
        $this->shippinglabel->render_settings();
        break;
    case "dispatch_label" :
        $this->dispatchlabel->render_settings();
        break;
    case "general" :
        $this->render_general_settings();
        break;
        break;
    case "invoice":
    default :
        $this->invoice->render_settings();
        break;
}
?>
<style>
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

    /* Show the tooltip text when you mouse over the tooltip container */
    .woocommerce-help-tip:hover .tooltiptext {
        visibility: visible;
    }
</style>
<?php
if (!defined('ABSPATH')) {
    exit;
}

$tab = (!empty($_GET['tab'])) ? ($_GET['tab']) : '';
if(!$tab)
$tab = (!empty($_GET['active_tab'])) ? ($_GET['active_tab']) : '';
if(!$tab)
$tab = (!empty($_GET['themeselection'])) ? ($_GET['themeselection']) : '';
    

$activation_check = get_option('packinglist_activation_status');
if (!empty($activation_check) && $activation_check === 'active') {
    $acivated_tab_html = "<small style='color:#5ccc96;font-size:xx-small;'> ( Activated ) </small>";
} else {
    $acivated_tab_html = "<small style='color:red;font-size:xx-small;'> ( Activate ) </small>";
}

?>

<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list') ?>" class="nav-tab <?php echo ($tab == '' || $tab == 'invoice') ? 'nav-tab-active' : ''; ?>"><?php _e('Invoice', 'wf-woocommerce-packing-list'); ?></a>
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=packing_slip') ?>" class="nav-tab <?php echo ($tab == 'packing_slip') ? 'nav-tab-active' : ''; ?>"><?php _e('Packing Slip', 'wf-woocommerce-packing-list'); ?></a>
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=shipping_label') ?>" class="nav-tab <?php echo ($tab == 'shipping_label') ? 'nav-tab-active' : ''; ?>"><?php _e('Shipping Label', 'wf-woocommerce-packing-list'); ?></a>
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=delivery_note') ?>" class="nav-tab <?php echo ($tab == 'delivery_note') ? 'nav-tab-active' : ''; ?>"><?php _e('Delivery Note', 'wf-woocommerce-packing-list'); ?></a>
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=dispatch_label') ?>" class="nav-tab <?php echo ($tab == 'dispatch_label') ? 'nav-tab-active' : ''; ?>"><?php _e('Dispatch Label', 'wf-woocommerce-packing-list'); ?></a>
    
    <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=general') ?>" class="nav-tab <?php echo ($tab == 'general') ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'wf-woocommerce-packing-list'); ?></a>
    <a href="https://www.xadapter.com/product/print-invoices-packing-list-labels-for-woocommerce/" target="_blank" class="nav-tab nav-tab-premium"><?php _e('Upgrade to Premium for More Features', 'wf-woocommerce-packing-list'); ?></a>
</h2>
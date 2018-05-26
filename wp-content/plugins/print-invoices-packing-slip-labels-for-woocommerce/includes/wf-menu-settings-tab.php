<?php
if (!defined('ABSPATH')) {
    exit;
}
$tab = (!empty($_GET['tab'])) ? ($_GET['tab']) : '';
if (!$tab)
    $tab = (!empty($_GET['active_tab'])) ? ($_GET['active_tab']) : '';
if (!$tab)
    $tab = (!empty($_GET['themeselection'])) ? ($_GET['themeselection']) : '';
if (!$tab) $tab = 'invoice';

$hidemenu_tabs = array('general');
if(!in_array($tab, $hidemenu_tabs)){
?>
<ul class="subsubsub">
    <li><a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=').$tab; ?>" class="current"><?php _e('Settings', 'wf-woocommerce-packing-list'); ?></a> </li><?php if('invoice'==$tab): ?> |
    <li><a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=').$tab.'&theme='.get_option('wf_invoice_active_key'); ?>" class=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></a> </li><?php endif;    ?>

</ul>
<br/><?php
}else{
    ?>
<ul class="subsubsub">
    <li> </li>
</ul><?php
}
<?php
if (isset($_GET['themeselection'])) {
    
    if ($_GET['themeselection'] === 'invoice') {
        include_once('wf_template_customize_for_invoice.php');
    }
    if ($_GET['themeselection'] === 'packing_slip') {
        include_once('wf_template_customize_for_packing_slip.php');
    }
    if ($_GET['themeselection'] === 'delivery_note') {
        include_once('wf_template_customize_for_delivery_note.php');
    }
    if ($_GET['themeselection'] === 'shipping_label') {
        include_once('wf_template_customize_for_shipping_label.php');
    }
    if ($_GET['themeselection'] === 'dispatch_label') {
        include_once('wf_template_customize_for_dispatch_label.php');
    }
    
} else {

    include_once('wf_template_customize_for_invoice.php');
    ?>
    <style>
        /* The Modal (background) */
        .wf_modal {
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
        }

        /* Modal Content/Box */
        .wf_modal-content {
            background-color: #fefefe;
            margin: 1% auto; /* 15% from the top and centered */
            padding: 10px;
            border: 1px solid #888;
            width: 90%; /* Could be more or less, depending on screen size */
        }

    </style>
    <br>
    <form method='post'>
        <!-- The Modal -->
        <div id="myModal" class="wf_modal">
            <br><br>
            <br><br>
            <center><h1 class="wf_modal-content" ><strong>Customize Templates</strong></h1
                <!-- Modal content -->
                <div class="wf_modal-content" style="margin:unset;">


                    <table width="100%">
                        <tr>
                            <td style="width:22%;padding-left:2%;">
                                <div class="theme-browser rendered" style="width:100%;">
                                    <div class="themes wp-clearfix" >
                                        <div class="theme" tabindex="0" style="width:80%;">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice'); ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:150px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice_custom2.png' ?>" height="100%" width="100%" alt=""> 
                                                </div>

                                                <span class="more-details" id=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></span></a>
                                            <h2 class="theme-name" id="" style="height:50%" >

                                            </h2>
                                        </div>	
                                    </div>
                                </div>
                            </td>

                            <td style="width:20%;text-align: center;"">
                                <div class="theme-browser rendered" style="width:100%;">
                                    <div class="themes wp-clearfix">
                                        <div class="theme" tabindex="0" style="width:80%;">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=shipping_label'); ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:150px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/shipping_label_custom.png' ?>" height="100%" width="100%" alt=""> 
                                                </div>

                                                <span class="more-details" id=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></span></a>
                                            <h2 class="theme-name" id="" style="height:50%" >
                                                <b></b>
                                            </h2>
                                        </div>	
                                    </div>
                                </div>
                            </td>


                            <td style="width:20%;text-align: center;">
                                <div class="theme-browser rendered" style="width:100%;">
                                    <div class="themes wp-clearfix">
                                        <div class="theme" tabindex="0" style="width:80%;">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=packing_slip'); ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:150px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/Packing_slip_custom3.png' ?>" height="100%" width="100%" alt=""> 
                                                </div>

                                                <span class="more-details" id=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></span></a>
                                            <h2 class="theme-name" id="" style="height:50%" >

                                            </h2>
                                        </div>	
                                    </div>
                                </div>
                            </td>
                            <td style="width:20%;text-align: center;"">
                                <div class="theme-browser rendered" style="width:100%;">
                                    <div class="themes wp-clearfix">
                                        <div class="theme" tabindex="0" style="width:80%;">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=delivery_note'); ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:150px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/delivery_note_custom1.png' ?>" height="100%" width="100%" alt=""> 
                                                </div>

                                                <span class="more-details" id=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></span></a>
                                            <h2 class="theme-name" id="" style="height:50%" >
                                                <b></b>
                                            </h2>
                                        </div>	
                                    </div>
                                </div>
                            </td>
                            <td style="width:20%;text-align: center;"">
                                <div class="theme-browser rendered" style="width:100%;">
                                    <div class="themes wp-clearfix">
                                        <div class="theme" tabindex="0" style="width:80%;">
                                            <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=dispatch_label'); ?>" style="color:white;">
                                                <div class="theme-screenshot" style="height:150px;">
                                                    <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/dispatch_label_custom3.png' ?>" height="100%" width="100%" alt=""> 
                                                </div>

                                                <span class="more-details" id=""><?php _e('Customize', 'wf-woocommerce-packing-list'); ?></span></a>
                                            <h2 class="theme-name" id="" style="height:50%" >
                                                <b></b>
                                            </h2>
                                        </div>	
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>

                            <td style="width:22%;padding-left:2%;">
                                <a href = "<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice'); ?>" style="width:80%;text-align: center;" class="button button-primary" ><?php _e('Invoice', 'wf-woocommerce-packing-list'); ?></a>
                            </td>
                            <td style="width:20%;">
                                <a href = "<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=shipping_label'); ?>" style="width:80%;text-align: center;" class="button button-primary" ><?php _e('Shipping Label', 'wf-woocommerce-packing-list'); ?> </a>
                            </td>
                            <td style="width:20%;">
                                <a href = "<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=packing_slip'); ?>" style="width:80%;text-align: center;" class="button button-primary" ><?php _e('Packing Slip', 'wf-woocommerce-packing-list'); ?> </a>
                            </td>
                            <td style="width:20%;">
                                <a href = "<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=delivery_note'); ?>" style="width:80%;text-align: center;" class="button button-primary" ><?php _e('Delivery Note', 'wf-woocommerce-packing-list'); ?></a>
                            </td>
                            <td style="width:20%;">
                                <a href = "<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=dispatch_label'); ?>" style="width:80%;text-align: center;" class="button button-primary" ><?php _e('Dispatch Label', 'wf-woocommerce-packing-list'); ?></a>
                            </td>
                        </tr>
                    </table>
                </div>

        </div>

    </form><?php }
?>
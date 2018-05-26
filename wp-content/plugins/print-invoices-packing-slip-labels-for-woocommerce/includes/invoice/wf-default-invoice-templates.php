<div class="themes wp-clearfix"><?php
    for ($i = 1; get_option('wf_invoice_template_new_' . $i, false) != ''; $i++) {
        if (get_option('wf_invoice_template_' . $i . 'custom', false) == false && get_option('wf_invoice_active_key') === 'wf_invoice_template_new_' . $i) {
            ?>
            <div class="theme" tabindex="0">
                <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_new_' . $i); ?>" style="color:white;">
                    <div class="theme-screenshot" style="height:220px;">
                        <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice_new' . $i . '.jpg' ?>" alt=""> 
                    </div>
                    <span class="more-details more-details-btn" id="">Customize</span></a>
                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_new_' . $i) {
                        echo '<div class="pull-right" style="padding:4px 10px;color:#26B99A;font-size:12px;font-weight:normal;" >Active<span class="dashicons dashicons-yes" style="font-size:25px;" ></span></div> ';
                    } else {
                        ?>
                        <a class="btn btn-sm btn-info pull-right button-primary" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_new_' . $i) ?>">Activate</a><?php }
                    ?>
                    Elegant</h2>
            </div><?php
        }
    }
    for ($i = 2; get_option('wf_invoice_template_' . $i) != ''; $i++) {
        
        if (get_option('wf_invoice_template_' . $i . 'custom') == false && get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
            ?>
            <div class="theme" tabindex="0">
                <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_' . $i); ?>" style="color:white;">
                    <div class="theme-screenshot" style="height:220px;">
                        <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice' . $i . '.png' ?>" alt=""> 
                    </div>
                    <span class="more-details more-details-btn" id="">Customize</span></a>
                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
                        echo '<div class="pull-right" style="padding:4px 10px;color:#26B99A;font-size:12px;font-weight:normal;" >Active<span class="dashicons dashicons-yes" style="font-size:25px;" ></span></div> ';
                    } else {
                        ?>
                        <a class="btn btn-sm btn-info pull-right button-primary" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_' . $i) ?>">Activate</a><?php
                    }

                    if ($i == 1) {
                        echo 'Classic';
                    } else if ($i == 2) {
                        echo 'Radiant';
                    } else if ($i === 3) {
                        echo 'Refined';
                    }
                    ?>
                </h2>
            </div><?php
        }
    }
    ?><?php
    for ($i = 1; get_option('wf_invoice_template_new_' . $i) != ''; $i++) {
        if (get_option('wf_invoice_template_' . $i . 'custom') == false && get_option('wf_invoice_active_key') != 'wf_invoice_template_new_' . $i) {
            ?>
            <div class="theme" tabindex="0">
                <a href="#" style="color:white;">

                    <div class="theme-screenshot" style="height:220px;">
                        <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice_new' . $i . '.jpg' ?>" alt=""> 
                    </div>
                    <span class="more-details more-details-btn" id="">Customize<br/>
                    <small style="color:white;">(Pro version) </small>
                    </span>
                </a>
                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_new_' . $i) {
                        echo '<div class="pull-right" style="padding:5px 10px;color:#26B99A;font-size:12px;font-weight:normal;" >Active<span class="dashicons dashicons-yes pull-right" style="font-size:25px;" ></span></div> ';
                    } else {
                        ?>
                        <!--<a class="btn btn-sm btn-info pull-right button-primary" href="<?php //echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_new_' . $i) ?>">Activate</a>--><?php }
                    ?>Elegant </h2>
            </div><?php
        }
    }

    for ($i = 1; get_option('wf_invoice_template_' . $i) != ''; $i++) {
        
        if (get_option('wf_invoice_template_' . $i . 'custom') == false && get_option('wf_invoice_active_key') != 'wf_invoice_template_' . $i) {
            ?>
            <div class="theme" tabindex="0">
                <a href="<?php if($i==1) { echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_' . $i);}else{echo '#';} ?>" style="color:white;">

                    <div class="theme-screenshot" style="height:220px;">
                        <img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice' . $i . '.png' ?>" alt=""> 
                    </div>
                    <span class="more-details more-details-btn" id="">Customize<br/><?php if($i!=1){ ?>
                        <small style="color:white;">(Pro version) </small><?php } ?>
                     </span>
                </a>
                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
                        echo '<div class="pull-right" style="padding:5px 10px;color:#26B99A;font-size:12px;font-weight:normal;" >Active<span class="dashicons dashicons-yes pull-right" style="font-size:25px;" ></span></div> ';
                    } else {
                        ?>
                        <!--<a class="btn btn-sm btn-info pull-right button-primary" href="<?php //echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_' . $i) ?>">Activate</a>--><?php
                    }
                    if ($i == 1) {
                        echo 'Classic';
                    } else if ($i == 2) {
                        echo 'Radiant';
                    } else if ($i == 3) {
                        echo 'Refined';
                    }
                    ?> </h2>
            </div><?php
        }
    }
    ?>

</div>
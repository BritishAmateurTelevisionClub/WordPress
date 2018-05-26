<?php
    for ($i = 1; get_option('wf_invoice_template_' . $i) != ''; $i++) {
        if (get_option('wf_invoice_template_' . $i . 'custom') && get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
            ?><?php if (get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i){
                ?>
            <div class="theme" style="box-shadow: 0 8px 6px -6px black;" tabindex="0"><?php 
                }
                else
                {
                ?>
                <div class="theme" tabindex="0"><?php
                }
                ?>
                <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_' . $i); ?>" style="color:white;">
                    <div class="theme-screenshot" style="height:220px;"><?php
                        if (get_option('wf_invoice_template_' . $i . 'from')) {
                            $check_data = get_option('wf_invoice_template_' . $i . 'from');

                            if ($check_data === 'wf_invoice_template_1') {
                                $j = 1;
                            } else if ($check_data === 'wf_invoice_template_2') {
                                $j = 2;
                            } else if ($check_data === 'wf_invoice_template_3') {
                                $j = 3;
                            } else {
                                $j = '';
                            }
                        } else {
                            $j = '';
                        }
                        ?>

                        <div style="background: white;width:100%;height:100%;">
                            <center><label style='color:black;padding:6px;'><?php echo 'Customized invoice ' ?></label></center>
                            <span class="dashicons dashicons-awards" style="position:absolute;top: 30%;left: 25%;font-size: 100px;color:#26B99A;"></span></div>


                    </div>

                    <span class="more-details more-details-btn" id=""> Customize</span> </a>
                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
                        echo '<div class="pull-right" style="color:#26B99A;font-size:13px;font-weight:normal;" ><div style="width:20px;height:20px;border-radius:50%;background:#00B196;"> <span class="dashicons dashicons-yes" style="font-size:20px;color:white;" ></span></div></div> ';
                    } else {
                        ?>
                        <a class="btn btn-sm btn-info pull-right button-primary" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_' . $i) ?>">Activate</a><?php }
                    
                    $thisindex_template_name = '';
                    $thisindex_template_name = get_option('wf_invoice_template_'.$i.'name');
                                                                                              
                    if($thisindex_template_name!=='' && $thisindex_template_name!== false){
                        echo $thisindex_template_name;
                        
                    }else{
                        echo __('Invoice ', 'wf-woocommerce-packing-list').($i+1); 
                        
                    }
                    
                    ?>
                    </h2>
            </div><?php
        }
    }
    ?><?php
    for ($i = 1; get_option('wf_invoice_template_' . $i) != ''; $i++) {
        if (get_option('wf_invoice_template_' . $i . 'custom') && get_option('wf_invoice_active_key') != 'wf_invoice_template_' . $i && get_option('wf_invoice_template_' . $i . 'deactive', 'yes') === 'yes') {
            ?>
            <div class="theme" tabindex="0">
                <a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=invoice&theme=wf_invoice_template_' . $i); ?>" style="color:white;">
                    <div class="theme-screenshot" style="height:220px;"><?php
                        if (get_option('wf_invoice_template_' . $i . 'from')) {
                            $check_data = get_option('wf_invoice_template_' . $i . 'from');

                            if ($check_data === 'wf_invoice_template_1') {
                                $j = 1;
                            } else if ($check_data === 'wf_invoice_template_2') {
                                $j = 2;
                            } else if ($check_data === 'wf_invoice_template_3') {
                                $j = 3;
                            } else {
                                $j = '';
                            }
                        } else {
                            $j = '';
                        }
                        ?>
                <!--<img src="<?php echo WF_INVOICE_MAIN_ROOT_PATH . 'assets/images/invoice_cus_' . $j . '.png' ?>"  alt=""> -->
                        <div style="background: white;width:100%;height:100%">
                            <center><label style='color:black;padding:6px;'><?php echo 'Customized invoice ' ?></label></center>
                            <span class="dashicons dashicons-awards" style="position:absolute;top: 30%;left: 25%;font-size: 100px;color:#26B99A;"></span>
                        </div>

                    </div>

                    <span class="more-details more-details-btn"  id=""> Customize</span></a>
                <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&deactive=1&theme=wf_invoice_template_' . $i) ?>" onclick="return confirm('Confirm deletion of custom template<?php echo 'Invoice' . $i; ?>. Do you wish to continue?');" class="pull-right more-details" style="color:red;top:0px;right:0px;padding:3px;left:unset;text-align:right;background:none;vertical-align:-webkit-baseline-middle;text-shadow:none;"><span class="dashicons dashicons-no-alt"></span></a>

                <h2 class="theme-name" id="" style="height:50%" ><?php
                    if (get_option('wf_invoice_active_key') === 'wf_invoice_template_' . $i) {
                        echo '<div class="pull-right" style="padding:5px 10px;color:#26B99A;font-size:12px;font-weight:normal;" >Active<span class="dashicons dashicons-yes pull-right" style="font-size:25px;" ></span></div> ';
                    } else {
                        ?>
                        <a class="btn btn-sm btn-info pull-right button-primary" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&active_tab=invoice&theme=wf_invoice_template_' . $i) ?>">Activate</a><?php }
                    ?><?php
                            
                    $thisindex_template_name = '';
                    $thisindex_template_name = get_option('wf_invoice_template_'.$i.'name');
                                                                                              
                    if($thisindex_template_name !=='' && $thisindex_template_name!== false){
                        echo $thisindex_template_name;
                        
                    }else{
                        echo __('Invoice ', 'wf-woocommerce-packing-list').($i+1); 
                        
                    }
                        
                        ?>
                    </h2>
            </div><?php
        }
    }
    ?>
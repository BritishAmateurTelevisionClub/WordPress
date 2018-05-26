<?php

function pdf_stamper_license_menu() {
    echo '<div class="wrap">';
    echo '<h2>Product License</h2>';
    echo '<div id="poststuff"><div id="post-body">';

    $message = "";
    if (isset($_POST['activate_license'])) {
        $retData = wp_pdf_stamper_liceinse_verify($_POST['xiuyAmIn_wp_pdf_stamper_lic_key']);
        if ($retData['result'] == 'Success') {
            // something else is to be done to store the license key.
            update_option('xiuyAmIn_wp_pdf_stamper_lic_key', $_POST['xiuyAmIn_wp_pdf_stamper_lic_key']);
            $message .= '<p style="color: green; font-size: 16px; font-weight: bold;">Success!</p>';
            $message .= '<p style="color: green; font-size: 16px; font-weight: bold;">License key is valid! Product activated.</p>';
            $message .= '<p style="color: green; font-size: 16px; font-weight: bold;">You can now go to the <a href="admin.php?page=wp-pdf-stamper/wp_pdf_stamp1.php">settings menu</a> and complete the configuration of this plugin.</p>';
        } else {
            $message .= "License key is invalid!";
            $message .= "<br />" . $retData['msg'];
        }
    }
    if (isset($_POST['deactivate_license'])) {
        $retData = wp_pdf_stamper_deactivate_license($_POST['xiuyAmIn_wp_pdf_stamper_lic_key']);
        if ($retData['result'] == 'Success') {
            // Reset the license key
            update_option('xiuyAmIn_wp_pdf_stamper_lic_key', '');
            $message .= "License key deactivated!";
        } else {
            $message .= "License key deactivation failed!";
            $message .= "<br />" . $retData['msg'];
        }
    }
    if (!empty($message)) {
        echo '<div id="message" class="updated fade"><p><strong>';
        echo $message;
        echo '</strong></p></div>';
    }
    ?>
    <div class="postbox">
        <h3 class="hndle"><label for="title">License Details </label></h3>
        <div class="inside">

            <p>Please enter the license key for this product to activate it. You were given a license key when you purchased the <a href="http://www.tipsandtricks-hq.com/wp-pdf-stamper-plugin-2332" target="_blank">PDF Stamper plugin</a>.</p>
            <form action="" method="post">
                <table class="form-table">
                    <tr>
                        <th style="width:100px;"><label for="xiuyAmIn_wp_pdf_stamper_lic_key">License Key</label></th>
                        <td ><input class="regular-text" type="text" id="xiuyAmIn_wp_pdf_stamper_lic_key" name="xiuyAmIn_wp_pdf_stamper_lic_key"  value="<?php echo get_option('xiuyAmIn_wp_pdf_stamper_lic_key'); ?>" ></td>
                    </tr>
                </table>
                <p class="submit">
                    <?php
                    if (wp_pdf_stamper_is_license_valid()) {
                        echo '<div style="color: #043B14;background-color: #CCF4D6;border: 1px solid #059B53; padding: 10px; margin: 10px 0;">License key is active on this install.</div>';
                    } else {
                        echo '<input type="submit" name="activate_license" value="Activate" class="button-primary" />';
                    }
                    ?> 
                    <input type="submit" name="deactivate_license" value="Deactivate" class="button" />
                </p>
            </form>	

        </div></div>	
    <?php
    echo '</div></div>';
    echo '</div>';
}

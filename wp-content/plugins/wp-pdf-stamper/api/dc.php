<?php

include_once('../../../../wp-load.php');
if (isset($_REQUEST['secret_key'])) {
    if ($_REQUEST['secret_key'] == WP_LICENSE_MGR_SECRET_KEY) {
        update_option('xiuyAmIn_wp_pdf_stamper_lic_key', '');
        echo "Success";
    }
}

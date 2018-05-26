<?php
include_once('pdf_stamper_db_access.php');
include_once('wp_pdf_stamp_utility_functions.php');

function manage_stamped_files_menu() {
    echo '<div class="wrap">';
    echo '<h2>WP PDF Stamper - Manage Stamped Files</h2>';
    echo '<div id="poststuff"><div id="post-body">';

    if (!wp_pdf_stamper_is_license_valid()) {
        echo '</div></div>';
        echo '</div>';
        return; //Do not display the page if licese key is invalid	
    }

    show_stamped_files_menu();

    echo '</div></div>';
    echo '</div>';
}

function show_stamped_files_menu() {
    $wp_ps_config = PDF_Stamper_Config::getInstance();
    $message = '';

    if (isset($_POST['auto_bulk_delete_settings'])) {
        $wp_ps_config->setValue('auto_bulk_delete_enabled', ($_POST['auto_bulk_delete_enabled'] == '1') ? '1' : '' );
        $wp_ps_config->setValue('auto_bulk_delete_after_days', trim($_POST['auto_bulk_delete_after_days']));

        $wp_ps_config->saveConfig();
        echo '<div id="message" class="updated fade"><p>';
        echo 'Auto bulk delete options saved successfully';
        echo '</p></div>';
    }

    if (isset($_POST['bulk_delete'])) {
        $interval_val = $_POST['bulk_delete_hours'];
        $interval_unit = 'HOUR'; //MINUTE
        $cur_time = current_time('mysql');

        $cond = " DATE_SUB('$cur_time',INTERVAL '$interval_val' $interval_unit) > creation_time";
        $resultset = WpPdfStamperDbAccess::findAll(WP_PDF_STAMPED_FILES_TABLE_NAME, $cond);
        if ($resultset) {
            foreach ($resultset as $result) {
                $retVal = pdf_stamp_delete_stamped_record($result);
            }
            $message .= "The files have been deleted! The current timestamp value used was: " . $cur_time;
        } else {
            $message .= "Nothing to delete!";
        }
        echo '<div id="message" class="updated fade"><p><strong>';
        echo $message;
        echo '</strong></p></div>';
    }
    if (isset($_POST['Delete'])) {
        $cond = ' file_id = ' . $_POST['file_id'];
        $result = WpPdfStamperDbAccess::find(WP_PDF_STAMPED_FILES_TABLE_NAME, $cond);

        $domain_path = pdf_stamper_get_abs_path_from_src_file($result->file_url);
        $file_deleted = unlink($domain_path);
        $message = "Server deletion result: ";
        if ($file_deleted) {
            $message .= "Success! Selected stamped file successfully deleted from the server.";
        } else {
            $message .= "Failure! An error occured while trying to delete selected file from the server!";
        }

        $result = WpPdfStamperDbAccess::delete(WP_PDF_STAMPED_FILES_TABLE_NAME, $cond);
        $message .= "<br /><br />Database deletion result: ";
        if ($result) {
            $message .= "Success! Selected stamped file's record successfully deleted from the database.";
        } else {
            $message .= "Failure! An SQL error occurded while trying to delete the entry!";
        }
        echo '<div id="message" class="updated fade"><p><strong>';
        echo $message;
        echo '</strong></p></div>';
    }

    $limit = 50;
    if (isset($_POST['search'])) {
        $search_term = (string) $_POST["search_key"];
        $condition = "file_url like '%" . $search_term . "%' OR cust_name like '%" . $search_term . "%' OR cust_email like '%" . $search_term . "%'";
        $resultset = WpPdfStamperDbAccess::findAll(WP_PDF_STAMPED_FILES_TABLE_NAME, $condition, " file_id DESC ");
    } else {
        $orderby = " file_id DESC LIMIT " . $limit;
        $resultset = WpPdfStamperDbAccess::findAll(WP_PDF_STAMPED_FILES_TABLE_NAME, '', $orderby);
        $notes = "<br /><i> * The stamped files table displays records for a maximum of " . $limit . " recent stamped files. Please use the search function to find an older file.</i>";
    }

    $output = pdf_stamper_display_stamped_files_table($resultset);
    ?>

    <div class="postbox">
        <h3 class="hndle"><label for="title">Search for a stamped file</label></h3>
        <div class="inside">
            <br /><strong>Search for a stamped file by entering the file name (full or partial)</strong>
            <br /><br />
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

                <input name="search_key" type="text" size="30" value=""/>
                <div class="submit">
                    <input type="submit" name="search" value="Search" class="button" />
                </div>
            </form>
        </div></div>

    <div class="postbox">
        <h3 class="hndle"><label for="title">Automatic Bulk Delete Option</label></h3>
        <div class="inside">
            <br />
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                Enable Auto Bulk Delete Feature 
                <input name="auto_bulk_delete_enabled" type="checkbox"<?php if ($wp_ps_config->getValue('auto_bulk_delete_enabled') == '1') {
        echo 'checked="checked"';
    } ?> value="1"/>    
                <br />
                Automatically Delete all stamped files older than
                <input name="auto_bulk_delete_after_days" type="text" size="3" value="<?php echo $wp_ps_config->getValue('auto_bulk_delete_after_days'); ?>"/> Days
                <div class="submit">
                    <input type="submit" name="auto_bulk_delete_settings" value="Save Auto Bulk Delete Options" class="button" />
                </div>
            </form>
        </div></div>

    <div class="postbox">
        <h3 class="hndle"><label for="title">Bulk Delete Now</label></h3>
        <div class="inside">
            <br />
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                Delete all stamped files older than
                <input name="bulk_delete_hours" type="text" size="3" value=""/> Hours
                <div class="submit">
                    <input type="submit" name="bulk_delete" value="Bulk Delete Now" class="button" />
                </div>
            </form>
        </div></div>

    <?php
    echo $output;
    if (!empty($notes)) {
        echo $notes;
    }
}

function pdf_stamper_display_stamped_files_table($resultset) {
    $output = '
    <table class="widefat">
    <thead><tr>
    <th scope="col">File ID</th>
    <th scope="col">Creation Date & Time</th>
    <th scope="col">File URL</th>
    <th scope="col">Customer Name</th>
    <th scope="col">Customer Email</th>
    <th scope="col"></th>
    </tr></thead>
    <tbody>';

    $i = 0;
    if ($resultset) {
        foreach ($resultset as $result) {
            if ($i % 2 == 0) {
                $output .= "<tr style='background-color: #fff;'>";
                $i++;
            } else {
                $output .= "<tr style='background-color: #E9EDF5;'>";
                $i++;
            }
            $output .= '<td>' . $result->file_id . '</td>';
            $output .= '<td><strong>' . $result->creation_time . '</strong></td>';
            $output .= '<td><strong>' . $result->file_url . '</strong></td>';
            $output .= '<td>' . $result->cust_name . '</td>';
            $output .= '<td>' . $result->cust_email . '</td>';

            $output .= "<td><form method=\"post\" action=\"\" onSubmit=\"return confirm('Are you sure you want to delete this entry?');\">";
            $output .= "<input type=\"hidden\" name=\"file_id\" value=" . $result->file_id . " />";
            $output .= '<input style="border: none; background-color: transparent; padding: 0; cursor:pointer;" type="submit" name="Delete" value="Delete">';
            $output .= "</form>";
            $output .= "</td>";

            $output .= '</tr>';
        }
    } else {
        $output .= '<tr> <td colspan="4">No stamped files found</td> </tr>';
    }

    $output .= '</tbody></table>';
    $output .= '<br /><i> * The customer name, email will only be available if it was provided to the stamper via the dynamic variables at the time of stamping.</i>';

    return $output;
}
?>
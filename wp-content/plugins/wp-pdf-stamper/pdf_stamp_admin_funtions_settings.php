<?php

function stamper_admin_functions_menu() {

    echo '<div class="wrap">';
    echo '<h2>Admin Functions</h2>';
    echo '<div id="poststuff"><div id="post-body">';

    if (isset($_POST['pdfstamper_decode_value'])) {
        $to_decode = trim($_REQUEST['wp_pdf_stamp_encrypted_tag_value']);
        $decoded_val = base64_decode($to_decode);
        echo '<div id="message" class="updated fade"><p>';
        echo 'The decoded value of the encrypted tag is: ' . $decoded_val;
        echo '</p></div>';
    }
    ?>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">


        <div class="postbox">
            <h3 class="hndle"><label for="title">Decode Encrypted Tag Value</label></h3>
            <div class="inside">
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">Encrypted Tag Value</th>
                        <td><input type="text" name="wp_pdf_stamp_encrypted_tag_value" value="" size="50" />
                            <p class="description">Enter the encrypted tag value in the above field then hit the decode button below.</p>
                        </td>
                    </tr>

                </table>
                
                <div class="submit">
                    <input type="submit" class="button-primary" name="pdfstamper_decode_value" value=" Decode Value " />
                </div>
            </div></div>



    </form>
    <?php
    echo '</div></div>';
    echo '</div>';
}
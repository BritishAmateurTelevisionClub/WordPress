<?php

/* Avery Label Menu Page */

function reminder_template_page_html(){
?>
    <style>
    .pdf_field_list:after {
           clear: both;
    content: '';
    display: table;
    }
    .pdf_field_list li {
 float: left;
    margin-right: 10px;
    padding: 7px 10px;
    background: #b9b9b9;
    color: #FFF;
    cursor:pointer;
    }

    </style>
    <input type="text" class="selected_image" />
<input type="button" class="upload_image_button" value="Upload Image">
	<div class="wrap">
		<h1>Reminder Postal Template Editor</h1>
	</div>

    <h2>User field list (Click to add to document)</h2>

    <ul class="pdf_field_list">
        <li>nickname</li>
<li>first_name</li>
<li>last_name</li>
<li>locale</li>
<li>billing_first_name</li>
<li>billing_last_name</li>
<li>billing_address_1</li>
<li>billing_city</li>
<li>billing_state</li>
<li>billing_postcode</li>
<li>billing_country</li>
<li>billing_email</li>
<li>call_sign</li>
<li>shipping_methods</li>
<li>expire_date</li>
</ul>
<?php

$template = stripslashes(get_option( 'reminder_template', '' ));

wp_editor( $template, 'pdf_template', array(
    'wpautop'       => true,
    'media_buttons' => false,
    'textarea_name' => 'reminder_template',
    'textarea_rows' => 10,
    'teeny'         => true,
    'media_buttons' => true,
) );

?>
<button style="margin-top:10px;" class="button" data-template="reminder" id="save_template">Save Template</button>
<?php
}

function reminder_email_template_page_html(){
?>
    <style>
    .pdf_field_list:after {
           clear: both;
    content: '';
    display: table;
    }
    .pdf_field_list li {
 float: left;
    margin-right: 10px;
    padding: 7px 10px;
    background: #b9b9b9;
    color: #FFF;
    cursor:pointer;
    }

    </style>
	<div class="wrap">
		<h1>General Purpose Letter Template Editor</h1>
	</div>

    <h2>Email Subject</h2>
<input style="width:40%;padding:10px 10px;" id="email_subject" type="text" value="<?= get_option('email_subject') ?>" placeholder="Enter subject line for email">

    <h2>User field list (Click to add to document)</h2>

    <ul class="pdf_field_list">
        <li>nickname</li>
<li>first_name</li>
<li>last_name</li>
<li>locale</li>
<li>billing_first_name</li>
<li>billing_last_name</li>
<li>billing_address_1</li>
<li>billing_city</li>
<li>billing_state</li>
<li>billing_postcode</li>
<li>billing_country</li>
<li>billing_email</li>
<li>call_sign</li>
<li>shipping_methods</li>
<li>expire_date</li>
</ul>
<h2>Email Body</h2>
<?php

$template = stripslashes(get_option( 'reminder_email_template', '' ));

wp_editor( $template, 'pdf_template', array(
    'wpautop'       => true,
    'media_buttons' => false,
    'textarea_name' => 'reminder_template',
    'textarea_rows' => 10,
    'teeny'         => true,
    'media_buttons' => true,
) );

?>
<button style="margin-top:10px;" class="button" data-template="reminder_email" id="save_template">Save Template</button>
<?php
}
?>

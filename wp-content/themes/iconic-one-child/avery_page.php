<?php

/* Avery Label Menu Page */

add_action( 'admin_menu', 'pdf_template_page' );

function pdf_template_page() {
	add_menu_page( 'Template Editors', 'Template Editors', 'manage_options', 'template_editors', 'renewal_template_page_html', 'dashicons-tickets', 6  );
    add_submenu_page( 'template_editors', 'Renewal Postal Template', 'Renewal Postal Template', 'manage_options', 'renewal_template_editor', 'renewal_template_page_html', 6 );
    add_submenu_page( 'template_editors', 'Reminder Postal Template', 'Reminder Postal Template', 'manage_options', 'reminder_template_editor', 'reminder_template_page_html', 6 );
    add_submenu_page( 'template_editors', 'General Purpose Letter Template', 'General Purpose Letter Template', 'manage_options', 'reminder_email_template_editor', 'reminder_email_template_page_html', 6 );
}

add_action('wp_ajax_save_template', 'save_template' );

function save_template() {
    update_option($_POST['template_type'].'_template', $_POST['template']);
    if(!empty($_POST['email_subject'])) {
        update_option('email_subject', $_POST['email_subject']);
    }
}

add_action( 'admin_footer', 'save_template_javascript' ); // Write our JS below here

function save_template_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

        jQuery('<option>').val('avery-label').text('Avery Labels')
                .appendTo("select[name='action'], select[name='action2']");

        jQuery('<option>').val('renewal-letter').text('Renewal Letter')
                .appendTo("select[name='action'], select[name='action2']");

            $('#doaction').on('click', function(e) {
                if($('#bulk-action-selector-top').val() != 'delete') {
                    e.preventDefault();

                    if($('#bulk-action-selector-top').val() == 'avery-label') {
                        var ids = '';

                        jQuery('.bbp_participant:checked').each(function(index) {
                          ids = ids + ((index === 0) ? '' : ',') + $(this).parents('tr').find('.column-id').html() ;

                       });

                        window.open(
                            'https://batc.org.uk/wp-content/themes/iconic-one-child/avery_labels.php?user_id=' + ids,
                            '_blank'
                            );
                    } else if ($('#bulk-action-selector-top').val() == 'renewal-letter') {
                         var ids = '';

                        jQuery('.bbp_participant:checked').each(function(index) {
                          ids = ids + ((index === 0) ? '' : ',') + $(this).parents('tr').find('.column-id').html() ;

                       });

                        window.open(
                            'https://batc.org.uk/wp-content/themes/iconic-one-child/generate_pdf.php?user_id=' + ids,
                            '_blank'
                            );
                    }
                }
            })

        $('.avery_labels a').on('click', function() {
            var id = $(this).parents('tr').find('.column-id').html();

        })

        $('.pdf_field_list li').on('click', function() {

            var editor = tinyMCE.get('pdf_template');


            editor.execCommand('mceInsertContent', false, '{{' + $(this).html() + '}}' );

        })

        $('#save_template').on('click', function() {
            var data = {
			'action': 'save_template',
            'template_type' : $(this).data('template'),
			'template': tinyMCE.get('pdf_template').getContent({format : 'raw'})
		};

        if($('#email_subject').length > 0) {
            data['email_subject'] = $('#email_subject').val();

        }

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Template Saved')
		});
        })

	});
	</script> <?php
}

function renewal_template_page_html(){

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
		<h1>Renewal Postal Template Editor</h1>
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

    $template = stripslashes(get_option( 'pdf_template', '' ));

wp_editor( $template, 'pdf_template', array(
    'wpautop'       => true,
    'media_buttons' => false,
    'textarea_name' => 'pdf_template',
    'textarea_rows' => 10,
    'teeny'         => true,
    'media_buttons' => true,
) );

?>
<button style="margin-top:10px;" data-template="pdf" class="button" id="save_template">Save Template</button>
<?php
}
?>

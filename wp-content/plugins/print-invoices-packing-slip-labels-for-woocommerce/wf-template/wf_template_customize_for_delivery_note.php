<?php 
$invoice_data='';
if(isset($_GET['theme']) && !empty($_GET['theme']))
{
	$customize_data = get_option($_GET['theme']);
	$main_data_value = eh_theme_value_assign($_GET['theme']);
	$invoice_data = $_GET['theme'];

}
else
{
	if(get_option('wf_delivery_note_active_key'))
	{
		$customize_data = get_option(get_option('wf_delivery_note_active_key')); //active 
		$main_data_value = eh_theme_value_assign(get_option('wf_delivery_note_active_key'));
		$invoice_data = get_option('wf_delivery_note_active_key');
	}
	else
	{
		$customize_data = '<h3>Choose Template</h3>'; //active template want to customize
		$main_data_value = '0|0|0|';
	}
}
$acive_template = get_option('wf_delivery_note_active_key');
$default_active_value = get_option('wf_delivery_note_active_value');
$default_active_array = explode('|',$default_active_value);
$main_data_array = explode('|',$main_data_value);

if(isset($_POST['logo_save']))
{
	$main_data_array[0] = is_numeric($_POST['logowidth']) ? $_POST['logowidth'] : $default_active_array[0]; 
	$main_data_array[1] = is_numeric($_POST['logoheight']) ? $_POST['logoheight'] : $default_active_array[1];
	$main_data_array[2] = isset($_POST['wf_company_logo_switch']) ? 'yes' : 'no';
	$main_data_array[3] =  $_POST['company_logo_or_text'];
	$main_data_array[4] = isset($_POST['wf_invoice_number_switch']) ? 'yes' : 'no';
	$main_data_array[5] = is_numeric($_POST['wf_invoice_font']) ? $_POST['wf_invoice_font'] : $default_active_array[5];
	$main_data_array[6] =  $_POST['wf_invoice_number_font_weight'];
	$main_data_array[7] = !empty($_POST['wf_invoice_number_text']) ? $_POST['wf_invoice_number_text'] : $default_active_array[7];
	$main_data_array[8] = isset($_POST['wf_invoice_number_color_code_default']) ? $default_active_array[8] : $_POST['wf_invoice_number_color_code'];
	$main_data_array[9] = isset($_POST['wf_invoice_date_switch']) ? 'yes' : 'no';
	$main_data_array[10] = !empty($_POST['wf_invoice_date_format']) ? $_POST['wf_invoice_date_format'] : $default_active_array[10];
	$main_data_array[11] = is_numeric($_POST['wf_invoice_date_font']) ? $_POST['wf_invoice_date_font'] : $default_active_array[11];
	$main_data_array[12] = !empty($_POST['wf_invoice_date_text']) ? stripslashes($_POST['wf_invoice_date_text']) : $default_active_array[12];
	$main_data_array[13] = $_POST['wf_invoice_date_font_weight'];
	$main_data_array[14] = isset($_POST['wf_invoice_date_color_code_default']) ? $default_active_array[14] : $_POST['wf_invoice_date_color'];
	$main_data_array[15] = isset($_POST['wf_order_date_switch']) ? 'yes' : 'no';
	$main_data_array[16] = !empty($_POST['wf_order_date_format']) ? $_POST['wf_order_date_format'] : $default_active_array[16];
	$main_data_array[17] = is_numeric($_POST['wf_order_date_font']) ? $_POST['wf_order_date_font'] : $default_active_array[17];
	$main_data_array[18] = !empty($_POST['wf_order_date_text']) ? $_POST['wf_order_date_text'] : $default_active_array[18];
	$main_data_array[19] = $_POST['wf_order_date_font_weight'];
	$main_data_array[20] = isset($_POST['wf_order_date_color_code_default']) ? $default_active_array[20] : $_POST['wf_order_date_color'];
	$main_data_array[21] = isset($_POST['wf_from_address_switch']) ? 'yes' : 'no';
	$main_data_array[22] = !empty($_POST['wf_from_address_title']) ? $_POST['wf_from_address_title'] : $default_active_array[22];
	$main_data_array[23] = $_POST['wf_from_address_text_align'];
	$main_data_array[24] = isset($_POST['wf_from_address_color_code_default']) ? $default_active_array[24] : $_POST['wf_from_address_color_code'];
	$main_data_array[25] = isset($_POST['wf_billing_address_switch']) ? 'yes' : 'no';
	$main_data_array[26] = !empty($_POST['wf_billing_address_title']) ? $_POST['wf_billing_address_title'] : $default_active_array[26];
	$main_data_array[27] = $_POST['wf_billing_address_text_align'];
	$main_data_array[28] = isset($_POST['wf_billing_address_color_code_default']) ? $default_active_array[28] : $_POST['wf_billing_address_color_code'];
	$main_data_array[29] = isset($_POST['wf_shipping_address_switch']) ? 'yes' : 'no';
	$main_data_array[30] = !empty($_POST['wf_shipping_address_title']) ? $_POST['wf_shipping_address_title'] : $default_active_array[30];
	$main_data_array[31] = $_POST['wf_shipping_address_text_align'];
	$main_data_array[32] = isset($_POST['wf_shipping_address_color_code_default']) ? $default_active_array[32] : $_POST['wf_shipping_address_color_code'];
	if(in_array('email', $this->invoice_contactno_email)) {
		$main_data_array[33] = isset($_POST['wf_email_switch']) ? 'yes' : 'no';
		$main_data_array[34] = is_numeric($_POST['email_font']) ? $_POST['email_font'] : $default_active_array[34];
		$main_data_array[35] = !empty($_POST['email_text']) ? stripslashes($_POST['email_text']) : $default_active_array[35];
		$main_data_array[36] = $_POST['wf_email_text_align'];
		$main_data_array[37] = isset($_POST['wf_email_color_code_default']) ? $default_active_array[37] : $_POST['wf_email_color_code'];
	}
	if(in_array('contact_number', $this->invoice_contactno_email)) {
		$main_data_array[38] = isset($_POST['wf_tel_switch']) ? 'yes' : 'no';
		$main_data_array[39] = is_numeric($_POST['tel_font']) ? $_POST['tel_font'] : $default_active_array[39];
		$main_data_array[40] = !empty($_POST['tel_text']) ? stripslashes($_POST['tel_text']) : $default_active_array[40];
		$main_data_array[41] = $_POST['wf_tel_text_align'];
		$main_data_array[42] = isset($_POST['wf_tel_color_code_default']) ? $default_active_array[42] : $_POST['wf_tel_color_code'];
	}
	if(in_array('vat', $this->invoice_contactno_email)) {
		$main_data_array[43] = isset($_POST['wf_vat_switch']) ? 'yes' : 'no';
		$main_data_array[44] = is_numeric($_POST['vat_font']) ? $_POST['vat_font'] : $default_active_array[44];
		$main_data_array[45] = !empty($_POST['vat_text']) ? stripslashes($_POST['vat_text']) : $default_active_array[45];
		$main_data_array[46] = $_POST['wf_vat_text_align'];
		$main_data_array[47] = isset($_POST['wf_vat_color_code_default']) ? $default_active_array[47] : $_POST['wf_vat_color_code'];
	}
	if(in_array('ssn', $this->invoice_contactno_email)) {
		$main_data_array[48] = isset($_POST['wf_ssn_switch']) ? 'yes' : 'no';
		$main_data_array[49] = is_numeric($_POST['ssn_font']) ? $_POST['ssn_font'] : $default_active_array[49];
		$main_data_array[50] = !empty($_POST['ssn_text']) ? stripslashes($_POST['ssn_text']) : $default_active_array[50];
		$main_data_array[51] = $_POST['wf_ssn_text_align'];
		$main_data_array[52] = isset($_POST['wf_ssn_color_code_default']) ? $default_active_array[52] : $_POST['wf_ssn_color_code'];
	}
	$main_data_array[53] = isset($_POST['wf_tp_switch']) ? 'yes' : 'no';
	$main_data_array[54] = is_numeric($_POST['tp_font']) ? $_POST['tp_font'] : $default_active_array[54];
	$main_data_array[55] = !empty($_POST['tp_text']) ? stripslashes($_POST['tp_text']) : $default_active_array[55];
	$main_data_array[56] = $_POST['wf_tp_text_align'];
	$main_data_array[57] = isset($_POST['wf_tp_color_code_default']) ? $default_active_array[57] : $_POST['wf_tp_color_code'];
	
	$main_data_array[58] = isset($_POST['wf_tn_switch']) ? 'yes' : 'no';
	$main_data_array[59] = is_numeric($_POST['tn_font']) ? $_POST['tn_font'] : $default_active_array[59];
	$main_data_array[60] = !empty($_POST['tn_text']) ? stripslashes($_POST['tn_text']) : $default_active_array[60];
	$main_data_array[61] = $_POST['wf_tn_text_align'];
	$main_data_array[62] = isset($_POST['wf_tn_color_code_default']) ? $default_active_array[62] : $_POST['wf_tn_color_code'];
	
	$main_data_array[63] = isset($_POST['wf_product_switch']) ? 'yes' : 'no';
	$main_data_array[64] = isset($_POST['wf_head_back_color_code_default']) ? $default_active_array[64] : $_POST['wf_head_back_code'];
	$main_data_array[65] = isset($_POST['wf_head_front_color_code_default']) ? $default_active_array[65] : $_POST['wf_head_front_code'];
	$main_data_array[66] = $_POST['wf_get_text_align_head'];
	$main_data_array[67] = isset($_POST['wf_body_front_color_code_default']) ? $default_active_array[67] : $_POST['wf_body_front_code'];
	$main_data_array[68] = $_POST['wf_get_text_align_body'];
	$main_data_array[69] = !empty($_POST['sku_text']) ? stripslashes($_POST['sku_text']) : $default_active_array[69];
	$main_data_array[70] = !empty($_POST['product_text']) ? stripslashes($_POST['product_text']) : $default_active_array[70];
	$main_data_array[71] = !empty($_POST['qty_text']) ? stripslashes($_POST['qty_text']) : $default_active_array[71];
	$main_data_array[72] = !empty($_POST['total_text']) ? stripslashes($_POST['total_text']) : $default_active_array[72];
	$main_data_array[73] = !empty($_POST['img_text']) ? stripslashes($_POST['img_text']) : $default_active_array[73];
	$main_data_array[74] = !empty($_POST['tw_text']) ? stripslashes($_POST['tw_text']) : $default_active_array[74];
	$main_data_array[75] = !empty($_POST['logo_extra_details']) ? stripslashes(str_replace('|','-*-',$_POST['logo_extra_details'])) : $default_active_array[80];
	$main_data_array[76] = is_numeric($_POST['logo_extra_details_font']) ? $_POST['logo_extra_details_font'] : $default_active_array[81];

	$my_main_data = implode('|',$main_data_array);
	eh_data_save_customize($acive_template,$my_main_data);

}

function eh_data_save_customize($acive_template,$my_main_data)
{
	$i=1;
	if(isset($_GET['theme']) && !empty($_GET['theme']))
	{
		if(get_option($_GET['theme'].'custom') === 'yes')
		{
			update_option($_GET['theme'].'value',$my_main_data);
			update_option('wf_delivery_note_active_key',$_GET['theme']);
		}
		else
		{

			for ($f=1; get_option('wf_delivery_note_template_'.$f) !='' ;$f++)
			{
				$i +=1;
			}
			update_option('wf_delivery_note_template_'.$i,get_option($_GET['theme']));
			update_option('wf_delivery_note_template_'.$i.'value',$my_main_data);
			update_option('wf_delivery_note_template_'.$i.'custom', 'yes');
			update_option('wf_delivery_note_template_'.$i.'from', $_GET['theme']);
			update_option('wf_delivery_note_active_key','wf_delivery_note_template_'.$i);
			wp_redirect(admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=delivery_note&theme=wf_delivery_note_template_'.$i));
		}
	}
	else
	{
		if(get_option($acive_template.'custom') === 'yes')
		{
			update_option($acive_template.'value',$my_main_data);
			update_option('wf_delivery_note_active_key',$acive_template);
		}
		else
		{
			for ($f=1; get_option('wf_delivery_note_template_'.$f) !='';$f++)
			{
				$i +=1;
			}
			update_option('wf_delivery_note_template_'.$i,get_option($acive_template));
			update_option('wf_delivery_note_template_'.$i.'value',$my_main_data);
			update_option('wf_delivery_note_template_'.$i.'custom', 'yes');
			update_option('wf_delivery_note_template_'.$i.'from', $acive_template);
			update_option('wf_delivery_note_active_key','wf_delivery_note_template_'.$i);
			wp_redirect(admin_url('admin.php?page=wf_template_customize_for_invoice&&themeselection=delivery_note&theme=wf_delivery_note_template_'.$i));
		}

	}
}
function eh_theme_value_assign($given_template)
{
	if(get_option($given_template.'value') === false)
	{
		return get_option('wf_delivery_note_active_value');
	}
	
	else
	{
		return get_option($given_template.'value');
	}
}

?>

<style type="text/css" >

	.switch {
		position: relative;
		display: inline-block;
		width: 34px;
		height: 21px;
	}
	.clickable
	{
		cursor: pointer;
	}
	.switch input {display:none;}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 21px;
		width: 21px;
		right:12px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked + .slider {
		background-color: #0085ba;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #0085ba;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(12px);
		-ms-transform: translateX(12px);
		transform: translateX(12px);
	}
	.panel-body{
		background: #F2F5F7;
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 50px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
	.tooltips {
		position: relative;
		display: inline-block;
		border-bottom: 1px dotted black;
	}

	.tooltips .tooltiptext {
		visibility: hidden;
		width: 120px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;
		
		/* Position the tooltip */
		position: absolute;
		z-index: 1;
		top: 100%;
		left: 50%;
		margin-left: -60px;
	}

	.tooltips:hover .tooltiptext {
		visibility: visible;
	}
</style>
<script>
	$(document).ready(function () {
    $(document).on('mouseenter', '.x_content', function () {
        $(this).find(":button").show();
    }).on('mouseleave', '.x_content', function () {
        $(this).find(":button").hide();
    });

});
</script>

            <ul class="subsubsub">
                <li><a style="color: #0073aa;" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=delivery_note'); ?>" class=""><?php _e('Settings','wf-woocommerce-packing-list'); ?></a> | </li>
                <li><a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=delivery_note&theme=').get_option('wf_delivery_note_active_key'); ?>" class="current"><?php _e('Customize','wf-woocommerce-packing-list'); ?></a></li>
            </ul>
            

<form method="post" action="">
	<div class="container bodyclass">
		<div class="main_container">

			<!-- page content -->
			<div class="right_col" role="main">
				<div class=""><br>
					
					<div class="clearfix"></div>

					<div class="">
						
						<div class="col-md-8 col-sm-8 col-xs-12" style="background: white;padding-top: 10px;">
							<div class="x_panel">
										<div class="x_content" >
																					<!-- start accordion -->
											<div class="accordion"  style="min-height: 28cm;" id="my_new_invoice" ><?php 

												$customize_data = str_replace("[invoice main height and width]", 'height:100%; width:100%;',$customize_data);
												$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/font-awesome/css/font-awesome.css' rel='stylesheet'>", '',$customize_data);
												$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/css/custom.min.css' rel='stylesheet'>", '',$customize_data);
												$customize_data = str_replace("<link href='[wf link]assets/new_invoice_css_js/dist/css/bootstrap.css' rel='stylesheet'>", '',$customize_data);
												
												$customize_data = str_replace('[company name]',$this->wf_packinglist_get_companyname() ? $this->wf_packinglist_get_companyname() : 'Company Name', $customize_data);
												
												$customize_data = str_replace('[company1 name]','', $customize_data);

												if ($main_data_array[2] === 'no')
												{
													$customize_data 		= str_replace('[company logo visible]','display:none;', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[company logo visible]','', $customize_data);
												}
												
												if ($main_data_array[3] === 'logo')
												{
													if($this->wf_packinglist_get_logo('print_packing_list') != '') { 

														$customize_data = str_replace('[image url for company logo]',$this->wf_packinglist_get_logo('print_invoice'), $customize_data);
													}
													else
													{
														$customize_data = str_replace('[image url for company logo]',WF_INVOICE_MAIN_ROOT_PATH.'assets/images/logo.png', $customize_data);
													}
													$customize_data = str_replace('[company text show hide]','display:none;', $customize_data);
													$customize_data = str_replace('[logo width]',$main_data_array[0], $customize_data);
													$customize_data = str_replace('[logo height]',$main_data_array[1], $customize_data);
												}
												else
												{
													$customize_data = str_replace('[image url for company logo]','', $customize_data);
													$customize_data = str_replace('[company text show hide]','', $customize_data);
													$customize_data = str_replace('[logo width]','', $customize_data);
													$customize_data = str_replace('[logo height]','', $customize_data);

												}
												if ($main_data_array[4] === 'no')
												{
													$customize_data 		= str_replace('[invoice number switch]','display:none;', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[invoice number switch]','', $customize_data);
												}
												if($main_data_array[8] != 'default')
												{
													$customize_data 		= str_replace('[invoice_number_color]','color:#'.$main_data_array[8].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[invoice_number_color]','', $customize_data);

												}
												$customize_data 		= str_replace('[invoice number prob]','font-size:'.$main_data_array[5].'px;', $customize_data);
												$customize_data 		= str_replace('[invoice font weight]','font-weight:'.$main_data_array[6].';', $customize_data);
												if($main_data_array[9] === 'no' )
												{
													$customize_data 		= str_replace('[invoice date show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[invoice date show hide]','', $customize_data);
												}
												$customize_data 		= str_replace('[invoice date font size]','font-size:'.$main_data_array[11].'px;', $customize_data);
												$customize_data 		= str_replace('[invoice Date label text]',$main_data_array[12], $customize_data);
												$customize_data 		= str_replace('[invoice date label font weight]','font-weight:'.$main_data_array[13].';', $customize_data);

												if($main_data_array[14] != 'default')
												{
													$customize_data 		= str_replace('[invoice date color code]','color:#'.$main_data_array[14].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[invoice date color code]','', $customize_data);
												}
												if($main_data_array[15] === 'no' )
												{
													$customize_data 		= str_replace('[order date show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[order date show hide]','', $customize_data);
													
												}
												$customize_data 		= str_replace('[invoice return policy hide]','display:none;',$customize_data);
												$customize_data 		= str_replace('[payment method show hide]','',$customize_data);
												$customize_data = str_replace('[invoice head font size]','16', $customize_data);
												$customize_data = str_replace('[invoice name]',$main_data_array[7], $customize_data);
												$customize_data = str_replace('[invoice number]','123456', $customize_data);
												$customize_data = str_replace('[order date title size]','16', $customize_data);
												$customize_data = str_replace('[invoice created date]',date($main_data_array[10],strtotime('now')), $customize_data);
												$customize_data = str_replace('[order date label]',$main_data_array[18], $customize_data);
												$customize_data = str_replace('[order date]',date($main_data_array[16],strtotime('now')), $customize_data);
												$customize_data 		= str_replace('[order date font size]','font-size:'.$main_data_array[17].'px;', $customize_data);

												$customize_data 		= str_replace('[order date label font weight]','font-weight:'.$main_data_array[19].';', $customize_data);
												if($main_data_array[20] != 'default')
												{
													$customize_data 		= str_replace('[order date color code]','color:#'.$main_data_array[20].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[order date color code]','', $customize_data);
												}

												if($main_data_array[21] === 'no' )
												{
													$customize_data 		= str_replace('[from address show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[from address show hide]','', $customize_data);
													
												}
												$customize_data 		= str_replace('[from address label]', $main_data_array[22], $customize_data);
												
												$customize_data 		= str_replace('[from address left right]','text-align:'.$main_data_array[23].';', $customize_data);

												if($main_data_array[24] != 'default')
												{
													$customize_data 		= str_replace('[from address text color]','color:#'.$main_data_array[24].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[from address text color]','', $customize_data);

												}

												$customize_data = str_replace('[from address font size]','14', $customize_data);
												$customize_data = str_replace('[from address]','Name<br>Company name<br>Address1<br>Address2<br>State<br>Country', $customize_data);
												$customize_data = str_replace('[billing address title size]','14', $customize_data);
												$customize_data = str_replace('[billing address label]',$main_data_array[26], $customize_data);

												if($main_data_array[25] === 'no' )
												{
													$customize_data 		= str_replace('[billing address show hide]','display:none;', $customize_data);

												}
												else
												{
													$customize_data 		= str_replace('[billing address show hide]','', $customize_data);

												}

												$customize_data 		= str_replace('[billing address left right]','text-align:'.$main_data_array[27].';', $customize_data);

												if($main_data_array[28] != 'default')
												{
													$customize_data 		= str_replace('[billing address text color]','color:#'.$main_data_array[28].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[billing address text color]','', $customize_data);

												}
												$customize_data 		= str_replace('[invoice extra field font size]','font-size:'.$main_data_array[76].'px;', $customize_data);
												if($main_data_array[75] != 'none')
												{
													$customize_data 		= str_replace('[Extra data below logo]',str_replace('-*-','|',$main_data_array[75]) , $customize_data);
													$customize_data 		= str_replace('[wf extra filed show hide]','', $customize_data);



												}
												else
												{
													$customize_data 		= str_replace('[wf extra filed show hide]','', $customize_data);

													$customize_data 		= str_replace('[Extra data below logo]','' , $customize_data);
												}



												$customize_data = str_replace('[billing address font size]','14', $customize_data);
												$customize_data = str_replace('[billing address data]','Name<br>Company name<br>Address1<br>Address2<br>State<br>Country<br>', $customize_data);

												$customize_data 		= str_replace('[email label]', $main_data_array[35], $customize_data);
												$customize_data 		= str_replace('[email address]', 'info@invoice.com' , $customize_data); 
												
												if(in_array('email', $this->invoice_contactno_email)) {

													if($main_data_array[33] === 'no' )
													{
														$customize_data 		= str_replace('[wf email show hide]','display:none;', $customize_data);

													}
													else
													{
														$customize_data 		= str_replace('[wf email show hide]','', $customize_data);
														$customize_data 		= str_replace('[wf email font size]', 'font-size:'.$main_data_array[34].'px;', $customize_data);

														$customize_data 		= str_replace('[wf email position set]','text-align:'.$main_data_array[36].';', $customize_data);

														if($main_data_array[37] != 'default')
														{
															$customize_data 		= str_replace('[wf_email color code default]','color:#'.$main_data_array[37].';', $customize_data);
														}
														else
														{
															$customize_data 		= str_replace('[wf_email color code default]','', $customize_data);

														}
														
													}
												}
												else
												{
													$customize_data 		= str_replace('[wf email show hide]','display:none;', $customize_data);

												}
												
												$customize_data 		= str_replace('[mobile label]', $main_data_array[40], $customize_data);
												$customize_data 		= str_replace('[mobile number]', '+123 4567890', $customize_data);  
												
												if(in_array('contact_number', $this->invoice_contactno_email)) {

													if($main_data_array[38] === 'no' )
													{
														$customize_data 		= str_replace('[wf tel show hide]','display:none;', $customize_data);

													}
													else
													{
														$customize_data 		= str_replace('[wf tel show hide]','', $customize_data);
														$customize_data 		= str_replace('[wf tel font size]', 'font-size:'.$main_data_array[39].'px;', $customize_data);

														$customize_data 		= str_replace('[wf tel position set]','text-align:'.$main_data_array[41].';', $customize_data);

														
														if($main_data_array[42] != 'default')
														{
															$customize_data 		= str_replace('[wf_tel color code default]','color:#'.$main_data_array[42].';', $customize_data);
														}
														else
														{
															$customize_data 		= str_replace('[wf_tel color code default]','', $customize_data);

														}
													}
												}
												else
												{
													$customize_data 		= str_replace('[wf tel show hide]','display:none;', $customize_data);

												}


												$customize_data = str_replace('[VAT label]', $main_data_array[45], $customize_data);
												$customize_data = str_replace('[VAT data]','4544123', $customize_data);
												$customize_data = str_replace('[SSN label]', $main_data_array[50], $customize_data);
												$customize_data = str_replace('[SSN data]','SSN54542S', $customize_data);
												


												if(in_array('vat', $this->invoice_contactno_email)) {

													
													if($main_data_array[43] === 'no' )
													{
														$customize_data 		= str_replace('[wf vat show hide]','display:none;', $customize_data);
														
													}
													else
													{
														$customize_data 		= str_replace('[wf vat show hide]','', $customize_data);
														$customize_data 		= str_replace('[wf vat font size]', 'font-size:'.$main_data_array[44].'px;', $customize_data);

														$customize_data 		= str_replace('[wf vat position set]','text-align:'.$main_data_array[46].';', $customize_data);


														if($main_data_array[47] != 'default')
														{
															$customize_data 		= str_replace('[wf_vat color code default]','color:#'.$main_data_array[47].';', $customize_data);
														}
														else
														{
															$customize_data 		= str_replace('[wf_vat color code default]','', $customize_data);

														}
													}
												}
												else
												{
													$customize_data 		= str_replace('[wf vat show hide]','display:none;', $customize_data);

												}
												
												

												if(in_array('ssn', $this->invoice_contactno_email)) {

													
													if($main_data_array[48] === 'no' )
													{
														$customize_data 		= str_replace('[wf ssn show hide]','display:none;', $customize_data);
														
													}
													else
													{
														$customize_data 		= str_replace('[wf ssn show hide]','', $customize_data);
														$customize_data 		= str_replace('[wf ssn font size]', 'font-size:'.$main_data_array[49].'px;', $customize_data);

														$customize_data 		= str_replace('[wf ssn position set]','text-align:'.$main_data_array[51].';', $customize_data);
														
														if($main_data_array[52] != 'default')
														{
															$customize_data 		= str_replace('[wf_ssn color code default]','color:#'.$main_data_array[52].';', $customize_data);
														}
														else
														{
															$customize_data 		= str_replace('[wf_ssn color code default]','', $customize_data);

														}
													}
												}
												else
												{
													$customize_data 		= str_replace('[wf ssn show hide]','display:none;', $customize_data);

												}

												$customize_data = str_replace('[shipping address title size]','16', $customize_data);

												if($main_data_array[29] === 'no' )
												{
													$customize_data 		= str_replace('[shipping address show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[shipping address show hide]','', $customize_data);
													
												}

												$customize_data 		= str_replace('[shipping address left right]','text-align:'.$main_data_array[31].';', $customize_data);

												if($main_data_array[32] != 'default')
												{
													$customize_data 		= str_replace('[shipping address text color]','color:#'.$main_data_array[32].';', $customize_data);
												}
												else
												{
													$customize_data 		= str_replace('[shipping address text color]','', $customize_data);

												}

												$customize_data = str_replace('[shipping address title]',$main_data_array[30], $customize_data);
												$customize_data = str_replace('[shipping address content size]','14', $customize_data);
												$customize_data = str_replace('[shipping address data]','Name<br>Company name<br>Address1<br>Address2<br>State<br>Country<br>', $customize_data);
												$customize_data = str_replace('[tracking label]',$main_data_array[55], $customize_data);
												$customize_data = str_replace('[tracking data]','DHL Express', $customize_data);
												$customize_data = str_replace('[tracking number label]',$main_data_array[60], $customize_data);
												$customize_data = str_replace('[tracking number data]','2786382178322', $customize_data);




												
												if($main_data_array[53] === 'no' )
												{
													$customize_data 		= str_replace('[wf tp show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[wf tp show hide]','', $customize_data);
													$customize_data 		= str_replace('[wf tp font size]', 'font-size:'.$main_data_array[54].'px;', $customize_data);

													$customize_data 		= str_replace('[wf tp position set]','text-align:'.$main_data_array[56].';', $customize_data);

													
													if($main_data_array[57] != 'default')
													{
														$customize_data 		= str_replace('[wf_tp color code default]','color:#'.$main_data_array[57].';', $customize_data);
													}
													else
													{
														$customize_data 		= str_replace('[wf_tp color code default]','', $customize_data);

													}
												}
												
												

												
												if($main_data_array[58] === 'no' )
												{
													$customize_data 		= str_replace('[wf tn show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[wf tn show hide]','', $customize_data);
													$customize_data 		= str_replace('[wf tn font size]', 'font-size:'.$main_data_array[59].'px;', $customize_data);

													$customize_data 		= str_replace('[wf tn position set]','text-align:'.$main_data_array[61].';', $customize_data);

													if($main_data_array[62] != 'default')
													{
														$customize_data 		= str_replace('[wf_tn color code default]','color:#'.$main_data_array[62].';', $customize_data);
													}
													else
													{
														$customize_data 		= str_replace('[wf_tn color code default]','', $customize_data);

													}
												}
												

												if($main_data_array[63] === 'no' )
												{
													$customize_data 		= str_replace('[wf product table show hide]','display:none;', $customize_data);
													
												}
												else
												{
													$customize_data 		= str_replace('[wf product table show hide]','', $customize_data);
													if($main_data_array[64] != 'default')
													{
														$customize_data 		= str_replace('[wf product table head color]','background:#'.$main_data_array[64].';', $customize_data);
														$customize_data 		= str_replace('[border-base-theme-color]',$main_data_array[64], $customize_data);
													}
													else
													{
														$customize_data 		= str_replace('[border-base-theme-color]','66BDA9', $customize_data);
														$customize_data 		= str_replace('[wf product table head color]','', $customize_data);
													}

													if($main_data_array[65] != 'default')
													{
														$customize_data 		= str_replace('[wf product table head text color]','color:#'.$main_data_array[65].';', $customize_data);
													}
													else
													{
														$customize_data 		= str_replace('[wf product table head text color]','', $customize_data);
													}
													$customize_data 		= str_replace('[wf product table text align]','text-align:'.$main_data_array[66].';', $customize_data);
													if($main_data_array[67] != 'default')
													{
														$customize_data 		= str_replace('[wf product table text color main]','color:#'.$main_data_array[67].';', $customize_data);
													}
													else
													{
														$customize_data 		= str_replace('[wf product table text color main]','', $customize_data);
													}

													$customize_data 		= str_replace('[wf product table body text align]','text-align:'.$main_data_array[68].';', $customize_data);
													$customize_data 		= str_replace('[product label text]', $main_data_array[70], $customize_data); 

														$customize_data 		= str_replace('[img label text]', $main_data_array[73], $customize_data);
														$customize_data 		= str_replace('[table colum img hide]','', $customize_data); 


														$customize_data 		= str_replace('[sku label text]', $main_data_array[69], $customize_data);
														$customize_data 		= str_replace('[table colum span]','', $customize_data); 

														$customize_data 		= str_replace('[table colum span hide]','', $customize_data);
														$customize_data 		= str_replace('[table quantity text]', $main_data_array[71], $customize_data);
														$customize_data 		= str_replace('[table toatl price text]', $main_data_array[72], $customize_data);

												}

												$customize_data 		= str_replace('[table total weight price text]', $main_data_array[74], $customize_data);

												$customize_data 		= str_replace('[table border top color]', $this->wf_packinglist_brand_color, $customize_data); 
												$customize_data 		= str_replace('[table background color]', $this->wf_packinglist_brand_color, $customize_data);
												
												$customize_data 		= str_replace('[table coloum brand color]', $this->wf_packinglist_brand_color, $customize_data); 
												$customize_data 		= str_replace('[table tax items]', '', $customize_data);
												$customize_data = str_replace('[table tfoot content size]','12', $customize_data);
												$customize_data = str_replace('[table coupon show hide]','display:none;', $customize_data);

													$customize_data = str_replace('[table tbody content value]','<tr><td class="qty"></td><td class="qty" style="text-align:unset;">Red_Ball</td><td class="desc" style="text-align:unset;">Jumbing LED Light Wall Ball</td><td class="unit" style="text-align:unset;">5</td><td class="unit" style="text-align:unset;">1 lbs</td><td class="total" style="text-align:unset;">$100.00</td></tr>', $customize_data);
												$customize_data = str_replace('[invoice barcode data]','', $customize_data);
												$customize_data = str_replace('[invoice return policy data]','30 days money back guarantee', $customize_data);
												$customize_data = str_replace('[invoice footor data]','XAdapter Customization', $customize_data);
												$customize_data = str_replace('','', $customize_data);

												$customize_data =	str_replace('[invoice extra firlds import]','',$customize_data);
												$customize_data =	str_replace('[invoice extra firlds import old one]','',$customize_data);
												$customize_data =	str_replace('[table total weight price text]','Total Weight',$customize_data);
												
												$customize_data = str_replace('[wffootor style]','bottom: 1px;',$customize_data);
												echo $customize_data;


												?>
												<div style="position: absolute;top:10px;"><button class="button button-secondary " style="display:none;" onclick="PrintElem('my_new_invoice','<?php echo WF_INVOICE_MAIN_ROOT_PATH; ?> ','show')"  type="button"><i class="fa fa-eye"></i></button> 
												<button class="button button-secondary " style="display:none;" onclick="PrintElem('my_new_invoice','<?php echo WF_INVOICE_MAIN_ROOT_PATH; ?> ','print')"  type="button"><i class="fa fa-print"></i></button> 
												
												</div>
											</div>
											<!-- end of accordion -->
										</div>
									</div>
								</div>


								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2><i class="fa fa-align-left"></i><?php _e('Field Attributes', 'wf-woocommerce-packing-list'); ?><small><?php echo 'Delivery Note '.substr($invoice_data, -1); ?></small>  </h2>
											<div class="pull-right">
												<span class="">																								
																				
													<div class="tooltips"><button id="logo_save" name="logo_save" class="button button-primary" style="font-size: 12px;" >Save & Activate</button>
														<span class="tooltiptext"><?php
															echo __('Save and Activate', 'wf-woocommerce-packing-list'); ?></span>
														</div>
														
															
														</span>
													</div>

													<div class="clearfix"></div>
												</div>
												
												
												<div class="x_content">
													<!-- start accordion -->
													<div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
										
										<div class="panel">
											<div class="panel-heading  clickable" >

												<label class="switch pull-right ">
													<input type="checkbox" id="wf_company_logo_switch" name="wf_company_logo_switch" value="company_logo"<?php echo $main_data_array[2] === 'no' ? '' : 'checked'; ?>/>  <div class="slider round"></div>
												</label>
												<h4 class="panel-title collapsed" role="tab" id="headingTwo1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Company Logo', 'wf-woocommerce-packing-list'); ?></h4>
											</div>


											<div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
												<div class="panel-body">
													<div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logoheight"><?php _e('Display', 'wf-woocommerce-packing-list'); ?></label>
															<select class="form-control clickable" id="company_logo_or_text" name="company_logo_or_text" ><?php if ($main_data_array[3] === 'logo')
																{
																	echo '<option value="logo" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>
																	<option value="name" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>';
																}
																else
																{
																	echo '<option value="logo" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>
																	<option value="name"  selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>';
																} ?>

															</select>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logowidth"><?php _e('Width', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logowidth" name="logowidth" placeholder="logo width" type="text" value="<?php echo $main_data_array[0]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logoheight"><?php _e('Height', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logoheight" name="logoheight" placeholder="logo height" type="text" value="<?php echo $main_data_array[1]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logo_extra_details"><?php _e('Extra Details', 'wf-woocommerce-packing-list'); ?></label>
															<textarea class="form-control" id="logo_extra_details" name="logo_extra_details" placeholder="Extra Details" ><?php echo str_replace('-*-','|',$main_data_array[75]); ?></textarea>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logo_extra_details_font"><?php _e('Font Size', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logo_extra_details_font" name="logo_extra_details_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[76]; ?>">
															<span class="input-group-addon">px</span>
														</div>
													</div>   

												</div>
											</div>
										</div>
										<div class="panel">

											<div class="panel-heading  clickable" >
												<label class="switch pull-right ">
													<input id="wf_invoice_number_switch" name="wf_invoice_number_switch" value="invoice_number" type="checkbox"<?php echo $main_data_array[4] === 'no' ? '' : 'checked'; ?>/> />  <div class="slider round"></div>
												</label>
												<h4 class="panel-title collapsed" role="tab" id="headingThree1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Order Number', 'wf-woocommerce-packing-list'); ?></h4>
											</div>

											<div id="collapseThree1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
												<div class="panel-body">
													<div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_invoice_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_invoice_font" name="wf_invoice_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[5]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_invoice_number_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_invoice_number_text" name="wf_invoice_number_text" placeholder="invoice text" type="text" value="<?php echo $main_data_array[7]; ?>">
															
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_invoice_number_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
															<select class="form-control clickable" id="wf_invoice_number_font_weight" name="wf_invoice_number_font_weight" ><?php if ($main_data_array[6] === 'normal')
																{
																	echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																	<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																}
																else
																{
																	echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																	<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																} ?>

															</select>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_invoice_number_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
															<input type="text" id="wf_invoice_number_color_code" name="wf_invoice_number_color_code" value="<?php  if($main_data_array[8] != 'default'){ echo $main_data_array[8]; }else { echo ''; } ?>" class="form-control jscolor" />

															<span class="input-group-addon"><input type="checkbox" id='wf_invoice_number_color_code_default' name='wf_invoice_number_color_code_default'<?php echo $main_data_array[8] === $default_active_array[8] ? 'checked' : ''; ?> />Default</span>
														</div>
													</div>   
												</div>
											</div>
										</div>

										<div class="panel">
											<div class="panel-heading  clickable" >
												<label class="switch pull-right ">
													<input type="checkbox" id="wf_invoice_date_switch" name="wf_invoice_date_switch" value="invoice_date"<?php echo $main_data_array[9] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
												</label>
												<h4 class="panel-title collapsed"  role="tab" id="headingFour1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseFour1" aria-expanded="false" aria-controls="collapseFour"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Invoice Date', 'wf-woocommerce-packing-list'); ?></h4>
											</div>

											<div id="collapseFour1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
												<div class="panel-body">
													<div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_invoice_date_format"><?php _e('Format', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_invoice_date_format" name="wf_invoice_date_format" placeholder="Date Format" type="text" value="<?php echo $main_data_array[10]; ?>">

															<span class="input-group-addon" >

																<select id = 'wf_date_format_selection' name='wf_date_format_selection' style="width:auto;height:auto;padding:0px;" >
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value="0">-SELECT-</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/Y'>d/m/Y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/y'>d/m/y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/y'>d/M/y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/Y'>d/M/Y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/Y'>m/d/Y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/y'>m/d/y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/y'>M/d/y</option>
																	<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/Y'>M/d/Y</option>
																</select>
															</span></div>
															<div class="input-group input-group-sm">
																<label class="input-group-addon" for="wf_invoice_date_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																<input class="form-control" id="wf_invoice_date_font" name="wf_invoice_date_font" placeholder="size" type="text" value="<?php echo $main_data_array[11]; ?>">
																<span class="input-group-addon">px</span>
															</div>
															<div class="input-group input-group-sm">
																<label class="input-group-addon" for="wf_invoice_date_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																<input class="form-control" id="wf_invoice_date_text" name="wf_invoice_date_text" placeholder="Invoice Date Text" type="text" value="<?php echo $main_data_array[12]; ?>">

															</div>
															<div class="input-group input-group-sm">
																<label class="input-group-addon" for="wf_invoice_date_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
																<select class="form-control clickable" id="wf_invoice_date_font_weight" name="wf_invoice_date_font_weight" ><?php if ($main_data_array[13] === 'normal')
																	{
																		echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																		<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																	}
																	else
																	{
																		echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																		<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																	} ?>
																</select>

															</div>
															<div class="input-group input-group-sm">
																<label class="input-group-addon" for="wf_invoice_date_color"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																<input type="text" id="wf_invoice_date_color" name="wf_invoice_date_color" value="<?php  if($main_data_array[14] != 'default'){ echo $main_data_array[14]; }else { echo ''; } ?>" class="form-control jscolor" />
																<span class="input-group-addon"><input type="checkbox" id='wf_invoice_date_color_code_default' name='wf_invoice_date_color_code_default'<?php echo $main_data_array[14] === $default_active_array[14] ? 'checked' : ''; ?> />Default</span>
															</div>

														</div>   
													</div>
												</div>
											</div>
											<div class="panel">
												<div class="panel-heading  clickable" >
													<label class="switch pull-right ">
														<input type="checkbox" id="wf_order_date_switch" name="wf_order_date_switch" value="order_date"<?php echo $main_data_array[15] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
													</label>
													<h4 class="panel-title collapsed"  role="tab" id="heading41" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse41" aria-expanded="false" aria-controls="collapse4"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Order Date', 'wf-woocommerce-packing-list'); ?></h4>
												</div>

												<div id="collapse41" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
													<div class="panel-body">
														<div>
															<div class="input-group input-group-sm">
																<label class="input-group-addon" for="wf_order_date_format"><?php _e('Format', 'wf-woocommerce-packing-list'); ?></label>
																<input class="form-control" id="wf_order_date_format" name="wf_order_date_format" placeholder="Date Format" type="text" value="<?php echo $main_data_array[16]; ?>">

																<span class="input-group-addon" >

																	<select id = 'wf_order_date_format_selection' name='wf_order_date_format_selection' style="width:auto;height:auto;padding:0px;" >
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value="0">-SELECT-</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/Y'>d/m/Y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/m/y'>d/m/y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/y'>d/M/y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='d/M/Y'>d/M/Y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/Y'>m/d/Y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='m/d/y'>m/d/y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/y'>M/d/y</option>
																		<option style="font-size: 10px;margin-bottom: 5px;margin-top: 5px;" value='M/d/Y'>M/d/Y</option>
																	</select>
																</span></div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_order_date_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_order_date_font" name="wf_order_date_font" placeholder="size" type="text" value="<?php echo $main_data_array[17]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_order_date_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_order_date_text" name="wf_order_date_text" placeholder="Order Date Text" type="text" value="<?php echo $main_data_array[18]; ?>">

																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_order_date_font_weight"><?php _e('Style', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control clickable" id="wf_order_date_font_weight" name="wf_order_date_font_weight" ><?php if ($main_data_array[19] === 'normal')
																		{
																			echo '<option value="normal" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																			<option value="bold" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																		}
																		else
																		{
																			echo '<option value="normal"  style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Normal</option>
																			<option value="bold" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Bold</option>';
																		} ?>
																	</select>

																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_order_date_color"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_order_date_color" name="wf_order_date_color" value="<?php  if($main_data_array[20] != 'default'){ echo $main_data_array[20]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_order_date_color_code_default' name='wf_order_date_color_code_default'<?php echo $main_data_array[20] === $default_active_array[20] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>   
														</div>
													</div>
												</div>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="from_address" id="wf_from_address_switch" name="wf_from_address_switch"<?php echo $main_data_array[21] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="headingFive1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseFive1" aria-expanded="false" aria-controls="collapseFive"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('From Address', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapseFive1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
														<div class="panel-body">
															<div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_from_address_title" name="wf_from_address_title" placeholder="From Address Title" type="text" value="<?php echo $main_data_array[22]; ?>">

																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_from_address_text_align" name="wf_from_address_text_align" ><?php if($main_data_array[23] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_from_address_color_code" name="wf_from_address_color_code" value="<?php  if($main_data_array[24] != 'default'){ echo $main_data_array[24]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_from_address_color_code_default' name='wf_from_address_color_code_default'<?php echo $main_data_array[24] === $default_active_array[24] ? 'checked' : ''; ?> />Default</span>
																</div>

															</div>   
														</div>
													</div>
												</div>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="billing_address" id="wf_billing_address_switch" name="wf_billing_address_switch"<?php echo $main_data_array[25] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="headingsix1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapsesix1" aria-expanded="false" aria-controls="collapsesix"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Billing Address', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapsesix1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsix">
														<div class="panel-body">
															<div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_billing_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_billing_address_title" name="wf_billing_address_title" placeholder="Billing title" type="text" value="<?php echo $main_data_array[26]; ?>">

																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_billing_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_billing_address_text_align" name="wf_billing_address_text_align" ><?php if($main_data_array[27] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_billing_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_billing_address_color_code" name="wf_billing_address_color_code" value="<?php  if($main_data_array[28] != 'default'){ echo $main_data_array[28]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_billing_address_color_code_default' name='wf_billing_address_color_code_default'<?php echo $main_data_array[28] === $default_active_array[28] ? 'checked' : ''; ?> />Default</span>
																</div>

															</div>     
														</div>
													</div>
												</div>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="shipping_address" id="wf_shiping_address_switch" name="wf_shipping_address_switch"<?php echo $main_data_array[29] === 'no' ? '' : 'checked'; ?>  />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading71" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse71" aria-expanded="false" aria-controls="collapse7"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Shipping Address', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse71" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7">
														<div class="panel-body">
															<div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_shipping_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_shipping_address_title" name="wf_shipping_address_title" placeholder="shipping Title" type="text" value="<?php echo $main_data_array[30]; ?>">

																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_shipping_address_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_shipping_address_text_align" name="wf_shipping_address_text_align" ><?php if($main_data_array[31] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_shipping_address_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_shipping_address_color_code" name="wf_shipping_address_color_code" value="<?php  if($main_data_array[32] != 'default'){ echo $main_data_array[32]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_shipping_address_color_code_default' name='wf_shipping_address_color_code_default'<?php echo $main_data_array[32] === $default_active_array[32] ? 'checked' : ''; ?> />Default</span>
																</div>

															</div>    
														</div>
													</div>
												</div><?php if(in_array('email', $this->invoice_contactno_email)) { ?>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="email" id="wf_email_switch" name="wf_email_switch"<?php echo $main_data_array[33] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading81" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse81" aria-expanded="false" aria-controls="collapse8"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Email Field', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse81" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading8">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="email_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="email_font" name="email_font" placeholder="font size" type="text" value="<?php echo $main_data_array[34]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="email_text">Text</label>
																	<input class="form-control" id="email_text" name="email_text" placeholder="email text" type="text" value="<?php echo $main_data_array[35]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_email_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_email_text_align" name="wf_email_text_align" ><?php if($main_data_array[36] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_email_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_email_color_code" name="wf_email_color_code" value="<?php  if($main_data_array[37] != 'default'){ echo $main_data_array[37]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_email_color_code_default' name='wf_email_color_code_default'<?php echo $main_data_array[37] === $default_active_array[37] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>   
														</div>
													</div>
												</div><?php } ?><?php if(in_array('contact_number', $this->invoice_contactno_email)) { ?>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="tel" id="wf_tel_switch" name="wf_tel_switch"<?php echo $main_data_array[38] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading91" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse91" aria-expanded="false" aria-controls="collapse9"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Tel Field', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse91" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading9">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tel_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tel_font" name="tel_font" placeholder="size" type="font size" value="<?php echo $main_data_array[39]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tel_text">Text</label>
																	<input class="form-control" id="tel_text" name="tel_text" placeholder="text" type="text" value="<?php echo $main_data_array[40]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tel_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_tel_text_align" name="wf_tel_text_align" ><?php if($main_data_array[41] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tel_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_tel_color_code" name="wf_tel_color_code" value="<?php  if($main_data_array[42] != 'default'){ echo $main_data_array[42]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_tel_color_code_default' name='wf_tel_color_code_default'<?php echo $main_data_array[42] === $default_active_array[42] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>    
														</div>
													</div>
												</div><?php } ?><?php if(in_array('vat', $this->invoice_contactno_email)) { ?>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="vat" id="wf_vat_switch" name="wf_vat_switch"<?php echo $main_data_array[43] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading101" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse101" aria-expanded="false" aria-controls="collapse10"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('VAT Field', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse101" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading10">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="vat_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="vat_font" name="vat_font" placeholder="size" type="font size" value="<?php echo $main_data_array[44]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="vat_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="vat_text" name="vat_text" placeholder="character" type="VAT text" value="<?php echo $main_data_array[45]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_vat_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_vat_text_align" name="wf_vat_text_align" ><?php if($main_data_array[46] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tel_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_vat_color_code" name="wf_vat_color_code" value="<?php  if($main_data_array[47] != 'default'){ echo $main_data_array[47]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_vat_color_code_default' name='wf_vat_color_code_default'<?php echo $main_data_array[47] === $default_active_array[47] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>     
														</div>
													</div>
												</div><?php } ?><?php if(in_array('ssn', $this->invoice_contactno_email)) { ?>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="ssn" id="wf_ssn_switch" name="wf_ssn_switch"<?php echo $main_data_array[48] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading111" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse111" aria-expanded="false" aria-controls="collapse11"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('SSN Field', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse111" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading11">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="ssn_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="ssn_font" name="ssn_font" placeholder="size" type="font size" value="<?php echo $main_data_array[49]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="ssn_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="ssn_text" name="ssn_text" placeholder="character" type="SSN text" value="<?php echo $main_data_array[50]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_ssn_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_ssn_text_align" name="wf_ssn_text_align" ><?php if($main_data_array[51] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_ssn_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_ssn_color_code" name="wf_ssn_color_code" value="<?php  if($main_data_array[52] != 'default'){ echo $main_data_array[52]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_ssn_color_code_default' name='wf_ssn_color_code_default'<?php echo $main_data_array[52] === $default_active_array[52] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>    
														</div>
													</div>
												</div><?php } ?>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="tp" id="wf_tp_switch" name="wf_tp_switch"<?php echo $main_data_array[53] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading121" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse121" aria-expanded="false" aria-controls="collapse12"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Shipping Method', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse121" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading12">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tp_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tp_font" name="tp_font" placeholder="size" type="Font size" value="<?php echo $main_data_array[54]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tp_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tp_text" name="tp_text" placeholder="character" type="text" value="<?php echo $main_data_array[55]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tp_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_tp_text_align" name="wf_tp_text_align" ><?php if($main_data_array[56] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tp_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_tp_color_code" name="wf_tp_color_code" value="<?php  if($main_data_array[57] != 'default'){ echo $main_data_array[57]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_tp_color_code_default' name='wf_tp_color_code_default'<?php echo $main_data_array[57] === $default_active_array[57] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>   
														</div>
													</div>
												</div>

												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="tn" id="wf_tn_switch" name="wf_tn_switch"<?php echo $main_data_array[58] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading131" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse131" aria-expanded="false" aria-controls="collapse13"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Tracking Number', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse131" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading13">
														<div class="panel-body">
															<div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tn_font"><?php _e('Font size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tn_font" name="tn_font" placeholder="size" type="font size" value="<?php echo $main_data_array[59]; ?>">
																	<span class="input-group-addon">px</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="ssn_text"><?php _e('Text', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tn_text" name="tn_text" placeholder="character" type="text" value="<?php echo $main_data_array[60]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tn_text_align"><?php _e('Text Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id="wf_tn_text_align" name="wf_tn_text_align" ><?php if($main_data_array[61] === 'right') 
																		{
																			echo "<option selected='true' >right</option> <option>left</option>";
																		}
																		else
																		{
																			echo "<option >right</option> <option selected='true' >left</option>";
																		}
																		?>

																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_tn_color_code"><?php _e('Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_tn_color_code" name="wf_tn_color_code" value="<?php  if($main_data_array[62] != 'default'){ echo $main_data_array[62]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_tn_color_code_default' name='wf_tn_color_code_default'<?php echo $main_data_array[62] === $default_active_array[62] ? 'checked' : ''; ?> />Default</span>
																</div>
															</div>  
														</div>
													</div>
												</div>
												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="product" id="wf_product_switch" name="wf_product_switch"<?php echo $main_data_array[63] === 'no' ? '' : 'checked'; ?> />  <div class="slider round"></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading141" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse141" aria-expanded="false" aria-controls="collapse14"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Product Table', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse141" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading14">
														<div class="panel-body">
															<div>
																
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_head_back_code"><?php _e('Head Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_head_back_code" name="wf_head_back_code" value="<?php  if($main_data_array[64] != 'default'){ echo $main_data_array[64]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_head_back_color_code_default' name='wf_head_back_color_code_default'<?php echo $main_data_array[64] === $default_active_array[64] ? 'checked' : ''; ?> />Default</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_head_front_code"><?php _e('Text Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_head_front_code" name="wf_head_front_code" value="<?php  if($main_data_array[65] != 'default'){ echo $main_data_array[65]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_head_front_color_code_default' name='wf_head_front_color_code_default'<?php echo $main_data_array[65] === $default_active_array[65] ? 'checked' : ''; ?> />Default</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_get_text_align_head"><?php _e('Head Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id='wf_get_text_align_head' name='wf_get_text_align_head'><?php if($main_data_array[66] === 'right')
																		{

																			echo"<option value='right' selected='true'>right</option>
																			<option value='left'>left</option>
																			<option value='center'>Center</option> ";
																		}
																		else if($main_data_array[66] === 'left')
																		{

																			echo"	<option value='right'>right</option>
																			<option value='left' selected='true'>left</option>
																			<option value='center'>Center</option> ";
																		}
																		else
																		{

																			echo"<option value='right'>right</option>
																			<option value='left'>left</option>
																			<option value='center' selected='true'>Center</option> ";
																		}
																		?>
																	</select>
																</div>
																
																
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_body_front_code"><?php _e('Body Color', 'wf-woocommerce-packing-list'); ?></label>
																	<input type="text" id="wf_body_front_code" name='wf_body_front_code' value="<?php  if($main_data_array[67] != 'default'){ echo $main_data_array[67]; }else { echo ''; } ?>" class="form-control jscolor" />
																	<span class="input-group-addon"><input type="checkbox" id='wf_body_front_color_code_default' name='wf_body_front_color_code_default'<?php echo $main_data_array[67] === $default_active_array[67] ? 'checked' : ''; ?> />Default</span>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_get_text_align_body"><?php _e('Body Align', 'wf-woocommerce-packing-list'); ?></label>
																	<select class="form-control" id='wf_get_text_align_body' name='wf_get_text_align_body'><?php if($main_data_array[68] === 'right')
																		{

																			echo"<option value='right' selected='true'>right</option>
																			<option value='left'>left</option>
																			<option value='center'>Center</option> ";
																		}
																		else if($main_data_array[68] === 'left')
																		{

																			echo"	<option value='right'>right</option>
																			<option value='left' selected='true'>left</option>
																			<option value='center'>Center</option> ";
																		}
																		else
																		{

																			echo"<option value='right'>right</option>
																			<option value='left'>left</option>
																			<option value='center' selected='true'>Center</option> ";
																		} ?>
																	</select>
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="img_text"><?php _e('Image', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="img_text" name="img_text" placeholder="character" type="Image column text" value="<?php echo $main_data_array[73]; ?>">
																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="sku_text"><?php _e('SKU', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="sku_text" name="sku_text" placeholder="character" type="SKU column text" value="<?php echo $main_data_array[69]; ?>">
																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="product_text"><?php _e('Product', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="product_text" name="product_text" placeholder="Product column text" type="text" value="<?php echo $main_data_array[70]; ?>">
																</div>


																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="qty_text"><?php _e('Qty', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="qty_text" name="qty_text" placeholder="character" type="Qty column text" value="<?php echo $main_data_array[71]; ?>">
																</div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="tw_text"><?php _e('Total Weight', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="tw_text" name="tw_text" placeholder="character" type="Total Weight column text" value="<?php echo $main_data_array[74]; ?>">
																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="total_text"><?php _e('Total', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="total_text" name="total_text" placeholder="character" type="total column text" value="<?php echo $main_data_array[72]; ?>">
																</div>

															</div>   
														</div>
													</div>
												</div>

												
											</div>
											<!-- end of accordion -->


										</div>
									</div>
								</div>

							</div>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
					<!-- /page content -->

					
				</div>
			</div>

			<div id="custom_notifications" class="custom-notifications dsp_none">
				<ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
				</ul>
				<div class="clearfix"></div>
				<div id="notif-group" class="tabbed_notifications"></div>
			</div>

		</form>

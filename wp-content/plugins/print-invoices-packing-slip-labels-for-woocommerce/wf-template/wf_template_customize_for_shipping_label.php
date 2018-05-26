<?php 

$invoice_data='';
if(isset($_GET['theme']) && !empty($_GET['theme']))
{
$invoice_data = $_GET['theme'];
}

$shipping_label_data='';
if(isset($_GET['theme']) && !empty($_GET['theme']))
{
	$customize_data = get_option($_GET['theme']);
	$main_data_value = eh_theme_value_assign($_GET['theme']);
	$shipping_label_data = $_GET['theme'];

}
else
{
	if(get_option('wf_shipping_label_active_key') != '')
	{
		$customize_data = get_option(get_option('wf_shipping_label_active_key')); //active 
		$main_data_value = eh_theme_value_assign(get_option('wf_shipping_label_active_key'));
		$shipping_label_data = get_option('wf_shipping_label_active_key');
	}
	else
	{
		$customize_data = '<h3>Choose Template</h3>'; //active template want to customize
		$main_data_value = '0|0|0|';
	}
}
$acive_template = get_option('wf_shipping_label_active_key');
$default_active_value = get_option('wf_shipping_label_active_value');
$default_active_array = explode('|',$default_active_value);
$main_data_array = explode('|',$main_data_value);

if(isset($_POST['logo_save']))
{

	
	
	$main_data_array[1] = is_numeric($_POST['logoheight']) ? $_POST['logoheight'] : $default_active_array[1];
	$main_data_array[2] = is_numeric($_POST['logowidth']) ? $_POST['logowidth'] : $default_active_array[2];
	$main_data_array[3] = is_numeric($_POST['company_size_font']) ? $_POST['company_size_font'] : $default_active_array[3];
	
	$main_data_array[5] = is_numeric($_POST['wf_shipping_details_font']) ? $_POST['wf_shipping_details_font'] : $default_active_array[5];
	$main_data_array[6] = !empty($_POST['wf_order_id']) ? $_POST['wf_order_id'] : $default_active_array[6];
	$main_data_array[7] = !empty($_POST['wf_weight_id']) ? $_POST['wf_weight_id'] : $default_active_array[7];
	$main_data_array[8] = !empty($_POST['wf_ship_date_id']) ? $_POST['wf_ship_date_id'] : $default_active_array[8];

	$main_data_array[9] = is_numeric($_POST['wf_from_title_font']) ? $_POST['wf_from_title_font'] : $default_active_array[9];
	$main_data_array[10] = is_numeric($_POST['wf_from_address_font']) ? $_POST['wf_from_address_font'] : $default_active_array[10];
	$main_data_array[11] = is_numeric($_POST['wf_to_title_font']) ? $_POST['wf_to_title_font'] : $default_active_array[11];
	
	$main_data_array[12] = is_numeric($_POST['wf_to_address_font']) ? $_POST['wf_to_address_font'] : $default_active_array[12];
	
	$main_data_array[16] = !empty($_POST['wf_from_address_title']) ? $_POST['wf_from_address_title'] : $default_active_array[16];
	$main_data_array[17] = !empty($_POST['wf_to_title']) ? $_POST['wf_to_title'] : $default_active_array[17];
	$main_data_array[18] = $_POST['sl_company_logo_or_text'];
	
	$main_data_array[21] = is_numeric($_POST['logomargintop']) ? $_POST['logomargintop'] : $default_active_array[21];
	$main_data_array[22] = is_numeric($_POST['logomarginbottom']) ? $_POST['logomarginbottom'] : $default_active_array[22];
	$main_data_array[23] = is_numeric($_POST['logomarginright']) ? $_POST['logomarginright'] : $default_active_array[23];
	$main_data_array[24] = is_numeric($_POST['logomarginleft']) ? $_POST['logomarginleft'] : $default_active_array[24];

	$main_data_array[19] = !empty($_POST['wf_company_logo_switch']) ? 'yes' : 'no';
        
        
        $main_data_array[27] = !empty($_POST['custom_shippinglabel_nameholder']) ? sanitize_text_field($_POST['custom_shippinglabel_nameholder']) : '';
	
	

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
			update_option('wf_shipping_label_active_key',$_GET['theme']);
			
		}
		else
		{	
			

			for ($f=1; get_option('wf_shipping_label_template_'.$f) !='' ;$f++)
			{
				$i +=1;
			}
			update_option('wf_shipping_label_template_'.$i,get_option($_GET['theme']));
			update_option('wf_shipping_label_template_'.$i.'value',$my_main_data);
			update_option('wf_shipping_label_template_'.$i.'custom', 'yes');
			update_option('wf_shipping_label_template_'.$i.'from', $_GET['theme']);
			update_option('wf_shipping_label_active_key','wf_shipping_label_template_'.$i);
			wp_redirect(admin_url('admin.php?page=wf_woocommerce_packing_list'));
		}
	}
	else
	{
		// echo $_GET['theme'];
		if(get_option($acive_template.'custom') === 'yes')
		{
			update_option($acive_template.'value',$my_main_data);
			update_option('wf_shipping_label_active_key',$acive_template);
		}
		else
		{
			for ($f=1; get_option('wf_shipping_label_template_'.$f) !='';$f++)
			{
				$i +=1;
			}
			update_option('wf_shipping_label_template_'.$i,get_option($acive_template));
			update_option('wf_shipping_label_template_'.$i.'value',$my_main_data);
			update_option('wf_shipping_label_template_'.$i.'custom', 'yes');
			update_option('wf_shipping_label_template_'.$i.'from', $acive_template);
			update_option('wf_shipping_label_active_key','wf_shipping_label_template_'.$i);
			wp_redirect(admin_url('admin.php?page=wf_woocommerce_packing_list'));
			
		}

	}
}
function eh_theme_value_assign($given_template)
{
	if(get_option($given_template.'value') === false)
	{
		return get_option('wf_shipping_label_active_value');
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
		background-color: black!important;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px;
		
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


function askForCustomName() {
    
    if(document.getElementById("custom_shippinglabel_nameholder").value != ''){
        return true;
    }
    var custom_template_name = prompt("Please enter name of custom template", "My Custom Shipping Label");
    if (custom_template_name != null) {
        document.getElementById("custom_shippinglabel_nameholder").value =  custom_template_name;
    }
}
</script>

<ul class="subsubsub">
                <li><a style="color: #0073aa;" href="<?php echo admin_url('admin.php?page=wf_woocommerce_packing_list&tab=shipping_label'); ?>" class=""><?php _e('Settings','wf-woocommerce-packing-list'); ?></a> | </li>
                <li><a href="<?php echo admin_url('admin.php?page=wf_template_customize_for_invoice&themeselection=shipping_label&theme=').get_option('wf_shipping_label_active_key'); ?>" class="current"><?php _e('Customize','wf-woocommerce-packing-list'); ?></a></li>
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
											<div class="accordion"  style="min-height: 28cm;" id="my_new_shipping_label" ><?php 
												
												if($main_data_array[19] === 'no'){
														$customize_data = str_replace('[text switch]', '; display:none;', $customize_data);
														$customize_data = str_replace('[logo switch]', 'display:none;', $customize_data);
														$customize_data = str_replace('[hide both]', 'display:none;', $customize_data);
												}else{

														
									
														if ( $this->wf_packinglist_get_logo() != '' && $main_data_array[18] === 'logo'){


														$customize_data = str_replace('[logo switch]', '', $customize_data);
														
														$customize_data = str_replace('[text switch]', 'display:none;', $customize_data);
														$customize_data = str_replace('[company name font size]', '', $customize_data);
														
														
														}else{
														$customize_data = str_replace('[image source]', '', $customize_data);
														$customize_data = str_replace('[logo switch]', 'display:none;', $customize_data);
														$customize_data = str_replace('[text switch]', '', $customize_data);
														$customize_data = str_replace('[company name font size]', $main_data_array[3].'px;', $customize_data);
														
														}
													}

													if($this->wf_packinglist_get_companyname()){
														$customize_data = str_replace('[company Name]',$this->wf_packinglist_get_companyname(), $customize_data);}else{
															$customize_data = str_replace('[company Name]','Company Name', $customize_data);
														}
													
														$customize_data = str_replace('[image source]',$this->wf_packinglist_get_logo(), $customize_data);
														$customize_data = str_replace('[logo image height]', $main_data_array[1].'px', $customize_data);
														$customize_data = str_replace('[logo image width]', $main_data_array[2].'px;', $customize_data);
												$customize_data = str_replace('[margin top]', 'margin-top:'.$main_data_array[21].'px;', $customize_data);
												$customize_data = str_replace('[margin bottom]', 'margin-bottom:'.$main_data_array[22].'px;', $customize_data);
												$customize_data = str_replace('[margin right]', 'margin-right:'.$main_data_array[23].'px', $customize_data);
												$customize_data = str_replace('[margin left]', 'margin-left:'.$main_data_array[24].'px', $customize_data);
												$customize_data = str_replace('[company name font size]', 'font-size:'.$main_data_array[3].'px;',$customize_data);
												$customize_data = str_replace('[order details font]', 'font-size:'.$main_data_array[5].'px;', $customize_data);
												
												$customize_data = str_replace('[Order Number Id]', $main_data_array[6], $customize_data);
												$customize_data = str_replace('[Order Number Value]', 'n/a', $customize_data);
											
												$customize_data = str_replace('[Ship Date]', $main_data_array[8], $customize_data);
												$customize_data = str_replace('[ship date value]','dd-mm-yy', $customize_data);

												$customize_data = str_replace('[Weight]', $main_data_array[7], $customize_data);
                                                                                                $customize_data = str_replace('[weight value]', '0', $customize_data);
												

												$customize_data = str_replace('[from font size]', 'font-size:'.$main_data_array[9].'px;', $customize_data);
												$customize_data = str_replace('[from address font size]', 'font-size:'.$main_data_array[10].'px;', $customize_data);

                                                                                                $order = new WC_Order(0);
												$ship_from_address = $this->wf_shipment_label_get_from_address('shipping_label',$order );
												$from_address_data = '';
												foreach ($ship_from_address as $key => $value) {
													if (!empty($value)) {
														$from_address_data .= $value . ' <br>';
													}
												}

                                                                                                
												if(empty($from_address_data)){

													$customize_data = str_replace('[from address display]','display:none !important;', $customize_data);
													$customize_data = str_replace('[Address]', '', $customize_data);
												}else{
													$customize_data = str_replace('[FROM]', $main_data_array[16], $customize_data);
													$customize_data = str_replace('[Address]', $from_address_data, $customize_data); 
												}


												$customize_data = str_replace('[to font size]','font-size:'.$main_data_array[11].'px;', $customize_data);
												$customize_data = str_replace('[to address font size]', 'font-size:'.$main_data_array[12].'px;', $customize_data);
												$customize_data = str_replace('[TO]', $main_data_array[17], $customize_data);

												$toaddress='Name<br>'.'Company name<br>'.'Address1<br>'.'Address2<br>'.'State<br>'.'Country<br>';
												$customize_data = str_replace('[To Address]',$toaddress, $customize_data);

												$customize_data = str_replace('[barcode alignment]', "'".$main_data_array[13].";'", $customize_data);

												$customize_data = str_replace('[return font size]', 'font-size:'.$main_data_array[14].';', $customize_data);

												

												if ($this->wf_packinglist_get_return_policy() != '')
												{ 
													
													$customize_data = str_replace('[Return Policy]', $this->wf_packinglist_get_return_policy(), $customize_data);
													$customize_data = str_replace('[return policy hide]', '', $customize_data);
												}else{
													
													$customize_data = str_replace('[Return Policy]', '', $customize_data);
													$customize_data = str_replace('[return policy hide]', 'display:none;', $customize_data);
												}



												$customize_data = str_replace('[footer font size]', $main_data_array[15],$customize_data);
												$customize_data = str_replace('[footer]', __(nl2br($this->wf_packinglist_get_footer(1,'xyz')),'wf-woocommerce-packing-list') , $customize_data);


												
													$customize_data = str_replace('[qr code display]', 'display:none;', $customize_data);
													$customize_data = str_replace('[QR Code]', '', $customize_data);
												

												if((get_option('woocommerce_wf_packinglist_datamatrix_information') == 'Yes' )) {

													$customize_data = str_replace('[hide barcode]', '', $customize_data);
													$customize_data = str_replace('[barcode font size]', 'px', $customize_data);
													$customize_data = str_replace('[tracking Number]', 'Tracking Number : 112', $customize_data);
															include_once('picqer/BarcodeGenerator.php');
															include_once('picqer/BarcodeGeneratorPNG.php');
															include_once('picqer/BarcodeGeneratorSVG.php');
															include_once('picqer/BarcodeGeneratorJPG.php');
															include_once('picqer/BarcodeGeneratorHTML.php');
													$generator = new BarcodeGeneratorPNG();
													$customize_data = str_replace('[barcode image source]', 'data:image/png;base64,' . base64_encode($generator->getBarcode('1234567', $generator::TYPE_CODE_128)) , $customize_data);
												}else{

													$customize_data = str_replace('[hide barcode]', 'Display:none', $customize_data);
												}

												$customize_data = str_replace('[footer font size]', '15px', $customize_data);
												$customize_data = str_replace('[Footer]', $this->wf_packinglist_get_footer(1,'xyz'), $customize_data);
												$customize_data = str_replace('[barcode adjust]','', $customize_data);
												echo $customize_data;
												?>
												<div style="position: absolute;top:10px;"><button class="button button-secondary " style="display:none;" onclick="PrintElem('my_new_shipping_label','<?php echo WF_INVOICE_MAIN_ROOT_PATH; ?> ','show')"  type="button"><i class="fa fa-eye"></i></button> 
												<button class="button button-secondary " style="display:none;" onclick="PrintElem('my_new_shipping_label','<?php echo WF_INVOICE_MAIN_ROOT_PATH; ?> ','print')"  type="button"><i class="fa fa-print"></i></button> 
												
												</div>
											</div>
											<!-- end of accordion -->
										</div>
									</div>
								</div>


								
								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2><i class="fa fa-align-left"></i><?php _e('Field Attributes', 'wf-woocommerce-packing-list'); ?>
                                                                                            <small><?php if(!empty($main_data_array[27])){echo $main_data_array[27];}else{echo 'Shipping Label '.substr($shipping_label_data, -1);} ?></small>  
                                                                                        </h2>
                                                                                    <!-- -->
                                                                                    
                                                                                    
											<div class="pull-right">
												<span class="">
                                                                                                    <input id="custom_shippinglabel_nameholder" type="hidden" name="custom_shippinglabel_nameholder" value="<?php if(!empty($main_data_array[27])){echo $main_data_array[27];}else{echo '';} ?>"/>											
																
											
													<div class="tooltips"><button onclick="askForCustomName(); return true;" id="logo_save" name="logo_save" class="button button-primary" style="font-size: 12px;" >Save & Activate</button>
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
													<input type="checkbox" id="wf_company_logo_switch" name="wf_company_logo_switch" value="wf_company_switch"<?php echo $main_data_array[19] === 'yes' ? 'checked' : ''; ?>/>  <div class="slider round"></div>
												</label>
												<h4 class="panel-title collapsed" role="tab" id="headingTwo1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Company Logo', 'wf-woocommerce-packing-list'); ?></h4>
											</div>

											<div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
												<div class="panel-body">
													<div>
														<div class="input-group input-group-sm">

															<label class="input-group-addon" for="logoheight"><?php _e('Display', 'wf-woocommerce-packing-list'); ?></label>
															<select class="form-control clickable" id="sl_company_logo_or_text" name="sl_company_logo_or_text" ><?php if ($main_data_array[18] === 'logo')
																{
																	echo '<option value="logo" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>
																	<option value="name" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>';
																}
																else
																{
																	echo '<option value="name" selected="true" style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Name</option>
																	<option value="logo"   style="font-size: 16px;margin-bottom: 5px;margin-top: 5px;">Company Logo</option>';
																} ?>

															</select>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logowidth"><?php _e('Width', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logowidth" name="logowidth" placeholder="logo width" type="text" value="<?php echo $main_data_array[2]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logoheight"><?php _e('Height', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logoheight" name="logoheight" placeholder="logo height" type="text" value="<?php echo $main_data_array[1]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logomargintop"><?php _e('Logo Margin Top', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logomargintop" name="logomargintop" placeholder="logo margin top" value="<?php echo $main_data_array[21]; ?>">
															<span class="input-group-addon">px</span>
														</div>

														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logomarginbottom"><?php _e('Logo Margin Bottom', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logomarginbottom" name="logomarginbottom" placeholder="logo margin bottom" value="<?php echo $main_data_array[22]; ?>"><span class="input-group-addon">px</span>
														</div>
														
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logomarginright"><?php _e('Logo Margin Right', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logomarginright" name="logomarginright" placeholder="logo marginright" value="<?php echo $main_data_array[23]; ?>"><span class="input-group-addon">px</span>
														</div>														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="logomarginleft"><?php _e('Logo Margin Left', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="logomarginleft" name="logomarginleft" placeholder="logo margin left" value="<?php echo $main_data_array[24]; ?>"><span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="company_size_font"><?php _e('Company Name Font Size', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="company_size_font" name="company_size_font" placeholder="Font size" type="text" value="<?php echo $main_data_array[3]; ?>"><span class="input-group-addon">px</span>
														</div>
													</div> 

												</div>
											</div>
										</div>
										<div class="panel">

											<div class="panel-heading  clickable" >
												<label class="switch pull-right ">
													<input id="wf_invoice_number_switch" name="wf_invoice_number_switch" value="invoice_number" type="checkbox"  />
												</label>
												<h4 class="panel-title collapsed" role="tab" id="headingThree1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('Shipping Details', 'wf-woocommerce-packing-list'); ?></h4>
											</div>

											<div id="collapseThree1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
												<div class="panel-body">
													<div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_shipping_details_font"><?php _e('Shipping Details Font Size', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_shipping_details_font" name="wf_shipping_details_font" placeholder="shipping details font" type="text" value="<?php echo $main_data_array[5]; ?>">
															<span class="input-group-addon">px</span>
														</div>
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_order_id"><?php _e('Order Title', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_order_id" name="wf_order_id" placeholder="order title" type="text" value="<?php echo $main_data_array[6]; ?>">
														</div>

														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_weight_id"><?php _e('Weight Title', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_weight_id" name="wf_weight_id" placeholder="weight title" type="text" value="<?php echo $main_data_array[7]; ?>">
														</div>

														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_ship_date_id"><?php _e('Ship Date Title', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_ship_date_id" name="wf_ship_date_id" placeholder="ship date title" type="text" value="<?php echo $main_data_array[8]; ?>">
														</div>
														
														<div class="input-group input-group-sm">
															<label class="input-group-addon" for="wf_ship_date_format"><?php _e('Format', 'wf-woocommerce-packing-list'); ?></label>
															<input class="form-control" id="wf_ship_date_format" name="wf_ship_date_format" placeholder="Date Format" type="text" value="<?php echo $main_data_array[20]; ?>">

															<span class="input-group-addon">
																<select id = 'wf_ship_date_format_selection' name='wf_ship_date_format_selection' style="width:auto;height:auto;padding:0px;" >
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
															</span>
														</div>

													</div>   
												</div>
											</div>
										</div>

										<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="from_address" id="wf_from_address_switch" name="wf_from_address_switch"/>  <div class=""></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="headingFive1" data-toggle="collapse" data-parent="#accordion1" data-target="#collapseFive1" aria-expanded="false" aria-controls="collapseFive"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('From Address', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapseFive1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
														<div class="panel-body">
															<div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_address_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_from_address_title" name="wf_from_address_title" placeholder="From Address Title" type="text" value="<?php echo $main_data_array[16]; ?>">

																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_title_font"><?php _e('From Title Font Size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_from_title_font" name="wf_from_title_font" placeholder="from title font" type="text" value="<?php echo $main_data_array[9]; ?>">
																	<span class="input-group-addon">px</span>
																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_from_address_font"><?php _e('From address Font Size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_from_address_font" name="wf_from_address_font" placeholder="from address font" type="text" value="<?php echo $main_data_array[10]; ?>">
																	<span class="input-group-addon">px</span>
																</div>			
															</div>   
														</div>
													</div>
												</div>

												<div class="panel">
													<div class="panel-heading  clickable" >
														<label class="switch pull-right ">
															<input type="checkbox" value="to_address" id="wf_to_address_switch" name="wf_to_address_switch"/>  <div class=""></div>
														</label>
														<h4 class="panel-title collapsed" role="tab" id="heading71" data-toggle="collapse" data-parent="#accordion1" data-target="#collapse71" aria-expanded="false" aria-controls="collapse7"><i class="iconPM fa fa-angle-double-down" aria-hidden="true"></i><?php _e('To Address', 'wf-woocommerce-packing-list'); ?></h4>
													</div>
													<div id="collapse71" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading7">
														<div class="panel-body">
															<div>
																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_to_title"><?php _e('Title', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_to_title" name="wf_to_title" placeholder="To Title" type="text" value="<?php echo $main_data_array[17]; ?>">

																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_to_title_font"><?php _e('To Title Font Size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_to_title_font" name="wf_to_title_font" placeholder="to title font" type="text" value="<?php echo $main_data_array[11]; ?>">
																	<span class="input-group-addon">px</span>
																</div>

																<div class="input-group input-group-sm">
																	<label class="input-group-addon" for="wf_to_address_font"><?php _e('To address Font Size', 'wf-woocommerce-packing-list'); ?></label>
																	<input class="form-control" id="wf_to_address_font" name="wf_to_address_font" placeholder="to address font" type="text" value="<?php echo $main_data_array[12]; ?>">
																	<span class="input-group-addon">px</span>
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
				</div>
				<!-- /page content -->

				<!-- footer content -->   <!-- /footer content -->
			</div>
			<div id="custom_notifications" class="custom-notifications dsp_none">
				<ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
				</ul>
				<div class="clearfix"></div>
				<div id="notif-group" class="tabbed_notifications"></div>
		</div>
	</form>

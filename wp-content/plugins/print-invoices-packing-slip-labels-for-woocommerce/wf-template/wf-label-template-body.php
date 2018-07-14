<?php
$active_template = get_option('wf_shipping_label_active_key');
$invoice_number         = $this->generate_invoice_number($order);
$main_data = get_option($active_template);
if(get_option($active_template.'value')){
	$main_data_value = get_option($active_template.'value');
	}
else{
		$main_data_value = get_option('wf_shipping_label_active_value');
	}

$main_data_array = explode('|', $main_data_value);
	if($main_data_array[19] === 'no'){
			$main_data = str_replace('[text switch]', '; display:none;', $main_data);
	$main_data = str_replace('[logo switch]', 'display:none;', $main_data);
	$main_data = str_replace('[hide both]', 'display:none;', $main_data);
}else{
		
	if($this->wf_packinglist_get_logo() != '') { 
	if ($main_data_array[18] === 'logo'){

		$main_data = str_replace('[image source]', $this->wf_packinglist_get_logo(), $main_data);
		$main_data = str_replace('[logo image height]', $main_data_array[1].'px', $main_data);
		$main_data = str_replace('[margin top]', 'margin-top:'.$main_data_array[21].'px;', $main_data);
		$main_data = str_replace('[margin bottom]', 'margin-bottom:'.$main_data_array[22].'px;', $main_data);
		$main_data = str_replace('[margin right]', 'margin-right:'.$main_data_array[23].'px;', $main_data);
		$main_data = str_replace('[margin left]', 'margin-left:'.$main_data_array[24].'px;', $main_data);
		$main_data = str_replace('[logo image width]', $main_data_array[2].'px;', $main_data);
		$main_data = str_replace('[logo switch]', '', $main_data);
		$main_data = str_replace('[company Name]',$this->wf_packinglist_get_companyname(), $main_data);
		$main_data = str_replace('[company name font size]', 'font-size:'.$main_data_array[3].'px', $main_data);
		$main_data = str_replace('[text switch]', '; display:none;', $main_data);
	}else{

		$main_data = str_replace('[image source]', '', $main_data);
		$main_data = str_replace('[logo switch]', 'display:none;', $main_data);
		$main_data = str_replace('[text switch]', '', $main_data);
		$main_data = str_replace('[company Name]',$this->wf_packinglist_get_companyname(), $main_data);
		$main_data = str_replace('[company name font size]', 'font-size:'.$main_data_array[3].'px', $main_data);

	}
	}
}
$main_data = str_replace('[order font size]', 'font-size:'.$main_data_array[4].'px;', $main_data);
$main_data = str_replace('[order details font]', 'font-size:'.$main_data_array[5].'px;', $main_data);

$orderdetails = $this->wf_packinglist_get_table_content($order, $order_package);

if(!empty($orderdetails)){
	$main_data = str_replace('[Order Number Id]', $main_data_array[6], $main_data);
	$main_data = str_replace('[Order Number Value]', $order->get_order_number() , $main_data);
}

if(key_exists('ship_date',$order_additional_information)){


$ship_date=!empty($order_additional_information['ship_date'])?date($main_data_array[20],strtotime( $order_additional_information['ship_date'] )) : ' ';	

$main_data = str_replace('[Ship Date]',!empty($order_additional_information['ship_date'])? $main_data_array[8]:' ', $main_data);
$main_data = str_replace('[ship date value]',$ship_date, $main_data);

}
else{
$main_data = str_replace('[Ship Date]', '', $main_data);
$main_data = str_replace('[ship date value]', '', $main_data);	
}
$main_data = str_replace('[Weight]', $main_data_array[7], $main_data);
if(!empty($orderdetails)){

	$main_data = str_replace('[weight value]', $orderdetails['weight'], $main_data);
	$order_details_name =  $orderdetails['name'];
	$box_name = (!empty($order_details_name) && $order_details_name != ' ') ? ('Box Label:'.$order_details_name) : ' ';
	$main_data = str_replace('[box name]', $box_name, $main_data);
}


$main_data = str_replace('[from font size]', 'font-size:'.$main_data_array[9].'px;', $main_data);
$main_data = str_replace('[from address font size]', 'font-size:'.$main_data_array[10].'px;', $main_data);

$ship_from_address=$this->wf_shipment_label_get_from_address($document_type='shipmentlabel',$order);
$from_address_data = '';
foreach ($ship_from_address as $key => $value) {
	if (!empty($value)) {
		$from_address_data .= $value .'<br>';
	}
}
$from_address_data = apply_filters('wf_alter_shipmentlabel_from_address', $from_address_data,$order, $ship_from_address);    

if(empty($from_address_data)){

	$main_data = str_replace('[from address display]','display:none !important;', $main_data);
	$main_data = str_replace('[Address]', '', $main_data);
}else{
	$main_data = str_replace('[FROM]', $main_data_array[16], $main_data);
	$main_data = str_replace('[Address]', $from_address_data, $main_data); 
}

$main_data = str_replace('[to font size]','font-size:'.$main_data_array[11].'px;', $main_data);
$main_data = str_replace('[to address font size]', 'font-size:'.$main_data_array[12].'px;', $main_data);
$main_data = str_replace('[TO]', $main_data_array[17], $main_data);

$main_data = str_replace('[To Address]',$this->shippinglabel->get_shipto_address($order), $main_data);

$main_data = str_replace('[return font size]', 'font-size:'.$main_data_array[14].'px;', $main_data);


if ($this->wf_packinglist_get_return_policy() != '')
{ 
	
	$main_data = str_replace('[Return Policy]', '', $main_data);
	$main_data = str_replace('[return policy hide]', '', $main_data);
}else{
	$main_data = str_replace('[Return Policy]', '', $main_data);
	$main_data = str_replace('[return policy hide]', 'display:none;', $main_data);
}

$main_data = str_replace('[footer font size]', 'font-size:'.$main_data_array[15].'px;',$main_data);
$main_data = str_replace('[footer]', ' ', $main_data);


if($main_data_array[19] == 'qrcode'){

	$main_data = str_replace('[qr code display]','' , $main_data);
	$main_data = str_replace('[QR Code]', 'qrcode', $main_data);
}else{

	$main_data = str_replace('[qr code display]', 'display:none;', $main_data);
	$main_data = str_replace('[QR Code]', '', $main_data);
}

if((get_option('woocommerce_wf_packinglist_datamatrix_information') == 'Yes' ) && get_post_meta($order_id,'wf_invoice_number',true)) {
	// echo $order_id;
	$main_data = str_replace('[hide barcode]', '', $main_data);
	$main_data = str_replace('[barcode font size]', 'font-size:'.$main_data_array[26].'px;', $main_data);
	$main_data = str_replace('[tracking Number]', 'Tracking Number :'.get_post_meta($order_id,'wf_invoice_number',true), $main_data);
	require_once 'class_wf_barcode_generator.php';
	$wf_barcode_obj = new wf_barcode_generator();
	$barcode_data = $wf_barcode_obj->wf_generate_barcode(get_post_meta($order_id,'wf_invoice_number',true));
	$main_data 		= str_replace('[barcode image source]', $barcode_data , $main_data);
	
}
else{

	$main_data = str_replace('[hide barcode]', 'display:none', $main_data);
}


$main_data = str_replace('[Footer]','', $main_data);


if(get_option('wf_custom_label_size_width') != '' && get_option ('wf_custom_label_size_height')){
	$main_data = str_replace('[barcode adjust]', '<br><br>', $main_data);
	$main_data = str_replace('[main width]', get_option('wf_custom_label_size_width').'in', $main_data);
	$main_data = str_replace('[main height]', get_option('wf_custom_label_size_height').'in', $main_data);


}else{


	if(get_option('woocommerce_wf_packinglist_label_size') && get_option('woocommerce_wf_packinglist_label_size') === '1'){
		$main_data = str_replace('[barcode adjust]', '<br><br>', $main_data);
		$main_data = str_replace('[main width]', '4in', $main_data);
		$main_data = str_replace('[main height]','5.5in', $main_data);
	}else{
		$main_data = str_replace('[barcode adjust]', ' ', $main_data);
		$main_data = str_replace('[main width]', '100%', $main_data);
		$main_data = str_replace('[main height]', 'auto', $main_data);
		$main_data = str_replace('[barcode adjust]', '', $main_data);

	}
}

echo $main_data; ?>




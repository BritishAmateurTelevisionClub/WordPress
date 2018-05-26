<?php
require('pdf-templates/tfpdf.php');
class Pdf_Invoice_Packingslip_Deliverynote  extends tFPDF
{
	public $heading_size;
	public $title_size;
	public $content_size;
	function init($font, $font_size)
	{
		$this->AddPage();
		$font_name;
		if($font == 'big5') {
			$font_name = 'big5.ttf';
		} else {
			$font_name = 'arial.ttf';
		}
		$this->AddFont('DejaVu','',$font_name,true);
		$this->SetFont('DejaVu','',8);
		switch($font_size) {
			case 'small':
				$this->heading_size = 23;
				$this->title_size = 13;
				$this->content_size = 8;
				break;
			case 'large':
				$this->heading_size = 27;
				$this->title_size = 17;
				$this->content_size = 12;
				break;
			default:
				$this->heading_size = 25;
				$this->title_size = 15;
				$this->content_size = 10;
				break;
		}
	}
	
	//function to add logo
	function addImage($img, $dimensions)
	{
		$image_path = WP_CONTENT_DIR.'/'.strstr($img,'uploads');
		$image_format = strtolower(pathinfo($img, PATHINFO_EXTENSION));
		$this->Image($image_path,10,10,$dimensions['width']*0.264,$dimensions['height']*0.264, $image_format);
	}
	
	function add_Companyname($companyname){
		$this->SetFontSize($this->heading_size);
		$this->setXY(65,3);
		$this->Cell(50,20,__($companyname,'wf-woocommerce-packing-list'),0,0,'R');
	}
	 
	function from_address($ship_from_address){
		$this->SetFontSize($this->content_size);
		$y_position=7;
		foreach($ship_from_address as $value){
			$this->setXY(-65,$y_position);
			if(!empty($value)){
				$this->Cell(40,10,__($value,'wf-woocommerce-packing-list'),0,1);
				$y_position+=5;
			}
		}
	}
	
	function billing_address($order, $contactno_email, $billing_address){
		$order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
		$y_position=55;
		$this->SetFontSize($this->title_size);
		$this->setXY(10,55);	
		$this->Cell(40,5,__('Billing address: ','wf-woocommerce-packing-list'),0,1);
		$this->SetFontSize($this->content_size);
		//printing billing address
		$this->setXY(10,$y_position);
		$this->Ln(3);
		$this->Cell(40,10,__($billing_address['first_name'] . ' ' . $billing_address['last_name'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		if($billing_address['company'] != '') {
			$this->setXY(10,$y_position);
			$this->Ln(3);
			$this->Cell(40,10,__($billing_address['company'],'wf-woocommerce-packing-list'),0,1);
			$y_position += 5;
		}
		$this->setXY(10,$y_position);
		$this->Ln(3);
		$this->Cell(40,10,__($billing_address['address_1'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;$this->setXY(10,$y_position);
		if($billing_address['address_2'] != '') {
			$this->Ln(3);
			$this->Cell(40,10,__($billing_address['address_2'],'wf-woocommerce-packing-list'),0,1);
			$y_position += 5;
			$this->setXY(10,$y_position);
		}
		$this->Ln(3);
		$this->Cell(40,10,__($billing_address['city'].', '.$billing_address['state'] . ' ' . $billing_address['postcode'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		$this->setXY(10,$y_position);
		$this->Ln(3);
		$this->Cell(40,10,__($billing_address['country'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		if(in_array('ssn', $contactno_email)) { 
			$ssn_data = (WC()->version < '2.7.0') ? $order->billing_SSN : get_post_meta($order_id,'_billing_SSN',true);
			if(!empty($ssn_data))
			{
				$this->Cell(40,5,__('SSN: ','wf-woocommerce-packing-list').$ssn_data,0,1);
			} 
			
		}
		if(in_array('vat', $contactno_email)) {
			$vat_data = (WC()->version < '2.7.0') ? $order->billing_SSN : get_post_meta($order_id,'_billing_VAT',true);
			if(!empty($vat_data))
			{
				$this->Cell(40,5,__('VAT: ','wf-woocommerce-packing-list').$vat_data,0,1);
			} 			
		}
		if(in_array('email', $contactno_email)) {
			$this->Cell(40,5,__('Email: ','wf-woocommerce-packing-list').$order->billing_email,0,1);
		}
		if(in_array('contact_number', $contactno_email)) {
			$this->Cell(40,5,__('Phone: ','wf-woocommerce-packing-list').$order->billing_phone,0,1);
		}
	}
	
	function shipping_address($order, $shipping_address){
		$x_position = -100;$y_position=58;
		$this->SetFontSize($this->title_size);
		$this->setXY(-100,57);
		$this->Cell(40,2,__('Shipping address: ','wf-woocommerce-packing-list'),0,1);
		$this->SetFontSize($this->content_size);
		//printing shipping address
		$this->setXY($x_position,$y_position);
		$this->Cell(40,10,__($shipping_address['first_name'] . ' ' . $shipping_address['last_name'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		if($shipping_address['company'] != '') {
			$this->setXY($x_position,$y_position);
			$this->Cell(40,10,__($shipping_address['company'],'wf-woocommerce-packing-list'),0,1);
			$y_position += 5;
		}
		$this->setXY($x_position,$y_position);
		$this->Cell(40,10,__($shipping_address['address_1'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		$this->setXY($x_position,$y_position);
		if($shipping_address['address_2'] != '') {
			$this->Cell(40,10,__($shipping_address['address_2'],'wf-woocommerce-packing-list'),0,1);
			$y_position += 5;
			$this->setXY($x_position,$y_position);
		}
		$this->Cell(40,10,__($shipping_address['city'].', '.$shipping_address['state'] . ' ' . $shipping_address['postcode'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
		$this->setXY($x_position,$y_position);
		$this->Cell(40,10,__($shipping_address['country'],'wf-woocommerce-packing-list'),0,1);
		$y_position += 5;
	}
			
	function addPoliciesInvoice($policy){
		$this->Ln(5);
		$y_position = $this->GetY();
		$this->setXY(12,$y_position);
		$policy_content = $this->create_url_content($policy);
		$this->Write(5,__(strip_tags($policy_content['initial_content']),'wf-woocommerce-packing-list'));
		$this->SetTextColor(0,0,255);
		$this->Write(5,__(strip_tags($policy_content['url_name']),'wf-woocommerce-packing-list'),$policy_content['url']);
		$this->SetTextColor(0,0,0);
		$this->Write(5,__(strip_tags($policy_content['end_content']),'wf-woocommerce-packing-list'));
		$this->setX(12);
		$this->MultiCell(180,5,'','B','L',0);
	}

	function addFooterInvoice($footer){
		$this->setXY(12,$this->GetY());
		$footer_content = $this->create_url_content($footer);
		$this->Write(5,__(strip_tags($footer_content['initial_content']),'wf-woocommerce-packing-list'));
		$this->SetTextColor(0,0,255);
		$this->Write(5,__(strip_tags($footer_content['url_name']),'wf-woocommerce-packing-list'),$footer_content['url']);
		$this->SetTextColor(0,0,0);
		$this->Write(5,__(strip_tags($footer_content['end_content']),'wf-woocommerce-packing-list'));
	}
	
	function br2nl($string)
	{
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}

	//function to calculate total number of lines for product name 
	function total_lines($w, $txt, $h=1, $border=0, $align='J', $fill=false)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw = &$this->CurrentFont['cw'];
		if($w==0)
			$w = $this->w-$this->rMargin-$this->x;
		$wmax = ($w-2*$this->cMargin);
		$s = str_replace("\r",'',$txt);
		if ($this->unifontSubset) {
			$nb=mb_strlen($s, 'utf-8');
			while($nb>0 && mb_substr($s,$nb-1,1,'utf-8')=="\n")	$nb--;
		} else {
			$nb = strlen($s);
			if($nb>0 && $s[$nb-1]=="\n")
				$nb--;
		}
		$b = 0;
		if($border) {
			if($border==1){
				$border = 'LTRB';
				$b = 'LRT';
				$b2 = 'LR';
			} else {
				$b2 = '';
				if(strpos($border,'L')!==false)
					$b2 .= 'L';
				if(strpos($border,'R')!==false)
					$b2 .= 'R';
				$b = (strpos($border,'T')!==false) ? $b2.'T' : $b2;
			}
		}
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$ns = 0;
		$nl = 1;
		while($i<$nb) {
			// Get next character
			if ($this->unifontSubset) {
				$c = mb_substr($s,$i,1,'UTF-8');
			} else {
				$c=$s[$i];
			}
			if($c=="\n") {
				// Explicit line break
				if($this->ws>0) {
					$this->ws = 0;
				}
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
				$nl++;
				if($border && $nl==2)
					$b = $b2;
				continue;
			}
			if($c==' ')	{
				$sep = $i;
				$ls = $l;
				$ns++;
			}
			if ($this->unifontSubset) { $l += $this->GetStringWidth($c); }
			else { $l += $cw[$c]*$this->FontSize/1000; }
			if($l>$wmax) {
				// Automatic line break
				if($sep==-1) {
					if($i==$j)
						$i++;
					if($this->ws>0) {
						$this->ws = 0;
					}
				} else {
					if($align=='J') {
						$this->ws = ($ns>1) ? ($wmax-$ls)/($ns-1) : 0;
					}
					$i = $sep+1;
				}
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
				$nl++;
				if($border && $nl==2)
					$b = $b2;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	function create_url_content($content)
	{
		$new_content = array(
			'initial_content' => '',
			'url_name'        => '',
			'url'             => '',
			'end_content'     => ''
		);
		if(strpos($content,'<a') !== false){
			$url_start = strpos($content,'<a');
			$url_end = strpos($content,'</a>');
			$url_string = substr($content,$url_start,($url_end+4 - $url_start));
			preg_match('/href=(["\'])([^\1]*)\1/i', $url_string, $url);
			$new_content['initial_content'] = substr($content,0,$url_start).' ';
			$new_content['end_content'] = ' '.substr($content,$url_end);
			$new_content['url'] = $url[2];
			$new_content['url_name'] = strip_tags($url_string);
		} else {
			$new_content['initial_content'] = $content;
		}
		return $new_content;
	}
	
	//function to create rgb value for table header color
	function get_color_values($hex_color)
	{
		$hex = str_replace("#", "", $hex_color);
		$rgb = array();
		if(strlen($hex) == 3) {
			$rgb['red'] = hexdec(substr($hex,0,1).substr($hex,0,1));
			$rgb['green'] = hexdec(substr($hex,1,1).substr($hex,1,1));
			$rgb['blue'] = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else if(strlen($hex) == 6) {
			$rgb['red'] = hexdec(substr($hex,0,2));
			$rgb['green'] = hexdec(substr($hex,2,2));
			$rgb['blue'] = hexdec(substr($hex,4,2));
		} else {
			$rgb['red'] = 30;
			$rgb['green'] = 115;
			$rgb['blue'] = 190;
		}
		return $rgb; 
	}
}

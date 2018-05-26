<?php
include_once('pdf-templates/tfpdf.php');
const TEMPIMGLOC = 'tempimg.png';
class PDF4x6 extends tFPDF
{
	//function to addpage
	public $xfactor=0;
	public $yfactor=0;
	public $fontfactor=1;
	public $to_title_size;
	public $to_content_size;
	public $from_title_size;
	public $from_content_size;
	public $phone_content_size;
	public $tracking_content_size;
	public $is_policy_exist;
	function init($par,$font, $font_size)
	{
		$this->AddPage();
		$font_name;
		if($font == 'big5') {
			$font_name = 'big5.ttf';
		} else {
			$font_name = 'arial.ttf';
		}
		$this->AddFont('DejaVu','',$font_name,true);
		$this->SetFont('DejaVu','',8*$this->xfactor);
		$this->xfactor=$par+0.18;
		$this->is_policy_exist = false;
		if($this->xfactor>1) {
			$this->yfactor=2.5;
			$this->fontfactor=2;
		} else {
			$this->yfactor=2.5;
			$this->fontfactor=1.5;
		}
		$this->font_size($font_size);
	}

	//function to add logo
	function addImage($img, $dimensions)
	{
		$image_path = WP_CONTENT_DIR.'/'.strstr($img,'uploads');
		$image_format = strtolower(pathinfo($img, PATHINFO_EXTENSION));
		$this->Image($image_path,10,10,$dimensions['width']*0.264,$dimensions['height']*0.264, $image_format);
	}

	//function to add company name
	function addCompanyname($companyname)
	{
		$this->SetFontSize($this->to_content_size*$this->fontfactor);
		$this->Cell(50*$this->xfactor,20,__($companyname,'wf-woocommerce-packing-list'),0,0,'R');
		$this->Ln(4);
	}

	//function to add shipping to address
	function addShippingToAddress($addr, $order_additional_information, $data_matrix, $contact_number, $shippinglabel_contactno_email,$order_id = '')
	{
		$i=28*$this->yfactor;
		$x=20*$this->xfactor;
		$this->setXY($x,25*$this->yfactor);
		$this->SetFontSize($this->to_title_size*$this->fontfactor);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__('To','wf-woocommerce-packing-list'),0,0,'L');
		$this->SetFontSize($this->to_content_size*$this->fontfactor);
		$this->setXY($x,$i);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['first_name'].' '.$addr['last_name'],'wf-woocommerce-packing-list'),0,0,'L');
		if($addr['company'] != '') {
			$this->Ln(5);
			$this->setyval($x);
			$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['company'],'wf-woocommerce-packing-list'),0,0,'L');
		}
		$this->Ln(5);
		$this->setyval($x);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['address_1'],'wf-woocommerce-packing-list'),0,0,'L');
		if($addr['address_2'] != '') {
			$this->Ln(5);
			$this->setyval($x);
			$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['address_2'],'wf-woocommerce-packing-list'),0,0,'L');
		}
		$this->Ln(5);
		$this->setyval($x);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['city'].', '.$addr['state'].' - '.$addr['postcode'],'wf-woocommerce-packing-list'),0,0,'L');
		$this->Ln(5);
		$this->setyval($x);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($addr['country'],'wf-woocommerce-packing-list'),0,0,'L');
		if (in_array('contact_number',$shippinglabel_contactno_email)) {
			$this->Ln(5);
			$this->setXY($x,($this->getY()+6));
			$this->SetFontSize($this->phone_content_size*$this->fontfactor);
			$this->Cell(65*$this->xfactor,5*$this->yfactor,__('Ph no:'.$addr['phone'],'wf-woocommerce-packing-list'),0,0,'L');
		}		

		if (($data_matrix == 'Yes' ) && $order_id !='' && get_post_meta($order_id,'_tracking_number',true)) {
			include_once('picqer/BarcodeGenerator.php');
			include_once('picqer/BarcodeGeneratorPNG.php');
			include_once('picqer/BarcodeGeneratorSVG.php');
			include_once('picqer/BarcodeGeneratorJPG.php');
			include_once('picqer/BarcodeGeneratorHTML.php');
			$generator = new BarcodeGeneratorPNG();
			$decodedImg = $generator->getBarcode(get_post_meta($order_id,'_tracking_number',true), $generator::TYPE_CODE_128);
			//  Check if image was properly decoded
			$this->Ln(10);
			$this->setXY($x-5,($this->getY()+6));
			$this->SetFontSize($this->tracking_content_size*$this->fontfactor);
			$this->Cell(67*$this->xfactor,5*$this->yfactor,__('Tracking Number :'.get_post_meta($order_id,'_tracking_number',true),'wf-woocommerce-packing-list'),0,0,'L');
			$this->Ln(15);
			if( $decodedImg!==false )
			{
				//  Save image to a temporary location
				if( file_put_contents(TEMPIMGLOC,$decodedImg)!==false )
				{
					$imagedata = @getimagesize(TEMPIMGLOC);
					$this->Image(TEMPIMGLOC,20*$this->xfactor,($this->getY()),$imagedata[0]*.2,$imagedata[1]*.2);
					//  Delete image from server
					unlink(TEMPIMGLOC);
				}
			}
		}
	}

	//function to set XY
	function setyval($x)
	{
		$this->setXY($x,($this->getY()+3));
	}

	//function to add from address
	function addShippingFromAddress($ship_from_address, $orderdata, $order_additional_information)
	{
		$x=12;
		$this->setXY($x,($this->getY()+(8*$this->yfactor)));
		$i=$this->getY()+(2*$this->yfactor);
		$this->SetFontSize($this->from_title_size*$this->fontfactor);
		$this->Cell(35*$this->xfactor,5*$this->yfactor,__('FROM','wf-woocommerce-packing-list'),0,0,'L');
		$this->SetFontSize($this->from_content_size*$this->fontfactor);
		if(!empty($orderdata)) {
			$this->Cell(22*$this->xfactor,5*$this->yfactor,__('Order Number ','wf-woocommerce-packing-list'),0,0,'L');
			$this->Cell(1*$this->xfactor,5*$this->yfactor,__(': ','wf-woocommerce-packing-list'),0,0,'R');
			$this->Cell(10*$this->xfactor,5*$this->yfactor,__($orderdata['order_id'],'wf-woocommerce-packing-list'),0,0,'L');
			$this->setXY($x,$i);
			$this->Cell(35*$this->xfactor,5*$this->yfactor,__($ship_from_address['sender_name'],'wf-woocommerce-packing-list'),0,0,'L');
			$this->Cell(22*$this->xfactor,5*$this->yfactor,__('Weight ','wf-woocommerce-packing-list'),0,0,'L');
			$this->Cell(1*$this->xfactor,5*$this->yfactor,__(': ','wf-woocommerce-packing-list'),0,0,'R');
			$this->Cell(10*$this->xfactor,5*$this->yfactor,__($orderdata['weight'],'wf-woocommerce-packing-list'),0,0,'L');
		}
		$this->Ln(1);
		$this->setyval($x);
		$this->Cell(35*$this->xfactor,5*$this->yfactor,__($ship_from_address['sender_address_line1'],'wf-woocommerce-packing-list'),0,0,'L');
		if (key_exists('ship_date', $order_additional_information)) {
			$this->Cell(22*$this->xfactor,5*$this->yfactor,__('Ship Date ','wf-woocommerce-packing-list'),0,0,'L');
			$this->Cell(1*$this->xfactor,5*$this->yfactor,__(': ','wf-woocommerce-packing-list'),0,0,'R');
			$this->Cell(10*$this->xfactor,5*$this->yfactor,__($order_additional_information['ship_date'],'wf-woocommerce-packing-list'),0,0,'L');
		}
		if($ship_from_address['sender_address_line2']!='')
		{
			$this->Ln(1);
			$this->setyval($x);
			$this->Cell(65*$this->xfactor,5*$this->yfactor,__($ship_from_address['sender_address_line2'],'wf-woocommerce-packing-list'),0,0,'L');
		}
		$this->Ln(1);
		$this->setyval($x);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($ship_from_address['sender_city'],'wf-woocommerce-packing-list'),0,0,'L');
		$this->Ln(1);
		$this->setyval($x);
		$this->Cell(65*$this->xfactor,5*$this->yfactor,__($ship_from_address['sender_country'].' - '. $ship_from_address['sender_postalcode'],'wf-woocommerce-packing-list'),0,0,'L');
		$this->setyval($x);
	}

	//function to add customized return policy, company policy, etc...
	function addPolicies($policy)
	{
		$this->is_policy_exist = true;
		$this->SetFontSize($this->from_content_size*$this->fontfactor);
		$this->setXY(12,($this->getY()+(10*$this->yfactor)));
		$policy_content = $this->create_url_content($policy);
		$this->Write(3*$this->yfactor,__(strip_tags($policy_content['initial_content']),'wf-woocommerce-packing-list'));
		$this->SetTextColor(0,0,255);
		$this->Write(3*$this->yfactor,__(strip_tags($policy_content['url_name']),'wf-woocommerce-packing-list'),$policy_content['url']);
		$this->SetTextColor(0,0,0);
		$this->Write(3*$this->yfactor,__(strip_tags($policy_content['end_content']),'wf-woocommerce-packing-list'));
		$this->setX(12);
		$this->MultiCell(80*$this->xfactor,3*$this->yfactor,'','B','L',0);
	}

	//function to add customized footer
	function addFooter($footer)
	{
		if($this->is_policy_exist) {
			$this->setXY(12,($this->getY()+($this->yfactor)));
		} else {
			$this->setXY(12,($this->getY()+(10*$this->yfactor)));
		}
		$this->SetFontSize($this->from_content_size*$this->fontfactor);
		$footer_content = $this->create_url_content($footer);
		$this->Write(3*$this->yfactor,__(strip_tags($footer_content['initial_content']),'wf-woocommerce-packing-list'));
		$this->SetTextColor(0,0,255);
		$this->Write(3*$this->yfactor,__(strip_tags($footer_content['url_name']),'wf-woocommerce-packing-list'),$footer_content['url']);
		$this->SetTextColor(0,0,0);
		$this->Write(3*$this->yfactor,__(strip_tags($footer_content['end_content']),'wf-woocommerce-packing-list'));
	}
	
	//function to set font size
	function font_size($font_size)
	{
		switch($font_size) {
			case 'large':
				$this->to_title_size = 15;
				$this->to_content_size = 11;
				$this->from_title_size = 10;
				$this->from_content_size = 6;
				$this->phone_content_size = 9;
				$this->tracking_content_size = 7;
				break;
			case 'small':
				$this->to_title_size = 13;
				$this->to_content_size = 9;
				$this->from_title_size = 8;
				$this->from_content_size = 4;
				$this->phone_content_size = 7;
				$this->tracking_content_size = 5;
				break;
			default:
				$this->to_title_size = 14;
				$this->to_content_size = 10;
				$this->from_title_size = 9;
				$this->from_content_size = 5;
				$this->phone_content_size = 8;
				$this->tracking_content_size = 6;
				break;
		}
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
}

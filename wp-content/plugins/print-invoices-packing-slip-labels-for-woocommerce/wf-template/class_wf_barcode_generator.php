<?php

class wf_barcode_generator{
	public function __construct()
	{

	}
	public function wf_generate_barcode($invoice_number)
	{
		include_once('picqer/BarcodeGenerator.php');
		include_once('picqer/BarcodeGeneratorPNG.php');
		include_once('picqer/BarcodeGeneratorSVG.php');
		include_once('picqer/BarcodeGeneratorJPG.php');
		include_once('picqer/BarcodeGeneratorHTML.php');
		$generator = new BarcodeGeneratorPNG();
		$barcode_data = '<div style="padding: 2%;width:100%;text-align: center;"><img src="data:image/png;base64,' . base64_encode($generator->getBarcode($invoice_number, $generator::TYPE_CODE_128)) . '"></div>';
		return $barcode_data;

	}
	
}
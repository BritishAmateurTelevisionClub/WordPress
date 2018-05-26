<?php
Class MyMethod3PDFStamp extends FPDF
{
    var $footer_text = "Footer Text Not Specified";
    var $color_red = 0;
    var $color_green = 0;
    var $color_blue = 0;
    var $font_family = "arial";
    var $font_style = "I";
    var $font_size = 10;
    var $alignment = 'C';
    var $distance_from_bottom = -45;//-15;
    var $line_space = 15;//5;
    var $start_page_number = 1;
    
    function loadUTFFont()
    {
		$this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true); 
		$this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
		$this->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
    }    
	function setupFooterText($footer_text)
	{
		$this->footer_text = $footer_text;
	}
    function setUpStyleAndLayoutData($color_red=0,$color_green=0,$color_blue=0,$font_family="arial",$font_style="I",$font_size=10,$alignment='C',$distance_from_bottom=-45,$line_space=15)
    {
        $this->color_red = $color_red;
        $this->color_green = $color_green;
        $this->color_blue = $color_blue;
        $this->font_family = $font_family;
        $this->font_style = $font_style;
        $this->font_size = $font_size;
        $this->alignment = $alignment;
        $this->distance_from_bottom = $distance_from_bottom*3;
        $this->line_space = $line_space*3;
    }        
    function Footer()
    {
        //Set font color
        $this->SetTextColor($this->color_red, $this->color_green, $this->color_blue);
        //Go to specified mm from bottom
        $this->SetY($this->distance_from_bottom);
        
        //Select specified font family and size
        $this->SetFont($this->font_family,$this->font_style,$this->font_size);
        
        //Print the string    
		$this->MultiCell(0, $this->line_space, $this->footer_text, 0, $this->alignment, false);
    }	
}

Class MyMethod3PDFStampChinese extends PDF_Chinese
{
    var $footer_text = "Footer Text Not Specified";
    var $color_red = 0;
    var $color_green = 0;
    var $color_blue = 0;
    var $font_family = "arial";
    var $font_style = "I";
    var $font_size = 10;
    var $alignment = 'C';
    var $distance_from_bottom = -45;//-15;
    var $line_space = 15;//5;
    var $start_page_number = 1;
    
    function loadUTFFont()
    {
		$this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true); 
		$this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
		$this->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
    }    
	function setupFooterText($footer_text)
	{
		$this->footer_text = $footer_text;
	}
    function setUpStyleAndLayoutData($color_red=0,$color_green=0,$color_blue=0,$font_family="arial",$font_style="I",$font_size=10,$alignment='C',$distance_from_bottom=-45,$line_space=15)
    {
        $this->color_red = $color_red;
        $this->color_green = $color_green;
        $this->color_blue = $color_blue;
        $this->font_family = $font_family;
        $this->font_style = $font_style;
        $this->font_size = $font_size;
        $this->alignment = $alignment;
        $this->distance_from_bottom = $distance_from_bottom*3;
        $this->line_space = $line_space*3;
    }        
    function Footer()
    {
        //Set font color
        $this->SetTextColor($this->color_red, $this->color_green, $this->color_blue);
        //Go to specified mm from bottom
        $this->SetY($this->distance_from_bottom);
        
        //Select specified font family and size
        $this->SetFont($this->font_family,$this->font_style,$this->font_size);
        
        //Print the string    
		$this->MultiCell(0, $this->line_space, $this->footer_text, 0, $this->alignment, false);
    }	
}

Class MyMethod3PDFStampJapanese extends PDF_Japanese
{
    var $footer_text = "Footer Text Not Specified";
    var $color_red = 0;
    var $color_green = 0;
    var $color_blue = 0;
    var $font_family = "arial";
    var $font_style = "I";
    var $font_size = 10;
    var $alignment = 'C';
    var $distance_from_bottom = -45;//-15;
    var $line_space = 15;//5;
    var $start_page_number = 1;
    
    function loadUTFFont()
    {
		$this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true); 
		$this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
		$this->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
    }    
	function setupFooterText($footer_text)
	{
		$this->footer_text = $footer_text;
	}
    function setUpStyleAndLayoutData($color_red=0,$color_green=0,$color_blue=0,$font_family="arial",$font_style="I",$font_size=10,$alignment='C',$distance_from_bottom=-45,$line_space=15)
    {
        $this->color_red = $color_red;
        $this->color_green = $color_green;
        $this->color_blue = $color_blue;
        $this->font_family = $font_family;
        $this->font_style = $font_style;
        $this->font_size = $font_size;
        $this->alignment = $alignment;
        $this->distance_from_bottom = $distance_from_bottom*3;
        $this->line_space = $line_space*3;
    }        
    function Footer()
    {
        //Set font color
        $this->SetTextColor($this->color_red, $this->color_green, $this->color_blue);
        //Go to specified mm from bottom
        $this->SetY($this->distance_from_bottom);
        
        //Select specified font family and size
        $this->SetFont($this->font_family,$this->font_style,$this->font_size);
        
        //Print the string    
		$this->MultiCell(0, $this->line_space, $this->footer_text, 0, $this->alignment, false);
    }	
}
?>
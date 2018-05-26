<?php
require_once('lib/fpdf/fpdf.php');
require_once('lib/fpdi/FPDI_Protection.php');

class MyWpFPDI extends FPDI_Protection{

    var $footer_text;
    var $src_file;
    var $src_file_name;
    var $dest_dir;

    var $color_red = 0;
    var $color_green = 0;
    var $color_blue = 0;
    var $font_family = "arial";
    var $font_style = "I";
    var $font_size = 10;
    var $alignment = 'C';
    var $distance_from_bottom = -15;
    var $line_space = 5;
    var $start_page_number = 1;
    
    var $allow_print = true;
    var $allow_modify = true;
    var $allow_copy = true;
    var $userpass = '';
    var $ownerpass = '';

    function setFooterText($line)
    {
        $this->footer_text = $line;
    }
    function setSrcFile($src_file)
    {
        $this->src_file = $src_file;
    }
    function setSrcFileName($src_file)
    {
    	$path_parts = pathinfo($src_file);
    	$this->src_file_name = $path_parts['basename'];
    }
    function setDestDir($destination_dir)
    {
    	$this->dest_dir = $destination_dir;
    }

    function setUpSecurityData($allow_print,$allow_modify,$allow_copy,$userpass='',$ownerpass='')
    {
    	$this->allow_print = $allow_print;
    	$this->allow_modify = $allow_modify;
    	$this->allow_copy = $allow_copy;
    	$this->userpass = $userpass;
    	$this->ownerpass = $ownerpass;
    }
    
    function setUpRequiredData($line,$src_file,$destination_dir='',$start_page_number=1)
    {
        $this->setFooterText($line);
        $this->setSrcFile($src_file);
        $this->setSrcFileName($src_file);        
        if(!empty($destination_dir))
        {
        	$this->setDestDir($destination_dir);
        } 
        $this->start_page_number = $start_page_number;       
    }
    
    function setUpStyleAndLayout($color_red=0,$color_green=0,$color_blue=0,$font_family="arial",$font_style="I",$font_size=10,$alignment='C',$distance_from_bottom=-15,$line_space=5)
    {
        $this->color_red = $color_red;
        $this->color_green = $color_green;
        $this->color_blue = $color_blue;
        $this->font_family = $font_family;
        $this->font_style = $font_style;
        $this->font_size = $font_size;
        $this->alignment = $alignment;
        $this->distance_from_bottom = $distance_from_bottom;
        $this->line_space = $line_space;
    }
    
    function loadUTFFont()
    {
		$this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true); 
		$this->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
		$this->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
    }
        
    function Footer()
    {
        /*
         * If you need to change text size, color, position etc.
         * check out the online manual for FPDF function reference
         * http://www.fpdf.org/en/doc/index.php
         */

        //Set font color
        $this->SetTextColor($this->color_red, $this->color_green, $this->color_blue);
        //Go to specified mm from bottom
        $this->SetY($this->distance_from_bottom);
        
        //Select specified font family and size
        $this->SetFont($this->font_family,$this->font_style,$this->font_size);
        
        //Print the string    
        if($this->PageNo() >= $this->start_page_number)  
        { 
        	$this->MultiCell(0, $this->line_space, $this->footer_text, 0, $this->alignment, false);
        }
    }

    function commitStamp()
    {
        global $argv;
        $output = "";

        $this->AddPage();
        $pagecount = $this->setSourceFile($this->src_file);

        if($pagecount === 0)
        {
            $output .= "Error! Could not find source PDF file: ".$this->src_file;
            return $output;
        }

        for($i = 1; $i <= $pagecount; $i++)
        {
            if($i !== 1)
            {
                $this->AddPage();
            }
            $tpl = $this->importPage($i);
           	$this->useTemplate($tpl,null,null,0,0,true);
        }
        
		/*** Set the protection. Available values array('print','modify','copy','annot-forms');	***/	
		$permission = array();
		if($this->allow_print)
			array_push($permission, 'print');
		if($this->allow_modify)
			array_push($permission, 'modify');
		if($this->allow_copy)
			array_push($permission, 'copy');	
				
		if(!empty($this->ownerpass))
		{
			$this->SetProtection($permission,$this->userpass,$this->ownerpass);
		}	
		else		
		{
			$this->SetProtection($permission,$this->userpass,null);
    	}
		/*** end of protection setup ***/
        
        $unique_file_suffix = uniqid();
        if(empty($this->dest_dir))
        {
        	$output_file = str_replace('.pdf', '', $this->src_file).'_'.$unique_file_suffix.'.pdf';
        }
        else
        {
        	$output_file_name = str_replace('.pdf', '', $this->src_file_name).'_'.$unique_file_suffix.'.pdf'; 
        	$output_file = $this->dest_dir.'/'.$output_file_name;
        }
        $this->Output($output_file, "F");

        $output .= $output_file;
        return $output;
    }
}
?>

<?php

class PDF_Stamper_Config
{
	var $configs;
	static $_this;
	function loadConfig(){
		$this->configs = get_option('wp_pdf_stamper_plugin_configs');
	}

    function getValue($key){
    	return isset($this->configs[$key])?$this->configs[$key] : '';
    }
    
    function setValue($key, $value){
    	$this->configs[$key] = $value;
    }
    function saveConfig(){
    	update_option('wp_pdf_stamper_plugin_configs', $this->configs);
    }
    function addValue($key, $value){
    	if(!is_array($this->configs)){return;}
    	if (array_key_exists($key, $this->configs)){
    		//Don't update the value for this key
    	}
    	else{
    		//It is safe to update the value for this key
    		$this->configs[$key] = $value;
    	}    	
    }
    static function getInstance(){
    	if(empty(self::$_this)){
    		self::$_this = new PDF_Stamper_Config();
    		self::$_this->loadConfig();
    		return self::$_this;
    	}
    	return self::$_this;
    }	
}

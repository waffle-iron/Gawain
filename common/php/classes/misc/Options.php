<?php

require_once(__DIR__ . '/../../constants/global_defines.php');

class Options {
	
	private $optionsArray;
	
	public function __construct() {
		$this->optionsArray = parse_ini_file(CONFIG_DIR . 'options.ini', FALSE);
	}
	
	
	public function get($str_Key) {
		return $this->optionsArray[$str_Key];
	}
	
	
	public function set($str_Key, $str_Value) {
		// TODO: add 'set' method to options
	}

}

?>
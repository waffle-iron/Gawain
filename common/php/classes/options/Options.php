<?php

require_once(__DIR__ . '/../../constants/global_defines.php');

class options {
	
	private $optionsArray;
	
	public function __construct() {
		$this->optionsArray = json_decode(file_get_contents(CONFIG_DIR . 'options.json'), true);
	}

	// Get options as object
	public function getValue() {
		return $this->optionsArray;
	}

	// Get options as JSON string
	public function getStringValue() {
		return json_encode($this->optionsArray);
	}

	// Set the options file content
	public function setValue($str_JsonString) {
		return file_put_contents(CONFIG_DIR . 'options.json', $str_JsonString);
	}

}

?>
<?php

class options {

	// Configuration file path
	private $str_OptionsFilePath = '/../../../config/options.json';

	// Get options as object
	function getValue() {
		return json_decode($this->getStringValue(), true);
	}

	// Get options as JSON string
	function getStringValue() {
		return file_get_contents(__DIR__ . $this->str_OptionsFilePath);
	}

	// Set the options file content
	function setValue($str_JsonString) {
		return file_put_contents(__DIR__ . $this->str_OptionsFilePath, $str_JsonString);
	}

}

?>
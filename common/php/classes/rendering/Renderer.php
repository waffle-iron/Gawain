<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');



class Renderer {

	// Data type (entity or report)
	private $dataType;


	// Data type ID (entity name or report ID)
	private $dataTypeID;


	// Renderer dataset
	private $dataset;


	// Renderer engine
	private $engine;


	// Current domain
	private $domainID;




	public function __construct($int_DomainID, $str_DataType, $str_DataTypeID) {
		$this->domainID = $int_DomainID;
		$this->dataType = $str_DataType;
		$this->dataTypeID = $str_DataTypeID;
	}



	public function importDataset($obj_Dataset) {
		$this->dataset = $obj_Dataset;
	}



	public function setOutputFormat($str_OutputFormat) {
		$this->engine = rendering_engine_autodefine($this->domainID, $this->dataType, $this->dataTypeID, $str_OutputFormat);
		$this->engine->importDataset($this->dataset);
	}


	public function setTemplate($str_Template) {
		$this->engine->setTemplate($str_Template);
	}


	public function render() {
		$this->engine->render();
	}

}
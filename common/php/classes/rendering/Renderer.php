<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');



class Renderer {

	// Internal DB handler
	private $dbHandler;


	// Internal options
	private $options;


	// Renderer dataset
	private $dataset;


	// Renderer engine
	private $engine;


	// Rendering template
	private $renderingTemplate;


	// Renderer output format
	private $outputFormat;



	public function __construct($obj_Dataset) {
		$this->dataset = $obj_Dataset;
	}



	public function setOutputFormat($str_OutputFormat) {
		$this->engine = rendering_engine_autodefine($str_OutputFormat);
	}


	public function setTemplate($str_Template) {
		$this->engine->setTemplate($str_Template);
	}


	public function render() {
		$this->engine->render();
	}

}
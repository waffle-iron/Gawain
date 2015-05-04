<?php

require_once(__DIR__ . '/../../constants/global_defines.php');


abstract class RenderingEngine {

	// Input dataset
	protected $dataset;


	// Rendering template
	protected $template;


	// Internal working directory
	protected $workingDir;


	/** Constructor
	 *
	 */
	public function __construct() {

	}


	/** Sets the internal dataset
	 *
	 * @param object $obj_Dataset
	 */
	public function importDataset($obj_Dataset) {
		$this->dataset = $obj_Dataset;
	}


	/** Sets the current template for rendering
	 *
	 * @param string $str_TemplateName
	 */
	abstract function setTemplate($str_TemplateName);


	/** Renders the given dataset
	 *
	 * @return mixed
	 */
	abstract function render();

}
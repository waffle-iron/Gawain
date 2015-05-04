<?php

require_once('../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');


abstract class Renderer {

	// Internal DB handler
	protected $dbHandler;


	// Internal options
	protected $options;


	// Input dataset
	protected $dataset;


	// Rendering template
	protected $template;


	/** Constructor
	 *
	 * @param object $obj_Dataset
	 */
	public function __construct($obj_Dataset) {
		$this->dataset = $obj_Dataset;
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
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
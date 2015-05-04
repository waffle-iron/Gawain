<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');


abstract class RenderingEngine {

	// Input dataset
	public $dataset;


	// Rendering template
	protected $template;


	// Internal working directory
	protected $workingDir;


	// Domain ID
	protected $domainID;


	// Data type (entity or report)
	protected $dataType;


	// Data type ID
	protected $dataTypeID;


	// Options
	protected $options;


	// DB Handler
	protected $dbHandler;


	/** Constructor
	 *
	 * @param integer $int_DomainID
	 * @param string $str_DataType
	 * @param string $str_DataTypeID
	 */
	public function __construct($int_DomainID, $str_DataType, $str_DataTypeID) {
		$this->domainID = $int_DomainID;
		$this->dataType = $str_DataType;
		$this->dataTypeID = $str_DataTypeID;

		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
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
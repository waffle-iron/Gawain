<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_VENDOR_DIR . 'Twig/Autoloader.php');


/**
 * Class Renderer
 */
abstract class Renderer {

	// Rendering style
	protected $style;


	// Internal Twig Loader
	protected $twigLoader;


	// Internal Twig environment
	protected $twig;


	// Internal Twig Template
	protected $twigTemplate;


	// Renderer data
	public $data;


	// Template file path
	protected $templatePath;


	/** Constructor
	 *
	 */
	public function __construct() {

		Twig_Autoloader::register();
		$this->twigLoader = new Twig_Loader_Filesystem(TEMPLATE_DIR);
		$this->twig = new Twig_Environment($this->twigLoader);

	}


	/** Imports the data to be rendered
	 *
	 * @param array $arr_Dataset
	 */
	public function importData($arr_Dataset) {

		$this->data = $arr_Dataset;

	}


	/** Sets the template to be used for document generation
	 *
	 * @param string $str_TemplatePath
	 */
	public function setTemplate($str_TemplatePath) {

		$this->templatePath = $str_TemplatePath;

	}


	/** Sets the style for the rendering
	 *
	 * @param string $str_Style
	 */
	public function setStyle($str_Style) {

		$this->style = $str_Style;

	}


	/** Renders the given data
	 *
	 * @return mixed
	 */
	abstract function render();

}
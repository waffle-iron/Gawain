<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_VENDOR_DIR . 'Twig/Autoloader.php');


/**
 * Class Renderer
 */
abstract class Renderer
{

    // Rendering style
    public $data;


    // Internal Twig Loader
    protected $style;


    // Internal Twig environment
    protected $twigLoader;


    // Internal Twig Template
    protected $twig;


    // Renderer data
    protected $twigTemplate;


    // Template file path
    protected $templatePath;


    // Rendering format
    protected $renderingFormat;


    /** Constructor
     *
     */
    public function __construct()
    {

        Twig_Autoloader::register();
        $this->twigLoader = new Twig_Loader_Filesystem(TEMPLATES_DIR);
        $this->twig = new Twig_Environment($this->twigLoader);

    }


    /** Add a path (typically an entity custom path) to the loader
     *
     * @param string $str_TemplatePath
     *
     * @throws Twig_Error_Loader
     */
    public function addTemplatePath($str_TemplatePath)
    {

        $this->twigLoader->addPath($str_TemplatePath);

    }


    /** Imports the data to be rendered
     *
     * @param array $arr_Dataset
     */
    public function importData($arr_Dataset)
    {

        $this->data = $arr_Dataset;

    }


    /** Sets the template to be used for document generation
     *
     * @param string $str_TemplatePath
     */
    public function setTemplate($str_TemplatePath)
    {

        $this->templatePath = $str_TemplatePath;

    }


    /** Sets the style for the rendering
     *
     * @param string $str_Style
     */
    public function setStyle($str_Style)
    {

        $this->style = $str_Style;

    }


    /** Renders the given data
     *
     * @return mixed
     */
    abstract function render();


    /** Provides a basic rendering method to be used in more complex render implementations
     *
     * @throws Exception
     */
    protected function basicRender()
    {

        if (!isset($this->renderingFormat)) {
            throw new Exception('Rendering format not defined');
        }

        if (!isset($this->style)) {
            throw new Exception('Rendering style not defined');
        }

        $this->twigLoader->addPath(TEMPLATES_DIR . $this->renderingFormat . '/' . $this->style);

        return $this->twig->render($this->templatePath, $this->data);

    }

}
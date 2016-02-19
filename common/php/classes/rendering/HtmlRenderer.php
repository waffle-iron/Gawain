<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'rendering/Renderer.php');


class HtmlRenderer extends Renderer
{

    public function __construct()
    {

        // Sets the rendering format to HTML and executes the parent costructor
        $this->renderingFormat = 'html';
        parent::__construct();
    }


    public function render()
    {

        // Simply outputs the rendered code
        return $this->basicRender();

    }

}
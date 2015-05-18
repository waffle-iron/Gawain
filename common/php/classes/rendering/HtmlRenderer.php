<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_ABSTRACTS_DIR . 'rendering/Renderer.php');



class HtmlRenderer extends Renderer {

	public function render() {

		return $this->basicRender();

	}

}
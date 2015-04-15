<?php 

require_once(__DIR__ . '/../../constants/global_defines.php');


class Jierarchy {
	// Dependency Source parsed from file
	private $depSource;
	
	// Javascript paths
	private $JsPaths = array();
	
	// CSS paths
	private $CssPaths = array();
	
	
	
	public function __construct($str_DepFilePath) {
		$str_JsonCOntent = file_get_contents($str_DepFilePath);
		$this->depSource = json_decode($str_JsonCOntent, TRUE);
	}
	
	
	
	public function load($arr_LibraryNames) {
		
		$this->calculateDependencies($arr_LibraryNames);
		
		$arr_OutputJs = array();
		$arr_OutputCss = array();
		
		// Foreach loop to create all the script tags
		foreach ($this->JsPaths as $str_JsPath) {
			$arr_OutputJs[] = '<script src="' . $str_JsPath . '"></script>';
		}
		
		// Foreach loop to create all the stylesheet tags
		foreach ($this->CssPaths as $str_CssPath) {
			$arr_OutputCss[] = '<link href="' . $str_CssPath . '" rel="stylesheet" type="text/css">';
		}
		
		
		echo implode(PHP_EOL, $arr_OutputCss);
		echo PHP_EOL;
		echo implode(PHP_EOL, $arr_OutputJs);
	}
	
	
	
	private function calculateDependencies($arr_LibraryNames) {
	
		foreach ($arr_LibraryNames as $str_LibraryName) {
			$arr_CurrentNode = $this->depSource[$str_LibraryName];
				
			if (!empty($arr_CurrentNode['dependencies'])) {
				$this->calculateDependencies($arr_CurrentNode['dependencies']);
			}
			
			if (!empty($arr_CurrentNode['path']['js'])) {
				foreach ($arr_CurrentNode['path']['js'] as $str_JsPath) {
					$this->JsPaths[] = $str_JsPath;
				}
			}
			
			if (!empty($arr_CurrentNode['path']['css'])) {
				foreach ($arr_CurrentNode['path']['css'] as $str_CssPath) {
					$this->CssPaths[] = $str_CssPath;
				}
			}
		}
		
		
		// 'Flip flip' method to get a unique array °_°
		$this->JsPaths = array_merge(array_flip(array_flip($this->JsPaths)));
		$this->CssPaths = array_merge(array_flip(array_flip($this->CssPaths)));
		
	}
}

?>
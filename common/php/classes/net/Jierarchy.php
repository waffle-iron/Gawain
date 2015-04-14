<?php 

require_once(__DIR__ . '/../../constants/global_defines.php');


class Jierarchy {
	// Dependency Source parsed from file
	private $depSource;
	
	// Javascript paths
	private $JsPaths;
	
	// CSS paths
	private $CssPaths;
	
	
	
	public function __construct($str_DepFilePath) {
		$str_JsonCOntent = file_get_contents($str_DepFilePath);
		$this->depSource = json_decode($str_JsonCOntent, TRUE);
	}
	
	
	
	public function load($arr_LibraryNames) {
		
		$this->calculateDependencies($arr_LibraryNames);
		
	}
	
	
	
	private function calculateDependencies($arr_LibraryNames) {
	
		foreach ($arr_LibraryNames as $str_LibraryName) {
			$arr_CurrentNode = $this->depSource[$str_LibraryName];
				
			if (!empty($arr_CurrentNode['dependencies'])) {
				$this->calculateDependencies($arr_CurrentNode['dependencies']);
			}
			
			var_dump($arr_CurrentNode);
		}
		
	}
}

?>
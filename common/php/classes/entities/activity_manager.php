<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'entities/entity_manager.php');

class activity_manager extends entity_manager {

	// Child constructor
	public function __construct($str_SessionID) {

		// Sets entity reference code
		$this->entityCode = 'activity';

		// Call parent constructor
		parent::__construct($str_SessionID);
	}
	

}

?>
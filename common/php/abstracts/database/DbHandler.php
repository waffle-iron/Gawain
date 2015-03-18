<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'options/Options.php');

abstract class DbHandler {

	// Hostname
	protected $hostname;


	// Username
	protected $username;


	// Password
	protected $password;


	// Default DB
	protected $database;


	// Connection Handler
	protected $handler;
	
	
	// Options
	protected $options;




	// Class constructor
	protected function __construct() {
		$this->options = new options();
		
		$obj_DBdata = $this->options->getValue()['environment']['DB'];
		
		$this->hostname = $obj_DBdata['host'];
		$this->username = $obj_DBdata['user'];
		$this->password = $obj_DBdata['pwd'];
		$this->database = $obj_DBdata['dbName'];
	}


	// Execute prepared query and output resultset
	abstract function executePrepared($str_Query, $arr_InputParameters);
	
	
	// Starts a transaction
	abstract function beginTransaction();
	
	
	// Commits a transaction
	abstract function commit();
	
	
	// Rollbacks a transaction
	abstract function rollback();


	// Class destructor
	abstract function __destruct();

}

?>
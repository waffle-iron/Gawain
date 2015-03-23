<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');

abstract class DbHandler {

	// Hostname
	protected $hostname;


	// Username
	protected $username;


	// Password
	protected $password;


	// Default schema
	protected $schema;


	// Connection Handler
	protected $handler;
	
	
	// Options
	protected $options;




	// Class constructor
	protected function __construct() {
		$this->options = new Options();
		
		$this->hostname = $this->options->get('db_host');
		$this->username = $this->options->get('db_user');
		$this->password = $this->options->get('db_pwd');
		$this->schema = $this->options->get('db_schema');
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
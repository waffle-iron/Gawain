<?php

abstract class db_handler {

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




	// Class constructor
	abstract function __construct();


	// Execute prepared query and output resultset
	abstract function execute_prepared($str_Query, $arr_InputParameters);


	// Class destructor
	abstract function __destruct();

}

?>
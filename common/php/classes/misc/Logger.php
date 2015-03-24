<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . '/misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

class Logger {

	// Inner options handler
	private $options;
	
	// DB handler
	private $dbHandler;
	
	// Log table name
	private $logTableName;
	
	// Global log level
	private $logLevel;
	
	// Internal log levels
	private $logLevelsList = array(
			'FATAL ERROR',
			'ERROR',
			'WARNING',
			'INFO',
			'DEBUG'
	);
	
	// Reference entity
	private $entity;
	
	// Reference module
	private $module;
	
	
	
	// Constructor
	public function __construct($str_Entity, $str_Module = NULL) {
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
		
		$this->logTableName = $this->options->get('log_table');
		$this->logLevel = $this->options->get('default_log_level');
		$this->entity = $str_Entity;
		$this->module = $str_Module;
	}
	
	
	
	// Manually sets and override default log level
	public function setLogLevel($str_LogLevel) {
		
		if (!in_array($str_LogLevel, $this->logLevelsList)) {
			throw new Exception('Invalid log level');
			return FALSE;
		} else {
			$this->logLevel = $str_LogLevel;
			return TRUE;
		}
	}
	
	
	// Inserts a log entry
	public function log($str_LogLevel, $str_Message, $str_UserNick = NULL, $str_Hostname = 'localhost') {
		
		$str_Timestamp = get_timestamp();
		$arr_InsertValues = array(
					array($str_Timestamp	=> 's'),
					array($str_LogLevel		=> 's'),
					array($str_Hostname		=> 's'),
					array($str_UserNick		=> 's'),
					array($this->entity		=> 's'),
					array($this->module		=> 's'),
					array($str_Message		=> 's')
			);
		
		$str_LogPrepared =
			'insert into ' . $this->logTableName . 
			' (
					logTimestamp,
					logLevel,
					hostname,
					userNick,
					entity,
					module,
					message
			) values (
					?,
					?,
					?,
					?,
					?,
					?,
					?
			)';
		
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_LogPrepared, $arr_InsertValues);
		$this->dbHandler->commit();
		
		return TRUE;
	}
	
}

?>
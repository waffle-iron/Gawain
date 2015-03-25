<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_ABSTRACTS_DIR . 'database/DbHandler.php');

class MySqlHandler extends DbHandler {

	// Class constructor
	function __construct() {

		parent::__construct();

		try {

			$this->handler = new mysqli($this->hostname, $this->username, $this->password, $this->schema);

			if ($this->handler->connect_error) {
				throw new Exception('DB Connection Error');
			}
			
		} catch (Exception $exception) {
			echo 'Cannot connect to selected DB: ' . $exception->getMessage() . "\n";
		}
		
		$this->handler->autocommit(FALSE);

	}



	// Execute prepared query and output resultset

	/**
	 * The input parameters will be formatted in the following way:
	 * array([0] => array(variable1 => type1), [1] => array(variable2 => type2))
	 * 
	 * @param string $str_Query
	 * @param array $arr_InputParameters
	 * @return object 
	 */
	public function executePrepared($str_Query, $arr_InputParameters) {
		

		/*
		 The input parameters will be formatted in the following way:
		 array([0] => array(variable1 => type1), [1] => array(variable2 => type2))
		 */
		// Prepare the query
		$obj_Prepared = $this->handler->prepare($str_Query);


		if (!empty($arr_InputParameters)) {
			// Dynamic values binding
			$str_ParametersType = '';
			$arr_ParametersValue = array();
			
			$arr_InputParametersKey = array();
			$arr_InputParametersValue = array();
			
			foreach ($arr_InputParameters as $arr_InputParameter) {
				$arr_InputParametersKey[] = implode('', array_keys($arr_InputParameter));
				$arr_InputParametersValue[] = implode('', array_values($arr_InputParameter));
			}
			
			
			for ($int_ParametersCounter = 0; $int_ParametersCounter < sizeof($arr_InputParametersKey); $int_ParametersCounter++) {
				$arr_ParametersValue[] = &$arr_InputParametersKey[$int_ParametersCounter];
				$str_ParametersType .= $arr_InputParametersValue[$int_ParametersCounter];
			}
			
			
			$arr_BindArgument = array_merge(array($str_ParametersType), $arr_ParametersValue);

			call_user_func_array(array($obj_Prepared, 'bind_param'), $arr_BindArgument);
		}

		$obj_Prepared->execute();


		// Output
		$obj_Resultset = $obj_Prepared->get_result();
		
		if (is_object($obj_Resultset)) {
			$obj_Result = $obj_Resultset->fetch_all(MYSQLI_ASSOC);
			$obj_Prepared->free_result();
			$obj_Prepared->close();
		} else {
			$obj_Result = NULL;
		}
		

		return $obj_Result;

	}
	
	
	
	// Starts a transaction
	public function beginTransaction() {
		$this->handler->query('begin transaction');
	}
	
	
	
	// Commits a transaction
	public function commit() {
		$this->handler->query('commit');
	}
	
	
	
	// Rollback a transaction
	public function rollback() {
		$this->handler->query('rollback');
	}



	// Destructor
	function __destruct() {
		$this->handler->close();
	}
}

?>
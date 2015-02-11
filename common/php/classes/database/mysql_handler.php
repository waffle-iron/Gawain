<?php

require_once(__DIR__ . '/../options/options.php');
require_once(__DIR__ . '/../../abstracts/database/db_handler.php');

class mysql_handler extends db_handler {

	// Class constructor
	function __construct() {

		$obj_Options = new options();

		$obj_DBdata = $obj_Options->getValue()['environment']['DB'];

		$str_Hostname = $obj_DBdata['host'];
		$str_Username = $obj_DBdata['user'];
		$str_Password = $obj_DBdata['pwd'];
		$str_Database = $obj_DBdata['dbName'];

		try {

			$this->handler = new mysqli($str_Hostname, $str_Username, $str_Password, $str_Database);

			if ($this->handler->connect_error) {
				throw new Exception('DB Connection Error');
			}
			
		} catch (Exception $exception) {
			echo 'Cannot connect to selected DB: ' . $exception->getMessage() . "\n";
		}

	}



	// Execute prepared query and output resultset
	public function execute_prepared($str_Query, $arr_InputParameters) {

		/*
			The input will be formatted in the following way:
			array(variable1 => type1, variable2 => type2, ...)
		*/

		// Prepare the query
		$obj_Prepared = $this->handler->prepare($str_Query);


		if (!empty($arr_InputParameters)) {
			// Dynamic values binding
			$str_ParametersType = '';
			$arr_ParametersValue = array();
			
			$arr_InputParametersKeys = array_keys($arr_InputParameters);
			
			foreach ($arr_InputParametersKeys as &$str_Value) {
				$arr_ParametersValue[] = &$str_Value;
			}
			
			foreach (array_values($arr_InputParameters) as $str_Type) {
				$str_ParametersType .= $str_Type;
			}
			
			
			$arr_BindArgument = array_merge(array($str_ParametersType), $arr_ParametersValue);

			call_user_func_array(array($obj_Prepared, 'bind_param'), $arr_BindArgument);
		}

		$obj_Prepared->execute();


		// Output
		$obj_Result = $obj_Prepared->get_result()->fetch_all(MYSQLI_ASSOC);
		$obj_Prepared->free_result();
		$obj_Prepared->close();

		return $obj_Result;

	}



	// Destructor
	function __destruct() {
		$this->handler->close();
	}
}

?>
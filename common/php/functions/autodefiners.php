<?php

require_once(__DIR__ . '/../constants/global_defines.php');


/** Automatically selects the right DB handler from options
 * 
 * @param Options $obj_OptionHandler
 * 
 * @return dbHandler
 */
function db_autodefine($obj_OptionHandler) {
	
	$str_DbType = $obj_OptionHandler->get('db_type');
	$obj_Return = NULL;
	
	switch($str_DbType) {
		case 'MySQL':
			require_once(PHP_CLASSES_DIR . 'database/MySqlHandler.php');
			$obj_Return = new MySqlHandler;
			break;

		default:
			$obj_Return = NULL;
			break;
	}

	return $obj_Return;
}





function rendering_engine_autodefine($str_OutputFormat) {
	$obj_Return = NULL;

	switch($str_OutputFormat) {
		case 'html':
			require_once(PHP_CLASSES_DIR . 'rendering/HtmlRenderingEngine.php');
			$obj_Return = new HtmlRenderingEngine();
			break;

		case 'json':
			break;

		case 'php':
			break;

		case 'pdf':
			break;

		case 'excel':
			break;

		default:
			$obj_Return = NULL;
	}

	return $obj_Return;
}
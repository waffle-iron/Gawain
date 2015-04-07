<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');

// Controller for activities
$obj_Activity = new Activity($_COOKIE['GawainSessionID']);

// Checks for request method
switch ($_SERVER['REQUEST_METHOD']) {

	// In case of GET, it is simply a read command
	case 'GET':
		$str_ID = (isset($_GET['ID']) ? $_GET['ID'] : NULL);
		$str_RenderingType = (isset($_GET['renderingType']) ? $_GET['renderingType'] : NULL);
		$str_OutputFormat = (isset($_GET['outputFormat']) ? $_GET['outputFormat'] : NULL);
		
		$str_Response = $obj_Activity->readByID($str_ID, $str_RenderingType, $str_OutputFormat);
		break;
}

?>
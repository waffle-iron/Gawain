<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

// Controller for activities

// If the cookies are not set, the request is automatically aborted
if (isset($_COOKIE['GawainSessionID']) && isset($_COOKIE['GawainUser'])) {
	$str_SessionID = $_COOKIE['GawainSessionID'];
	$str_User = $_COOKIE['GawainUser'];
	
	// If the user authentication is not valid, the request is automatically aborted
	$obj_UserAuthManager = new UserAuthManager($str_User);
	
	if (!$obj_UserAuthManager->isAuthenticated($str_SessionID)) {
		header('Gawain-Response: Unauthorized', 0, 401);
		exit();
	}
	
 } else {
 	header('Gawain-Response: Unauthorized', 0, 401);
 	exit();
 }
 
 $obj_Activity = new Activity($str_SessionID);

// Checks for request method
switch ($_SERVER['REQUEST_METHOD']) {

	// In case of GET, it is simply a read command
	case 'GET':
		$str_ID = (isset($_GET['ID']) ? $_GET['ID'] : NULL);
		$str_RenderingType = (isset($_GET['renderingType']) ? $_GET['renderingType'] : NULL);
		$str_OutputFormat = (isset($_GET['outputFormat']) ? $_GET['outputFormat'] : NULL);
		
		$str_Response = $obj_Activity->read($str_ID, $str_RenderingType, $str_OutputFormat);
		break;
		
		
	// In case of POST, multiple choices are available
	case 'POST':
		$str_ID = (isset($_POST['ID']) ? $_POST['ID'] : NULL);
		$str_Method = (isset($_POST['method']) ? $_POST['method'] : NULL);
		$arr_Data = (isset($_POST['data']) ? json_decode($_POST['method']) : NULL);
		$str_Response = NULL;
		
		// Calls the class methods
		switch($str_Method) {
			case 'insert':
				$str_Response = $obj_Activity->insert($arr_Data);
				break;
				
			case 'update':
				if ($str_ID !== NULL) {
					$str_Response = $obj_Activity->update($str_ID, $arr_Data);
				} else {
					header('Gawain-Response: Malformed request', 0, 400);
				}
				break;
				
			case 'delete':
				if ($str_ID !== NULL) {
					$str_Response = $obj_Activity->delete($str_ID);
				} else {
					header('Gawain-Response: Malformed request', 0, 400);
				}
				break;
		}
		
	default:
		header('Gawain-Response: Malformed request', 0, 400);
		break;
}

echo $str_Response;

?>
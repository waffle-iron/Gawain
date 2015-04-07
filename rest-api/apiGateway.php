<?php 

require_once(__DIR__ . '/../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Curl.php');

use \Curl\Curl;

// Parses API URL and redirects the request to the proper interface

// Get request data, including cookies
$str_ServerName = $_SERVER['HTTP_HOST'];
$str_RequestURL = $_SERVER['REQUEST_URI'];
$str_RequestMethod = $_SERVER['REQUEST_METHOD'];

if (isset($_COOKIE['GawainSessionID']) && isset($_COOKIE['GawainUser'])) {
	$str_SessionID = $_COOKIE['GawainSessionID'];
	$str_User = $_COOKIE['GawainUser'];
} else {
	header('Gawain-Response: Unauthorized', 0, 401);
}



// Parse the URL with regex to get entity, ID and method
$rgx_UrlPattern ='/(.+)\/rest-api\/(\w+)\/?(\d*)\/?(\w*)/';
preg_match($rgx_UrlPattern, $str_RequestURL, $arr_ParsedPath); 

$str_ServerURL = $arr_ParsedPath[1];
$str_Entity = $arr_ParsedPath[2];
$int_ID = $arr_ParsedPath[3];
$str_Method = $arr_ParsedPath[4];


// Get the request body if the requets method is POST and rewrite it
if ($str_RequestMethod == 'POST') {
	$str_RequestBody = file_get_contents('php://input');
	
	$arr_RedirectFields = array(
			'ID'		=>	$int_ID,
			'method'	=>	$str_Method,
			'data'		=>	$str_RequestBody
	);
	
	
} elseif ($str_RequestMethod == 'GET') {
	$arr_RedirectFields = array('ID'	=>	$int_ID);
}


// Redirect the request
$str_RedirectUrl = $str_ServerName . $str_ServerURL . '/rest-api/entities/' . $str_Entity . '.php';

$obj_Curl = new Curl();
$obj_Curl->setCookie('GawainSessionID', $str_SessionID);
$obj_Curl->setCookie('GawainUser', $str_User);

if ($str_RequestMethod == 'GET') {
	$obj_Curl->get($str_RedirectUrl, $arr_RedirectFields);
} elseif ($str_RequestMethod == 'POST') {
	$obj_Curl->post($str_RedirectUrl, $arr_RedirectFields);
}


if ($obj_Curl->error) {
	echo 'Error ' . $obj_Curl->error_code . ': ' . $obj_Curl->error_message;
} else {
	switch ($obj_Curl->http_status_code) {
		case 200:
			echo $obj_Curl->response;
			break;
		
		case 401:
			header('Gawain-Response: Unauthorized', 0, 401);
			break;
			
		default:
			header('Gawain-Response: Invalid', 0, $obj_Curl->http_status_code);
			break;
	}
	
	if ($obj_Curl->http_status_code == 401 || $obj_Curl->http_status_code == 404)
	echo $obj_Curl->response;
}


?>

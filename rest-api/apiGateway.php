<?php 

require_once(__DIR__ . '/../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/CurlRequest.php');
require_once(PHP_CLASSES_DIR . 'net/ApiController.php');


// Parses API URL and redirects the request to the proper interface

// Get request data, including cookies
$str_ServerName = $_SERVER['HTTP_HOST'];
$str_RequestURL = $_SERVER['REQUEST_URI'];
$str_RequestMethod = $_SERVER['REQUEST_METHOD'];

$str_SessionID = isset($_COOKIE['GawainSessionID']) ? $_COOKIE['GawainSessionID'] : NULL;
$str_User = isset($_COOKIE['GawainUser']) ? $_COOKIE['GawainUser'] : NULL;



// Parse the URL with regex to get entity, ID and method
$rgx_UrlPattern ='/(.+)\/rest-api\/(\w+)\/?(\d*)\/?(\w*)/';
preg_match($rgx_UrlPattern, $str_RequestURL, $arr_ParsedPath); 

$str_ServerURL = $arr_ParsedPath[1];
$str_Entity = $arr_ParsedPath[2];
$int_ID = $arr_ParsedPath[3];
$str_Method = $arr_ParsedPath[4];


// Get the request body if the request method is POST and rewrite it
if ($str_RequestMethod == 'POST') {
	$str_RequestBody = file_get_contents('php://input');
	
	$arr_RedirectFields = array(
			'ID'		=>	$int_ID != '' ? $int_ID : NULL,
			'method'	=>	$str_Method,
			'data'		=>	$str_RequestBody
	);
	
	
} elseif ($str_RequestMethod == 'GET') {
	$arr_RedirectFields = $int_ID != '' ? array('ID'	=>	$int_ID) : array();

	if ($str_Method != '') {
		$arr_RedirectFields['method'] = $str_Method;
	} else {
		$arr_RedirectFields['method'] = 'read';
	}

	$arr_RedirectFields = array_merge($arr_RedirectFields, $_GET);
}



// Redirect the request
$str_RedirectUrl = $str_ServerName . $str_ServerURL . '/rest-api/controllers/' . $str_Entity . '.php';

$obj_Curl = new CurlRequest();
$obj_Curl->setCookie('GawainSessionID', $str_SessionID);
$obj_Curl->setCookie('GawainUser', $str_User);
$obj_Curl->setCookie('GawainClientIP', $_SERVER['REMOTE_ADDR']);

if ($str_RequestMethod == 'GET') {
	$obj_Curl->get($str_RedirectUrl, $arr_RedirectFields);
} elseif ($str_RequestMethod == 'POST') {
	$obj_Curl->post($str_RedirectUrl, $arr_RedirectFields);
}


// Send the responses back
$arr_ResponseHeader = explode(PHP_EOL, trim($obj_Curl->raw_response_headers));
foreach ($arr_ResponseHeader as $str_ResponseHeader) {

	// Removes the chunking since it is not necessary
	if (!strpos($str_ResponseHeader, 'chunked')) {
		header($str_ResponseHeader);
	}
}

header('Content-length: ' . strlen($obj_Curl->response));

echo $obj_Curl->response;
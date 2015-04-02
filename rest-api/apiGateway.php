<?php 

// Parses API URL and redirects the request to the proper interface

$str_RequestURL = $_SERVER['REQUEST_URI'];
$str_RequestMethod = $_SERVER['REQUEST_METHOD'];


// Parse the URL with regex to get entity, ID and method
$rgx_UrlPattern ='/rest-api\/(\w+)\/?(\d*)\/?(\w*)/';
preg_match($rgx_UrlPattern, $str_RequestURL, $arr_ParsedPath); 

$str_Entity = $arr_ParsedPath[1];
$int_ID = $arr_ParsedPath[2];
$str_Method = $arr_ParsedPath[3];


// Get the request body if the requets method is POST and rewrite it
if ($str_RequestMethod == 'POST') {
	$str_RequestBody = json_decode(file_get_contents('php://input'));
	
	$arr_RedirectBody = array(
			'ID'		=>	$int_ID,
			'method'	=>	$str_Method,
			'data'		=>	$str_RequestBody
	);
	
	$str_RedirectBody = json_encode($arr_RedirectBody);
	$str_QueryString = '';
	
} elseif ($str_RequestMethod == 'GET') {
	$str_RedirectBody = '';
	$str_QueryString = '?ID=' . $int_ID;
}


// Redirect the request
$str_RedirectUrl = '/rest-api/entities/' . $str_Entity . '.php' . $str_QueryString;

$obj_CurlHandler = curl_init($str_RedirectUrl);

if ($str_RequestMethod = 'POST') {
	curl_setopt($obj_CurlHandler, CURLOPT_POST, 1);
	curl_setopt($obj_CurlHandler, CURLOPT_POSTFIELDS, $str_RedirectBody);
}

curl_setopt($obj_CurlHandler, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($obj_CurlHandler, CURLOPT_HEADER, 0);

$bool_Response = curl_exec($obj_CurlHandler);

if ($bool_Response) {
	header('Gawain-Response-Code: 200', true, 200);
} else {
	header('Gawain-Response-Code: 404', true, 404);
}


?>

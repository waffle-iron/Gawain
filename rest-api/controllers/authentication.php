<?php

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/ApiController.php');

$obj_Controller = new ApiController('UserAuthManager', PHP_CLASSES_DIR . 'auths/UserAuthManager.php', 'login', $_COOKIE['GawainClientIP'], FALSE, array($_COOKIE['GawainClientIP']), FALSE, FALSE);


// Define custom methods for authentication
$obj_Controller->registerMethod('POST', 'authenticate', array(
		'writeGrant'	=>	0,
		'arguments'		=>	isset($obj_Controller->requestArgs['data']) ? $obj_Controller->requestArgs['data'] : NULL
));


$obj_Controller->registerMethod('POST', 'isAuthenticated', array(
		'writeGrant'	=>	0,
		'arguments'		=>	array(
				isset($_COOKIE['GawainUser']) ? $_COOKIE['GawainUser'] : NULL,
				isset($_COOKIE['GawainSessionID']) ? $_COOKIE['GawainSessionID'] : NULL
		)
));


/*$obj_Controller->registerMethod('POST', 'hasGrants', array(
		'writeGrant'	=>	0,
		'arguments'		=>	$obj_Controller->requestArgs['data']
));*/


$obj_Controller->registerMethod('POST', 'login', array(
		'writeGrant'	=>	0,
		'arguments'		=>	array(
				isset($_COOKIE['GawainSessionID']) ? $_COOKIE['GawainSessionID'] : NULL,
				isset($obj_Controller->requestArgs['data']['selectedCustomer']) ? $obj_Controller->requestArgs['data']['selectedCustomer'] : NULL
		)
));


$obj_Controller->registerMethod('POST', 'logout', array(
		'writeGrant'	=>	0,
		'arguments'		=>	array(
				isset($_COOKIE['GawainSessionID']) ? $_COOKIE['GawainSessionID'] : NULL
		)
));



// Call the requests method
$mix_Response = $obj_Controller->callMethod();



// Different actions depending on method
if ($mix_Response !== NULL) {
	switch ($obj_Controller->requestArgs['method']) {
		case 'authenticate':
			setcookie('GawainSessionID', $mix_Response['sessionID']);
			header('Gawain-Response: Authenticated', 0, 200);
			echo $mix_Response['enabledCustomers'];
			break;
			
			
		case 'login':
			header('Gawain-Response: Logged In', 0, 200);
			break;
			
			
		case 'logout':
			header('Gawain-Response: Logged Out', 0, 200);
			break;
			
			
		case 'isAuthenticated':
			if ($mix_Response) {
				header('Gawain-Response: Authorized', 0, 200);
			} else {
				header('Gawain-Response: Unauthorized', 0, 401);
			}
			break;
			
			
		case 'hasGrants':
			break;
	}
}


?>
<?php

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

$obj_AthManager = new UserAuthManager();

if (isset($_COOKIE['GawainSessionID'])) {
	$str_SessionID = $_COOKIE['GawainSessionID'];

	// Checks if the session ID is valid
	if (!$obj_AthManager->isAuthenticated($str_SessionID)) {
		header('Location: ' . LOGOUT_LANDING_PAGE, TRUE);
		exit;
	}
} else {
	header('Location: ' . LOGOUT_LANDING_PAGE, TRUE);
	exit;
}
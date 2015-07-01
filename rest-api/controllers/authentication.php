<?php

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');



$obj_UserAuthManager = new UserAuthManager($app->request->getIp());


$app->group('/authentication', function () use ($app, $obj_UserAuthManager) {


	$app->post('/authenticate', function () use ($app, $obj_UserAuthManager) {

		$str_Body = $app->request->getBody();
		$arr_Body = json_decode($str_Body, true);

		$str_Username = $arr_Body['username'];
		$str_Password = $arr_Body['password'];


		try {
			$arr_Results = $obj_UserAuthManager->authenticate($str_Username, $str_Password);
			$app->setCookie('GawainSessionID', $arr_Results['sessionID'], 0);
			$app->response->body($arr_Results['enabledCustomers']);
		} catch (Exception $exc) {
			$app->response->setStatus(401);
		}

	});



	$app->post('/login', function () use ($app, $obj_UserAuthManager) {

		$str_Body = $app->request->getBody();

		$str_SessionID = $app->getCookie('GawainSessionID');
		$int_SelectedCustomer = $app->request->post('selectedCustomer');

		if ($obj_UserAuthManager->login($str_SessionID, $int_SelectedCustomer)) {
			$app->redirect($app->urlFor('activities'));
		} else {
			$app->response->setStatus(403);
		}

	});



	$app->get('/isAuthenticated', function () use ($app, $obj_UserAuthManager) {

		if ($obj_UserAuthManager->isAuthenticated($app->getCookie('GawainSessionID'))) {
			$app->response->setStatus(200);
		} else {
			$app->response->setStatus(401);
		}

	});


	$app->get('/logout', function () use ($app, $obj_UserAuthManager) {

		$str_SessionID = $app->getCookie('GawainSessionID');

		if ($obj_UserAuthManager->logout($str_SessionID)) {
			$app->deleteCookie('GawainSessionID');
			$app->redirect($app->urlFor('loginPage'));
		} else {
			$app->response->setStatus(403);
		}

	})->name('logout');


});
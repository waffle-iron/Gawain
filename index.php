<?php

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'middlewares/CheckAuthenticationMiddleware.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');


\Slim\Slim::registerAutoloader();


// Creation of Slim app
$app = new \Slim\Slim();


// Settings
$app->config(array(
	             'debug' =>  true
             ));


// Middleware declaration
$app->add(new \CheckAuthenticationMiddleware());



// Default routing rule if the simple path is provided
$app->get('/', function () use ($app) {
	$app->redirect('/login');
});



// Login route
$app->get('/login', function () use ($app) {

})->name('loginPage');






// Run the application
$app->run();
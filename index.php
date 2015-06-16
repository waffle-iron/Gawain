<?php

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'middlewares/CheckAuthenticationMiddleware.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');

require_once(PHP_VENDOR_DIR . 'Twig/Autoloader.php');
require_once(PHP_VENDOR_DIR . 'Slim-Views/Twig.php');
require_once(PHP_VENDOR_DIR . 'Slim-Views/TwigExtension.php');

require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');


\Slim\Slim::registerAutoloader();
Twig_Autoloader::register();


// Creation of Slim app with Twig rendering engine (Uber coool)
$app = new \Slim\Slim(array(
	                      'view' => new \Slim\Views\Twig()
                      ));


// Settings
$app->config(array(
	             'debug' =>  true
             ));


// Adding Twig extensions
$app->view()->parserExtensions = array(new \Slim\Views\TwigExtension());


// Configure Twig instance
$app->view()->setTemplatesDirectory(TEMPLATES_DIR . 'html/Default');
$twig = $app->view()->getEnvironment();
$loader = $twig->getLoader();


// Jierarchy declaration
$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');


// Middleware declaration
$app->add(new \CheckAuthenticationMiddleware());



// Default routing rule if the simple path is provided
$app->get('/', function () use ($app) {
	$app->redirect('/login');
});



// Login route
$app->get('/login', function () use ($app, $loader, $obj_Jierarchy) {

	// Calculates and prepares the page library dependencies
	$arr_PageDependencies = $obj_Jierarchy->load(array(
		                                                      'jQuery',
		                                                      'bootstrap',
		                                                      'bootstrap-cerulean-theme',
		                                                      'gawain-style-settings',
		                                                      'CryptoJS',
		                                                      'gawain-button-bindings'
	                                                      ));

	$app->view()->set('page_dependencies', $arr_PageDependencies);

	$loader->addPath(MODULES_DIR . 'login/templates/html/Default');
	$app->render('webpage.twig');

})->name('loginPage');






// Run the application
$app->run();
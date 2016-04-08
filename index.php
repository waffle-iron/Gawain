<?php

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'middlewares/CheckAuthenticationMiddleware.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');

require_once(PHP_VENDOR_DIR . 'Twig/Autoloader.php');
require_once(PHP_VENDOR_DIR . 'Slim-Views/Twig.php');

\Slim\Slim::registerAutoloader();
Twig_Autoloader::register();

require_once(PHP_VENDOR_DIR . 'Slim-Views/TwigExtension.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');


// Creation of Slim app with Twig rendering engine (Uber coool)
$app = new \Slim\Slim(array(
                          'view' => new \Slim\Views\Twig()
                      ));


// Settings
$app->config(array(
                 'debug' => true
             ));


// Adding Twig extensions
$app->view()->parserExtensions = array(new \Slim\Views\TwigExtension());


// Configure Twig instance
$app->view()->setTemplatesDirectory(TEMPLATES_DIR . 'html/Default');
$twig = $app->view()->getEnvironment();
$loader = $twig->getLoader();


// Jierarchy declaration
$obj_Jierarchy = new Jierarchy(CONFIG_DIR . 'dependencies.json');


// Middleware declaration
$app->add(new \CheckAuthenticationMiddleware());


// Default routing rule if the simple path is provided
$app->get('/', function () use ($app) {
    $app->redirect($app->urlFor('loginPage'));
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

    $loader->addPath(TEMPLATES_DIR . 'html/Default');
    $app->render('login.twig');

})->name('loginPage');


// Modules routes group
$arr_ModulesList = array_diff(scandir(MODULES_DIR), array('..', '.'));

foreach ($arr_ModulesList as $str_Module) {
    if (file_exists(MODULES_DIR . $str_Module . '/controller.php')) {
        require(MODULES_DIR . $str_Module . '/controller.php');
    }
}


// REST API routes group
$app->group('/rest-api', function () use ($app, $loader, $obj_Jierarchy) {

    $arr_ControllersList = array_diff(scandir(RESTAPI_DIR . 'controllers'), array('..', '.'));

    foreach ($arr_ControllersList as $str_Controller) {
        require(RESTAPI_DIR . 'controllers/' . $str_Controller);
    }

});


// Run the application
$app->run();
<?php
/**
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'middlewares/CheckAuthenticationMiddleware.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');

require_once(PHP_VENDOR_DIR . 'Twig/Autoloader.php');
require_once(PHP_VENDOR_DIR . 'Slim-Views/Twig.php');

\Slim\Slim::registerAutoloader();
Twig_Autoloader::register();

require_once(PHP_VENDOR_DIR . 'Slim-Views/TwigExtension.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');
require_once(PHP_CLASSES_DIR . 'misc/I18N.php');


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


// I18N declaration
$obj_I18N = new I18N('en_EN');


// Middleware declaration
$app->add(new \CheckAuthenticationMiddleware());


// Default routing rule if the simple path is provided
$app->get('/', function () use ($app) {
    $app->redirect($app->urlFor('loginPage'));
});


// Login route
$app->get('/login', function () use ($app, $loader, $obj_Jierarchy, $obj_I18N) {

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
    $app->view()->set('I18N', $obj_I18N);

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

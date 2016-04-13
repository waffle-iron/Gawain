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

namespace Gawain;

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

// PSR-0 autoloader registration for the while project
spl_autoload_register('class_loader');

Slim::registerAutoloader();
Twig_Autoloader::register();

use Gawain\Classes\Middlewares\CheckAuthenticationMiddleware;
use Gawain\Classes\Net\Jierarchy;
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig_Autoloader;

// Creation of Slim app with Twig rendering engine
$app = new Slim(array(
                    'view' => new Twig()
                ));

// Settings
$app->config(array(
                 'debug' => true
             ));

// Adding Twig extensions
$app->view()->parserExtensions = array(new TwigExtension());

// Configure Twig instance
$app->view()->setTemplatesDirectory(TEMPLATES_DIR . 'html/Default');
$twig = $app->view()->getEnvironment();
$loader = $twig->getLoader();

// Jierarchy declaration
$obj_Jierarchy = new Jierarchy(CONFIG_DIR . 'dependencies.json');

// Middleware declaration
$app->add(new CheckAuthenticationMiddleware());

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

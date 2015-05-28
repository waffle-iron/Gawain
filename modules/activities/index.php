<?php

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');
require_once(PHP_CLASSES_DIR . 'rendering/HtmlRenderer.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(PHP_VENDOR_DIR . 'Slim/Slim.php');
require_once(TEMPLATES_DIR . 'html/Default/page_navbar.php');

\Slim\Slim::registerAutoloader();


// Check permission for the current page
$obj_AuthManager = new UserAuthManager($_SERVER['REMOTE_ADDR']);
$obj_AuthManager->checkPermissions('activities', TRUE);


// Creates Slim app
$app = new Slim\Slim();



// Global activity view
$app->get('/', function() {

	// Loads the navbar data
	$arr_Data = common\page_navbar\data_source();

	// Calculates and prepares the page library dependencies
	$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
	$arr_Data['page_dependencies'] = $obj_Jierarchy->load(array(
		                                                      'jQuery',
		                                                      'bootstrap',
		                                                      'bootstrap-cerulean-theme',
		                                                      'gawain-style-settings',
		                                                      'gawain-button-bindings',
		                                                      'font-awesome',
		                                                      'jquery-treegrid',
		                                                      'gawain-treegrid-onload',
		                                                      "dhtmlx-Gantt"
	                                                      ));

	// Gets activity data, skipping referentials for parent activity
	$obj_Activity = new Activity($_COOKIE['GawainSessionID']);
	$arr_Data['activities'] = $obj_Activity->read(NULL, array('activityParentID'));
	$arr_Data['module_label'] = $obj_Activity->entityLabel;
	$arr_Data['activity_types'] = $obj_Activity->getActivityTypes();


	// Renders the webpage
	$obj_Renderer = new HtmlRenderer();
	$obj_Renderer->addTemplatePath(__DIR__ . '/templates/html/Default');
	$obj_Renderer->importData($arr_Data);
	$obj_Renderer->setStyle('Default');
	$obj_Renderer->setTemplate('webpage_all.twig');
	echo $obj_Renderer->render();

});


// Details of activity, read mode
$app->get('/:id', function($id) {   // FIXME: move the app declaration in main index.php

	// Loads the navbar data
	$arr_Data = common\page_navbar\data_source();

	// Calculates and prepares the page library dependencies
	$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
	$arr_Data['page_dependencies'] = $obj_Jierarchy->load(array(
		                                                      'jQuery',
		                                                      'bootstrap',
		                                                      'bootstrap-cerulean-theme',
		                                                      'gawain-style-settings',
		                                                      'gawain-button-bindings',
		                                                      'font-awesome',
		                                                      'jquery-treegrid',
		                                                      'gawain-treegrid-onload',
		                                                      "dhtmlx-Gantt"
	                                                      ));

	// Gets activity data, skipping referentials for parent activity
	$obj_Activity = new Activity($_COOKIE['GawainSessionID']);
	$arr_Data['activities'] = $obj_Activity->read($id);
	$arr_Data['module_label'] = $obj_Activity->entityLabel;


	// Renders the webpage
	$obj_Renderer = new HtmlRenderer();
	$obj_Renderer->addTemplatePath(__DIR__ . '/templates/html/Default');
	$obj_Renderer->importData($arr_Data);
	$obj_Renderer->setStyle('Default');
	$obj_Renderer->setTemplate('webpage_details.twig');
	echo $obj_Renderer->render();

});


$app->run();
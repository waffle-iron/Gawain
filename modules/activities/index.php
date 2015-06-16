<?php

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');
require_once(PHP_CLASSES_DIR . 'rendering/HtmlRenderer.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(TEMPLATES_DIR . 'html/Default/page_navbar.php');


// Check permission for the current page
$obj_AuthManager = new UserAuthManager($_SERVER['REMOTE_ADDR']);
$obj_AuthManager->checkPermissions('activities', TRUE);



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

//var_dump($arr_Data['activities']);

echo $obj_Renderer->render();
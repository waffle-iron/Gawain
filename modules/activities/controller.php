<?php

require_once(COMMON_DIR . 'templates/html/Default/page_navbar.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');


$app->get('/activities', function () use($app, $loader, $obj_Jierarchy) {

	// Get session ID
	$str_SessionID = $app->getCookie('GawainSessionID');


	// Page dependencies
	$arr_PageDependencies = $obj_Jierarchy->load(array(
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


	// Navbar data declaration
	$arr_NavbarData = common\page_navbar\data_source($str_SessionID, 'activities');


	//Activity object declaration and usage
	$obj_Activity = new Activity($str_SessionID);
	$arr_Activities = $obj_Activity->read(NULL, array('activityParentID'));
	$str_ModuleLabel = $obj_Activity->entityLabel;
	$arr_ActivityTypes = $obj_Activity->getActivityTypes();


	// Prepare the view
	$app->view()->set('page_dependencies', $arr_PageDependencies);
	$app->view()->set('navbar_data', $arr_NavbarData);
	$app->view()->set('activities', $arr_Activities);
	$app->view()->set('module_label', $str_ModuleLabel);
	$app->view()->set('activity_types', $arr_ActivityTypes);


	// Renders the page
	$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
	$app->render('webpage_all.twig');

})->name('activities');
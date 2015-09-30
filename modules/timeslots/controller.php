<?php

require_once(COMMON_DIR . 'templates/html/Default/page_navbar.php');
require_once(PHP_CLASSES_DIR . 'entities/Timeslot.php');


// Get session ID
$str_SessionID = $app->getCookie('GawainSessionID');


$app->group('/timeslots', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

	$app->get('/', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

		// Page dependencies
		$arr_PageDependencies = $obj_Jierarchy->load(array(
			                                             'jQuery',
			                                             'bootstrap',
			                                             'bootstrap-cerulean-theme',
			                                             'gawain-style-settings',
			                                             'gawain-button-bindings',
			                                             'font-awesome',
			                                             'TinyColor'
		                                             ));


		// Navbar data declaration
		$arr_NavbarData = common\page_navbar\data_source($str_SessionID, 'timeslots');


		// Timeslot object declaration and usage
		$obj_Timeslot = new Timeslot($str_SessionID);
		$arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries(); // Defaults to current month
		$arr_TimeslotFields = $obj_Timeslot->getFieldsData();
		$str_ModuleLabel = $obj_Timeslot->getLabel();
		$str_ItemLabel = $obj_Timeslot->getItemLabel();


		// Pepare view variables
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('timeslots_fields', $arr_TimeslotFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('current_user_timeslots', $arr_CurrentUserTimeslots);
		$app->view()->set('module_item_label', $str_ItemLabel);


		// Renders the page
		$loader->addPath(MODULES_DIR . 'timeslots/templates/html/Default');
		$app->render('webpage_user_timeslots.twig');

	})->name('timeslots');




	$app->get('/new', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

	})->name('timeslot_new');

});
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
		$str_TimeslotFilter = $app->request->get('filter');
		$str_TimeslotFrom = $app->request->get('from');
		$str_TimeslotTo = $app->request->get('to');

		if (!is_null($str_TimeslotFilter)) {
			$arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries($str_TimeslotFilter);
		} elseif (!is_null($str_TimeslotFrom) || !is_null($str_TimeslotTo)) {
			$arr_Limits = array(
				'from'  =>  $str_TimeslotFrom,
				'to'    =>  $str_TimeslotTo
			);
			$arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries($arr_Limits);
		} else {
			$arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries();
		}


		// Group timeslots by date
		$arr_GroupedTimeslots = array();
		foreach ($arr_CurrentUserTimeslots as $int_TimeslotID => $arr_Timeslot) {
			$arr_GroupedTimeslots[$arr_Timeslot['timeslotReferenceDate']][$int_TimeslotID] = $arr_Timeslot;
		}

		$arr_TimeslotFields = $obj_Timeslot->getFieldsData();
		$str_ModuleLabel = $obj_Timeslot->getLabel();
		$str_ItemLabel = $obj_Timeslot->getItemLabel();


		// Pepare view variables
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('timeslots_fields', $arr_TimeslotFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('current_user_timeslots', $arr_GroupedTimeslots);
		$app->view()->set('module_item_label', $str_ItemLabel);


		// Renders the page
		$loader->addPath(MODULES_DIR . 'timeslots/templates/html/Default');
		$app->render('webpage_user_timeslots.twig');

	})->name('timeslots');




	$app->get('/new', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

	})->name('timeslot_new');



	$app->get('/:timeslotID/edit', function ($timeslotID) use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

	})->name('timeslot_edit');



	$app->post('/delete', function () use ($app, $str_SessionID) {

	})->name('timeslot_delete');


});
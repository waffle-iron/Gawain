<?php

require_once(COMMON_DIR . 'templates/html/Default/page_navbar.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');


// Get session ID
$str_SessionID = $app->getCookie('GawainSessionID');


$app->group('/activities', function () use($app, $loader, $obj_Jierarchy, $str_SessionID) {

	$app->get('/', function () use($app, $loader, $obj_Jierarchy, $str_SessionID) {

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
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_ModuleLabel = $obj_Activity->entityLabel;
		$arr_ActivityTypes = $obj_Activity->getActivityTypes();


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activities_data', $arr_Activities);
		$app->view()->set('activities_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('activity_types', $arr_ActivityTypes);


		// Renders the page
		$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
		$app->render('webpage_all.twig');

	})->name('activities');



	$app->get('/:activityID', function ($activityID) use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

		// Page dependencies
		$arr_PageDependencies = $obj_Jierarchy->load(array(
			                                             'jQuery',
			                                             'bootstrap',
			                                             'bootstrap-cerulean-theme',
			                                             'gawain-style-settings',
			                                             'gawain-button-bindings',
			                                             'font-awesome',
			                                             "dhtmlx-Gantt"
		                                             ));


		// Navbar data declaration
		$arr_NavbarData = common\page_navbar\data_source($str_SessionID, NULL);


		//Activity object declaration and usage
		$obj_Activity = new Activity($str_SessionID);
		$arr_ActivityData = $obj_Activity->read($activityID);
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_ModuleLabel = $obj_Activity->entityLabel;
		$str_ItemLabel = $obj_Activity->entityItemLabel;


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activity_ID', $activityID);
		$app->view()->set('activity_data', $arr_ActivityData[$activityID]);
		$app->view()->set('activity_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('module_item_label', $str_ItemLabel);




		// Renders the page
		$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
		$app->render('webpage_single_show.twig');

	})->conditions(array('activityID' => '\d+'))->name('activity_show');




	$app->get('/new', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

		// Page dependencies
		$arr_PageDependencies = $obj_Jierarchy->load(array(
			                                             'jQuery',
			                                             'bootstrap',
			                                             'bootstrap-cerulean-theme',
			                                             'gawain-style-settings',
			                                             'gawain-button-bindings',
			                                             'font-awesome'
		                                             ));

		// Navbar data declaration
		$arr_NavbarData = common\page_navbar\data_source($str_SessionID, NULL);

		// If the request comes from a "Clone", then copy data from selected activity
		// If the request is a simple "New", no data is set as default value
		$int_ClonedActivityID = $app->request->get('cloneFrom');

		$obj_Activity = new Activity($str_SessionID);
		$arr_ActivityData = is_null($int_ClonedActivityID) ? NULL : $obj_Activity->read($int_ClonedActivityID);
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_DomainDependencyColumn = $obj_Activity->getDomainDependencyColumn();
		$str_MainID = $obj_Activity->mainID;
		$str_ModuleLabel = $obj_Activity->entityLabel;
		$str_ItemLabel = $obj_Activity->entityItemLabel;


		// If the type is specified in the request, the field is automatically populated, else it is empty
		$int_ActivityTypeID = $app->request->get('activityType');


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activity_data', is_null($int_ClonedActivityID) ? NULL : $arr_ActivityData[$int_ClonedActivityID]);
		$app->view()->set('activity_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('module_item_label', $str_ItemLabel);
		$app->view()->set('activity_type_ID', $int_ActivityTypeID);
		$app->view()->set('activity_domain_dependency_column', $str_DomainDependencyColumn);
		$app->view()->set('activity_main_id', $str_MainID);



		// Action switch to define the view once and use it for edit and creation
		$app->view()->set('page_action', 'new');


		// Renders the page
		$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
		$app->render('webpage_single_new_edit.twig');

	})->name('activity_new');



	$app->post('/new/save', function () use ($app) {

		$app->redirect($app->urlFor('activities'));

	})->name('activity_new_save');

});
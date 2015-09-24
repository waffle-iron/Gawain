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
			                     'jsgantt-improved',
			                     'bootstrap-cerulean-theme',
			                     'gawain-style-settings',
			                     'gawain-button-bindings',
			                     'font-awesome',
			                     'jquery-treegrid',
			                     'gawain-treegrid-onload',
			                     'TinyColor'
		                     ));


		// Navbar data declaration
		$arr_NavbarData = common\page_navbar\data_source($str_SessionID, 'activities');


		// Activity object declaration and usage
		$obj_Activity = new Activity($str_SessionID);
		$arr_Activities = $obj_Activity->read(NULL, array('activityParentID'));
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_ModuleLabel = $obj_Activity->getLabel();
		$arr_ActivityTypes = $obj_Activity->getActivityTypes();


		// Prepare Gantt chart data outside the template
		$str_GanttXML = $obj_Activity->getGanttData();


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activities_data', $arr_Activities);
		$app->view()->set('activities_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('activity_types', $arr_ActivityTypes);
		$app->view()->set('gantt_data', str_replace('\'', '\\\'', $str_GanttXML));


		// Renders the page
		$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
		$app->render('webpage_all.twig');

	})->name('activities');



	$app->get('/:activityID', function ($activityID) use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

		// Page dependencies
		$arr_PageDependencies = $obj_Jierarchy->load(array(
			                                             'jQuery',
			                                             'bootstrap',
			                                             'jsgantt-improved',
			                                             'bootstrap-cerulean-theme',
			                                             'gawain-style-settings',
			                                             'gawain-button-bindings',
			                                             'font-awesome'
		                                             ));


		// Navbar data declaration
		$arr_NavbarData = common\page_navbar\data_source($str_SessionID, NULL);


		//Activity object declaration and usage
		$obj_Activity = new Activity($str_SessionID);
		$arr_ActivityData = $obj_Activity->read($activityID);
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_ModuleLabel = $obj_Activity->getLabel();
		$str_ItemLabel = $obj_Activity->getItemLabel();

		// Prepare Gantt chart data outside the template
		$str_GanttXML = $obj_Activity->getGanttData($activityID);


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activity_ID', $activityID);
		$app->view()->set('activity_data', $arr_ActivityData[$activityID]);
		$app->view()->set('activity_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('module_item_label', $str_ItemLabel);
		$app->view()->set('gantt_data', str_replace('\'', '\\\'', $str_GanttXML));



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
		$str_MainID = $obj_Activity->getPrimaryKey();
		$str_ModuleLabel = $obj_Activity->getLabel();
		$str_ItemLabel = $obj_Activity->getItemLabel();


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



	$app->post('/new/save', function () use ($app, $str_SessionID) {

		// Get all the POST variables
		$arr_PostVariables = $app->request->post();

		// Get all activity fields info
		$obj_Activity = new Activity($str_SessionID);
		$arr_ActivityFields = $obj_Activity->getFieldsData();

		// Iterate over array to nullify empty strings
		foreach (array_keys($arr_PostVariables) as $str_PostKey) {
			if ($arr_PostVariables[$str_PostKey] == '') {
				if ($arr_ActivityFields[$str_PostKey]['type'] == 'BOOL') {
					$arr_PostVariables[$str_PostKey] = 0;
				} else {
					$arr_PostVariables[$str_PostKey] = NULL;
				}
			}
		}

		// Save activity data
		$obj_Activity->insert($arr_PostVariables);
		$app->redirect($app->urlFor('activities'));

	})->name('activity_new_save');




	$app->get('/:activityID/edit', function ($activityID) use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

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


		$obj_Activity = new Activity($str_SessionID);
		$arr_ActivityData = $obj_Activity->read($activityID);
		$arr_ActivityFields = $obj_Activity->getFieldsData();
		$str_DomainDependencyColumn = $obj_Activity->getDomainDependencyColumn();
		$str_MainID = $obj_Activity->getPrimaryKey();
		$str_ModuleLabel = $obj_Activity->getLabel();
		$str_ItemLabel = $obj_Activity->getItemLabel();


		// Prepare the view
		$app->view()->set('page_dependencies', $arr_PageDependencies);
		$app->view()->set('navbar_data', $arr_NavbarData);
		$app->view()->set('activity_data', $arr_ActivityData[$activityID]);
		$app->view()->set('activity_fields', $arr_ActivityFields);
		$app->view()->set('module_label', $str_ModuleLabel);
		$app->view()->set('module_item_label', $str_ItemLabel);
		$app->view()->set('activity_domain_dependency_column', $str_DomainDependencyColumn);
		$app->view()->set('activity_main_id', $str_MainID);
		$app->view()->set('activity_ID', $activityID);



		// Action switch to define the view once and use it for edit and creation
		$app->view()->set('page_action', 'edit');


		// Renders the page
		$loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
		$app->render('webpage_single_new_edit.twig');

	})->conditions(array('activityID' => '\d+'))->name('activity_edit');



	$app->post('/:activityID/save', function ($activityID) use ($app, $str_SessionID) {

		// Get all the POST variables
		$arr_PostVariables = $app->request->post();
		$obj_Activity = new Activity($str_SessionID);

		// Iterate over array to unset empty strings
		foreach (array_keys($arr_PostVariables) as $str_PostKey) {
			if ($arr_PostVariables[$str_PostKey] == '') {
				unset($arr_PostVariables[$str_PostKey]);
			}
		}

		// Save activity data
		$obj_Activity->update($activityID, $arr_PostVariables);
		$app->redirect($app->urlFor('activity_show', array('activityID' => $activityID)));

	})->conditions(array('activityID' => '\d+'))->name('activity_edit_save');



	$app->post('/delete', function () use ($app, $str_SessionID) {

		$int_ActivityID = $app->request->post('activityID');
		$obj_Activity = new Activity($str_SessionID);
		$obj_Activity->delete($int_ActivityID);
		$app->redirect($app->urlFor('activities'));

	})->conditions(array('activityID' => '\d+'))->name('activity_delete');

});
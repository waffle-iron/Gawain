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
                                                         'highcharts',
                                                         'highcharts-drilldown',
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
                'from' => $str_TimeslotFrom,
                'to' => $str_TimeslotTo
            );
            $arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries($arr_Limits);
        } else {
            $arr_CurrentUserTimeslots = $obj_Timeslot->getCurrentUserEntries();
        }


        // Group timeslots by date
        $arr_DateGroupedTimeslots = $obj_Timeslot::groupTimeslotsByDate($arr_CurrentUserTimeslots);


        // Group timeslots by activity and task
        $arr_ActivityGroupedTimeslots = $obj_Timeslot::groupTimeslotsByActivity($arr_CurrentUserTimeslots);


        $arr_TimeslotFields = $obj_Timeslot->getFieldsData();
        $str_ModuleLabel = $obj_Timeslot->getLabel();
        $str_ItemLabel = $obj_Timeslot->getItemLabel();


        // Pepare view variables
        $app->view()->set('page_dependencies', $arr_PageDependencies);
        $app->view()->set('navbar_data', $arr_NavbarData);
        $app->view()->set('timeslots_fields', $arr_TimeslotFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('date_grouped_timeslots', $arr_DateGroupedTimeslots);
        $app->view()->set('activity_grouped_timeslots', $arr_ActivityGroupedTimeslots);
        $app->view()->set('module_item_label', $str_ItemLabel);


        // Renders the page
        $loader->addPath(MODULES_DIR . 'timeslots/templates/html/Default');
        $app->render('webpage_user_timeslots.twig');

    })->name('timeslots');


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
        $arr_NavbarData = common\page_navbar\data_source($str_SessionID, null);

        // If the request comes from a "Clone", then copy data from selected timeslot
        // If the request is a simple "New", no data is set as default value
        $int_ClonedTimeslotID = $app->request->get('cloneFrom');

        $obj_Timeslot = new Timeslot($str_SessionID);
        $arr_TimeslotData = is_null($int_ClonedTimeslotID) ? null : $obj_Timeslot->read($int_ClonedTimeslotID);
        $arr_TimeslotFields = $obj_Timeslot->getFieldsData();
        $str_DomainDependencyColumn = $obj_Timeslot->getDomainDependencyColumn();
        $str_MainID = $obj_Timeslot->getPrimaryKey();
        $str_ModuleLabel = $obj_Timeslot->getLabel();
        $str_ItemLabel = $obj_Timeslot->getItemLabel();


        // Prepare the view
        $app->view()->set('page_dependencies', $arr_PageDependencies);
        $app->view()->set('navbar_data', $arr_NavbarData);
        $app->view()->set('data', is_null($int_ClonedTimeslotID) ? null : $arr_TimeslotData[$int_ClonedTimeslotID]);
        $app->view()->set('fields', $arr_TimeslotFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('domain_dependency_column', $str_DomainDependencyColumn);
        $app->view()->set('main_ID', $str_MainID);


        // Action switch to define the view once and use it for edit and creation
        $app->view()->set('page_action', 'new');
        $app->view()->set('entity_new_save_link_name', 'timeslot_new_save');
        $app->view()->set('entity_edit_save_link_name', 'timeslot_edit_save');


        // Renders the page
        $loader->addPath(COMMON_DIR . 'templates/html/Default');
        $app->render('single_entity_new_edit.twig');

    })->name('timeslot_new');


    $app->post('/new/save', function () use ($app, $str_SessionID) {

        // Get all the POST variables
        $arr_PostVariables = $app->request->post();

        // Get all activity fields info
        $obj_Timeslot = new Timeslot($str_SessionID);
        $arr_TimeslotFields = $obj_Timeslot->getFieldsData();

        // Iterate over array to nullify empty strings
        foreach (array_keys($arr_PostVariables) as $str_PostKey) {
            if ($arr_PostVariables[$str_PostKey] == '') {
                if ($arr_TimeslotFields[$str_PostKey]['type'] == 'BOOL') {
                    $arr_PostVariables[$str_PostKey] = 0;
                } else {
                    $arr_PostVariables[$str_PostKey] = null;
                }
            }
        }

        // Save activity data
        $obj_Timeslot->insert($arr_PostVariables);
        $app->redirect($app->urlFor('timeslots'));

    })->name('timeslot_new_save');


    $app->get('/:timeslotID/edit', function ($timeslotID) use ($app, $loader, $obj_Jierarchy, $str_SessionID) {

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
        $arr_NavbarData = common\page_navbar\data_source($str_SessionID, null);


        $obj_Timeslot = new Timeslot($str_SessionID);
        $arr_TimeslotData = $obj_Timeslot->read($timeslotID);
        $arr_TimeslotFields = $obj_Timeslot->getFieldsData();
        $str_DomainDependencyColumn = $obj_Timeslot->getDomainDependencyColumn();
        $str_MainID = $obj_Timeslot->getPrimaryKey();
        $str_ModuleLabel = $obj_Timeslot->getLabel();
        $str_ItemLabel = $obj_Timeslot->getItemLabel();


        // Prepare the view
        $app->view()->set('page_dependencies', $arr_PageDependencies);
        $app->view()->set('navbar_data', $arr_NavbarData);
        $app->view()->set('data', $arr_TimeslotData[$timeslotID]);
        $app->view()->set('fields', $arr_TimeslotFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('domain_dependency_column', $str_DomainDependencyColumn);
        $app->view()->set('main_ID', $str_MainID);
        $app->view()->set('entity_ID', $timeslotID);


        // Action switch to define the view once and use it for edit and creation
        $app->view()->set('page_action', 'edit');
        $app->view()->set('entity_new_save_link_name', 'timeslot_new_save');
        $app->view()->set('entity_edit_save_link_name', 'timeslot_edit_save');


        // Renders the page
        $loader->addPath(COMMON_DIR . 'templates/html/Default');
        $app->render('single_entity_new_edit.twig');

    })->conditions(array('timeslotID' => '\d+'))->name('timeslot_edit');


    $app->post('/:ID/save', function ($ID) use ($app, $str_SessionID) {

        // Get all the POST variables
        $arr_PostVariables = $app->request->post();
        $obj_Timeslot = new Timeslot($str_SessionID);

        // Iterate over array to unset empty strings
        foreach (array_keys($arr_PostVariables) as $str_PostKey) {
            if ($arr_PostVariables[$str_PostKey] == '') {
                unset($arr_PostVariables[$str_PostKey]);
            }
        }

        // Save activity data
        $obj_Timeslot->update($ID, $arr_PostVariables);
        $app->redirect($app->urlFor('timeslots'));

    })->conditions(array('ID' => '\d+'))->name('timeslot_edit_save');


    $app->post('/delete', function () use ($app, $str_SessionID) {

        $int_TimeslotID = $app->request->post('timeslotID');
        if (!is_null($int_TimeslotID)) {
            $obj_Timeslot = new Timeslot($str_SessionID);
            $obj_Timeslot->delete($int_TimeslotID);
        }
        $app->redirect($app->urlFor('timeslots'));

    })->name('timeslot_delete');


});
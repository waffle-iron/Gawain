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

require_once(COMMON_DIR . 'templates/html/Default/page_navbar.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');


// Get session ID
$str_SessionID = $app->getCookie('GawainSessionID');


$app->group('/activities', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID, $obj_I18N) {

    $app->get('/', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID, $obj_I18N) {

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
        $arr_Activities = $obj_Activity->read(null, array('activityParentID'));
        $arr_ActivityFields = $obj_Activity->getFieldsData();
        $str_ModuleLabel = $obj_Activity->getLabel();
        $str_ItemLabel = $obj_Activity->getItemLabel();
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
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('gantt_data', str_replace('\'', '\\\'', $str_GanttXML));
        $app->view()->set('I18N', $obj_I18N);


        // Renders the page
        $loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
        $app->render('webpage_all.twig');

    })->name('activities');


    $app->get('/:activityID', function ($activityID) use ($app, $loader, $obj_Jierarchy, $str_SessionID, $obj_I18N) {

        // Page dependencies
        $arr_PageDependencies = $obj_Jierarchy->load(array(
                                                         'jQuery',
                                                         'bootstrap',
                                                         'jsgantt-improved',
                                                         'bootstrap-cerulean-theme',
                                                         'highcharts',
                                                         'highcharts-drilldown',
                                                         'gawain-style-settings',
                                                         'gawain-button-bindings',
                                                         'font-awesome'
                                                     ));


        // Navbar data declaration
        $arr_NavbarData = common\page_navbar\data_source($str_SessionID, null);


        //Activity object declaration and usage
        $obj_Activity = new Activity($str_SessionID);
        $arr_ActivityData = $obj_Activity->read($activityID);
        $arr_ActivityFields = $obj_Activity->getFieldsData();
        $str_ModuleLabel = $obj_Activity->getLabel();
        $str_ItemLabel = $obj_Activity->getItemLabel();

        // Prepare Gantt chart data outside the template
        $str_GanttXML = $obj_Activity->getGanttData($activityID);


        // Resources and timeslots
        $obj_Timeslot = new Timeslot($str_SessionID);
        $arr_ActivityTimeslots = $obj_Timeslot::groupTimeslotsByUser($obj_Timeslot->getUsersEntries(null, 'all',
                                                                                                        $activityID));


        // Prepare the view
        // Dashboard data
        $app->view()->set('page_dependencies', $arr_PageDependencies);
        $app->view()->set('navbar_data', $arr_NavbarData);
        $app->view()->set('activity_ID', $activityID);
        $app->view()->set('activity_data', $arr_ActivityData[$activityID]);
        $app->view()->set('activity_fields', $arr_ActivityFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('I18N', $obj_I18N);

        // Gantt data
        $app->view()->set('gantt_data', str_replace('\'', '\\\'', $str_GanttXML));

        // Users and Timeslot data
        $app->view()->set('activity_timeslots', $arr_ActivityTimeslots);

        // Renders the page
        $loader->addPath(MODULES_DIR . 'activities/templates/html/Default');
        $app->render('webpage_single_show.twig');

    })->conditions(array('activityID' => '\d+'))->name('activity_show');


    $app->get('/new', function () use ($app, $loader, $obj_Jierarchy, $str_SessionID, $obj_I18N) {

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

        // If the request comes from a "Clone", then copy data from selected activity
        // If the request is a simple "New", no data is set as default value
        $int_ClonedActivityID = $app->request->get('cloneFrom');

        $obj_Activity = new Activity($str_SessionID);
        $arr_ActivityData = is_null($int_ClonedActivityID) ? null : $obj_Activity->read($int_ClonedActivityID);
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
        $app->view()->set('data', is_null($int_ClonedActivityID) ? null : $arr_ActivityData[$int_ClonedActivityID]);
        $app->view()->set('fields', $arr_ActivityFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('type_ID', $int_ActivityTypeID);
        $app->view()->set('domain_dependency_column', $str_DomainDependencyColumn);
        $app->view()->set('main_ID', $str_MainID);
        $app->view()->set('I18N', $obj_I18N);


        // Action switch to define the view once and use it for edit and creation
        $app->view()->set('page_action', 'new');
        $app->view()->set('entity_new_save_link_name', 'activity_new_save');
        $app->view()->set('entity_edit_save_link_name', 'activity_edit_save');


        // Renders the page
        $loader->addPath(COMMON_DIR . 'templates/html/Default');
        $app->render('single_entity_new_edit.twig');

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
                    $arr_PostVariables[$str_PostKey] = null;
                }
            }
        }

        // Save activity data
        $obj_Activity->insert($arr_PostVariables);
        $app->redirect($app->urlFor('activities'));

    })->name('activity_new_save');


    $app->get('/:activityID/edit', function ($activityID) use (
        $app, $loader, $obj_Jierarchy, $str_SessionID, $obj_I18N) {

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
        $app->view()->set('data', $arr_ActivityData[$activityID]);
        $app->view()->set('fields', $arr_ActivityFields);
        $app->view()->set('module_label', $str_ModuleLabel);
        $app->view()->set('module_item_label', $str_ItemLabel);
        $app->view()->set('domain_dependency_column', $str_DomainDependencyColumn);
        $app->view()->set('main_ID', $str_MainID);
        $app->view()->set('entity_ID', $activityID);
        $app->view()->set('I18N', $obj_I18N);


        // Action switch to define the view once and use it for edit and creation
        $app->view()->set('page_action', 'edit');
        $app->view()->set('entity_new_save_link_name', 'activity_new_save');
        $app->view()->set('entity_edit_save_link_name', 'activity_edit_save');


        // Renders the page
        $loader->addPath(COMMON_DIR . 'templates/html/Default');
        $app->render('single_entity_new_edit.twig');

    })->conditions(array('activityID' => '\d+'))->name('activity_edit');


    $app->post('/:ID/save', function ($ID) use ($app, $str_SessionID) {

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
        $obj_Activity->update($ID, $arr_PostVariables);
        $app->redirect($app->urlFor('activity_show', array('activityID' => $ID)));

    })->conditions(array('ID' => '\d+'))->name('activity_edit_save');


    $app->post('/delete', function () use ($app, $str_SessionID) {

        $int_ActivityID = $app->request->post('activityID');
        if (!is_null($int_ActivityID)) {
            $obj_Activity = new Activity($str_SessionID);
            $obj_Activity->delete($int_ActivityID);
        }
        $app->redirect($app->urlFor('activities'));

    })->name('activity_delete');

});

<?php

namespace common\page_navbar;

require_once(__DIR__ . '/../../../php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

function data_source($str_SessionID, $str_CurrentModuleID = null) {

	$obj_Options = new \Options();
	$obj_DbHandler = db_autodefine($obj_Options);

	$arr_Output = array();

	// Retrieves the enabled modules
	$str_Query = '
			select
				modules.moduleCode,
				labels.moduleLabel
			from modules
			inner join modules_label labels
				on modules.moduleCode = labels.moduleCode
			inner join modules_auths auths
				on auths.moduleCode = modules.moduleCode
				and auths.customerID = labels.customerID
			inner join user_groups groups
				on groups.groupCode = auths.groupCode
			inner join user_enabled_customers enabled
				on enabled.groupCode = groups.groupCode
				and enabled.authorizedCustomerID = auths.customerID
			inner join sessions
				on sessions.userNick = enabled.userNick
				and sessions.customerID = enabled.authorizedCustomerID
			where sessions.sessionID = ?
				order by labels.moduleDisplayOrder, labels.moduleLabel';

	$obj_Resultset = $obj_DbHandler->executePrepared($str_Query, array(
		array($str_SessionID  =>  's')
	));


	$arr_Output['navbar_modules'] = $obj_Resultset;

	$arr_Output['current_module'] = $str_CurrentModuleID;



	// Retrieve Username
	$str_Query = '
			select
				users.userName
			from sessions
			inner join users
				on sessions.userNick = users.userNick
			where sessions.sessionID = ?';

	$obj_Resultset = $obj_DbHandler->executePrepared($str_Query, array(
		array($_COOKIE['GawainSessionID']  =>  's')
	));

	$arr_Output['username'] = $obj_Resultset[0]['userName'];


	return $arr_Output;

}
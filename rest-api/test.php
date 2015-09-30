<?php

require_once(__DIR__ . '/../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'entities/Timeslot.php');
require_once(PHP_CLASSES_DIR . 'misc/Logger.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

$obj_Manager = new Timeslot('AAA');
$obj_Logger = new Logger('activity');
$obj_AuthManager = new UserAuthManager();

//$obj_Output = $obj_AuthManager->authenticate('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

//var_dump($obj_AuthManager->isAuthenticated('AAA'));

//$obj_AuthManager->logout($obj_Output['sessionID']);

var_dump($obj_Manager->getCurrentUserEntries());

//echo $obj_Logger->log('INFO', 'Testo di prova', 'admin');



/*echo $obj_Manager->read(array(
		'activityID' => array(
				'operator' => '=',
				'arguments' => array(
						1
					)
			)
	), 'display__block_text', 'rendered');*/

/*echo $obj_Manager->insert(array(
		'activityCustomerID' => 1,
		'activityName' => 'Prova inserimento remoto',
		'activityTypeID' => 1
));*/


/*echo $obj_Manager->update(array(
		'activityID' => array(
				'operator' => '=',
				'arguments' => array(
						1
					)
			)
	),
		array(
				'activityCustomerID' => 1,
				'activityName' => 'Prova inserimento remoto',
				'activityTypeID' => 1
		)
	);*/

/*echo $obj_Manager->delete(array(
		'activityID' => array(
				'operator' => '=',
				'arguments' => array(
						11
					)
			)
	))*/



?>
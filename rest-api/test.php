<?php

require_once(__DIR__ . '/../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(PHP_CLASSES_DIR . 'misc/Logger.php');

$obj_Manager = new Activity('AAA');
$obj_Logger = new Logger('activity');

//echo $obj_Manager->read(NULL, 'display__block_text', 'rendered');

echo $obj_Logger->log('INFO', 'Testo di prova', 'admin');



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
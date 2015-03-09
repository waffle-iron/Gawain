<?php

require_once('../classes/entities/activity_manager.php');

$obj_Manager = new activity_manager('AAA');

echo $obj_Manager->read(NULL, 'block_text');

/*echo $obj_Manager->read(array(
		'activityID' => array(
				'operator' => '=',
				'arguments' => array(
						1
					)
			)
	), 'block_text', 'rendered');*/

/*echo $obj_Manager->insert(array(
		'activityCustomerID' => 1,
		'activityName' => 'Prova inserimento remoto',
		'activityTypeID' => 1
));*/



?>
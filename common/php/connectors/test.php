<?php

require_once('../classes/entities/activity_manager.php');

$obj_Manager = new activity_manager('AAA');

echo $obj_Manager->read(array(
		'activityID' => array(
				'operator' => '=',
				'arguments' => array(
						1
					)
			)
	), 'block_text', 'rendered');



?>
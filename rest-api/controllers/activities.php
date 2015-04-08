<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/ApiController.php');

$obj_Controller = new ApiController('Activity', PHP_CLASSES_DIR . 'entities/Activity.php', 'activities');

$str_Response = $obj_Controller->callMethod();

echo $str_Response;

?>
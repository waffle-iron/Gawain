<!doctype html>

<html>
<head>

<?php 

require_once(__DIR__ . '/common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');

$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
$obj_Jierarchy->load(array('jQuery', 'bootstrap'));


?>

</head>




</html>
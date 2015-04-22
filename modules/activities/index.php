<?php

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');
require_once(PHP_CLASSES_DIR . 'auths/PageRenderer.php');
$obj_PageRenderer = new PageRenderer('activities');

?>

<!doctype html>

<html data-gawain-module="activities">

<head>
	<title>Gawain - Activities</title>

	<meta charset="UTF-8">
	<meta name="author" content="Stefano Romanò (Rumix87)">

	<?php

	$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
	$obj_Jierarchy->load(array(
		                     'jQuery',
		                     'bootstrap',
		                     'bootstrap-cerulean-theme',
		                     'gawain-style-settings',
		                     'gawain-button-bindings',
		                     'font-awesome'
	                     ));

	?>

</head>

<body>

<?php
$obj_PageRenderer->renderNavbar();
?>

</body>

</html>
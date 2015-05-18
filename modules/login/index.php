<!doctype html>

<html data-gawain-module="login">

<head>
	<title>Gawain - Login Page</title>
	
	<meta charset="UTF-8">
	<meta name="author" content="Stefano Romanò (Rumix87)">
	
<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');

$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
$arr_Libraries = $obj_Jierarchy->load(array(
		'jQuery',
		'bootstrap',
		'bootstrap-cerulean-theme',
		'gawain-style-settings',
        'CryptoJS',
        'gawain-button-bindings'
	));


foreach ($arr_Libraries['css'] as $str_CssPaths) {
	echo '<link href="' . $str_CssPaths . '" rel="stylesheet" type="text/css">';
}

foreach ($arr_Libraries['js'] as $str_JsPaths) {
	echo '<script src="' . $str_JsPaths . '"></script>';
}

?>
	
</head>



<body>

	<div class="container" role="main">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h2>Gawain</h2>
				<div class="row">
					<div class="col-md-12">
						<form class="form-horizontal" id="gawain-authentication-form">
							<div class="form-group">
								<label for="gawain-login-username" class="col-md-2 control-label">Username</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="gawain-login-username" placeholder="Username" name="username">
								</div>
							</div>

							<div class="form-group">
								<label for="gawain-login-password" class="col-md-2 control-label">Password</label>
								<div class="col-md-10">
									<input type="password" class="form-control" id="gawain-login-password" placeholder="Password" name="password" data-gawain-hash="hash">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="button" class="btn btn-primary gawain-controller-button" id="gawain-authentication-button"
                                            data-gawain-controller="authentication"
                                            data-gawain-controller-method="authenticate"
                                            data-gawain-request-method="POST"
                                            data-gawain-request-target="gawain-authentication-form"
											data-gawain-response-target="gawain-domain-viewport">Authenticate</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12  gawain-viewport" id="gawain-domain-viewport">

					</div>
				</div>

			</div>
		</div>


	</div>

</body>

</html>
<!doctype html>

<html>

<head data-gawain-module="login">
	<title>Gawain - Login Page</title>
	
	<meta charset="UTF-8">
	<meta name="author" content="Stefano Romanò (Rumix87)">
	
<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');

$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
$obj_Jierarchy->load(array(
		'jQuery',
		'bootstrap',
		'bootstrap-cerulean-theme',
		'gawain-style-settings',
        'CryptoJS',
        'gawain-button-bindings'
	));

?>
	
</head>



<body>

	<div class="container container-fluid" role="main">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					
					<div class="panel-body">
					<h2>Gawain</h2>
						<form class="form-horizontal" id="gawain-login-form">
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
									<button type="button" class="btn btn-primary gawain-controller-button" id="gawain-login-authenticate"
                                            data-gawain-controller="authentication"
                                            data-gawain-controller-method="authenticate"
                                            data-gawain-request-method="POST"
                                            data-gawain-request-target="gawain-login-form"
											data-gawain-response-target="gawain-domain-viewport">Authenticate</button>
								</div>
							</div>
						</form>

						
						<div class="gawain-viewport" id="gawain-domain-viewport">
							
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>

</body>

</html>
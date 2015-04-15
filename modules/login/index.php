<!doctype html>

<html>

<head data-gawain-module="login">
	<title>Gawain - Login Page</title>
	
	<meta charset="UTF-8">
	<meta name="author" content="Stefano Roman� (Rumix87)">
	
<?php 

require_once(__DIR__ . '/../../common/php/constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'net/Jierarchy.php');

$obj_Jierarchy = new Jierarchy(JS_DIR . 'dependencies/dependencies.json');
$obj_Jierarchy->load(array(
		'jQuery',
		'bootstrap',
		'bootstrap-cerulean-theme',
		'gawain-style-settings'
	));

?>
	
	<script src="js/login.js"></script>
	
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
									<input type="text" class="form-control" id="gawain-login-username" placeholder="Username" data-gawain-id="username">
								</div>
							</div>
							
							<div class="form-group">
								<label for="gawain-login-password" class="col-md-2 control-label">Password</label>
								<div class="col-md-10">
									<input type="password" class="form-control" id="gawain-login-password" placeholder="Password" data-gawain-id="password">
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="button" class="btn btn-primary" id="gawain-login-authenticate" data-gawain-method="authenticate" data-gawain-target="gawain-login-form">Authenticate</button>
								</div>
							</div>
						</form>
						
						<hr class="gawain-anchor" id="gawain-domain-anchor" data-gawain-related-to="gawain-domain-viewport">
						
						<div class="gawain-viewport" id="gawain-domain-viewport">
							
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>

</body>

</html>
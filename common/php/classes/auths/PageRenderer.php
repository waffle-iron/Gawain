<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');


class PageRenderer {

	// Internal DB Handler
	private $dbHandler;

	// Internal options handler
	private $options;

	// Internal sessionID
	private $sessionID;

	// Internal module name
	private $module;

	// Internal AuthManager
	private $authManager;


	/** Constructor
	 *
	 * @param string $str_Module
	 * @param string $str_SessionID
	 * @throws Exception
	 */
	public function __construct($str_Module, $str_SessionID = NULL) {

		$this->module = $str_Module;
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
		$this->authManager = new UserAuthManager();

		// Check if session ID is null, if so try to get it from current cookies
		if ($str_SessionID !== NULL) {
			$this->sessionID = $str_SessionID;
		} else {
			if (isset($_COOKIE['GawainSessionID'])) {
				$this->sessionID = $_COOKIE['GawainSessionID'];

				// Checks if the session ID is valid
				if ($this->authManager->isAuthenticated($this->sessionID)) {

					// Checks if the user has the correct grants
					if (!$this->authManager->hasGrants($this->sessionID, $this->module)) {
						header('Location: /gawain/index.php', TRUE, 401);
					}

				} else {
					header('Location: /gawain/index.php', TRUE, 401);
				}
			} else {
				header('Location: /gawain/index.php', TRUE, 401);
			}
		}


	}


	/** Renders the upper navbar links
	 *
	 */
	public function renderNavbar() {

		// Retrieves the enabled modules
		$str_Query = '
			select
				modules.moduleCode,
				labels.moduleLabel,
				groups.groupName
			from modules
			inner join modules_label labels
				on modules.moduleCode = labels.moduleCode
			inner join modules_auths auths
				on auths.moduleCode = modules.moduleCode
				and auths.customerID = labels.customerID
			inner join user_groups groups
				on groups.groupCode = auths.groupCode
			inner join user_enabled_customers enabled
				on enabled.groupCode = groups.groupCode
				and enabled.authorizedCustomerID = auths.customerID
			inner join sessions
				on sessions.userNick = enabled.userNick
				and sessions.customerID = enabled.authorizedCustomerID
			where sessions.sessionID = ?
				order by labels.moduleDisplayOrder, labels.moduleLabel';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query, array(
			array($this->sessionID  =>  's')
		));


		// Start composing the output
		$str_Output = '
			<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Gawain</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">';


		$arr_Links = array();

		// TODO: make path dynamic
		foreach ($obj_Resultset as $arr_Result) {
			$arr_Links[] = '<li><a href="' . '/gawain/modules/' . $arr_Result['moduleCode'] . '/">' .
			               $arr_Result['moduleLabel'] . '</a></li>';
		}

		$str_Output .= implode(PHP_EOL, $arr_Links);


		// Retrieve Username
		$str_Query = '
			select
				users.userName
			from sessions
			inner join users
				on sessions.userNick = users.userNick
			where sessions.sessionID = ?';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query, array(
			array($this->sessionID  =>  's')
		));

		$str_Output .= '</ul>';


		// Creates the user action button
		$str_Output .= '
			<ul class="nav navbar-nav navbar-right">
				<li>
					<div class="btn-group navbar-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-user"></i> ' . $obj_Resultset[0]['userName'] . ' <i class="fa fa-caret-down"></i></button>

						<ul class="dropdown-menu" role="menu">
							<li><a href="#">To be filled...</a></li>
							<li class="divider"></li>
							<li><a href="#">Logout</a></li>
						</ul>
					</div>
				</li>
			</ul>
			</div>
			</div>
			</nav>';


		echo $str_Output;
	}


}
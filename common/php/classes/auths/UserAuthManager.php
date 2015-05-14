<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

class UserAuthManager {
	
	// IP address
	private $hostName;
	
	
	// DB handler
	private $dbHandler;
	
	
	// Options
	private $options;
	
	
	/** Constructor
	 * 
	 * @param string $str_Host
	 */
	public function __construct($str_Host = NULL) {
		$this->hostName = $str_Host;
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
	}
	
	
	
	/** Authenticates with the given password hash
	 * 
	 * @param string $str_UserNick
	 * @param string $str_PasswordHash
	 * @throws Exception
	 * @return array
	 */
	public function authenticate($str_UserNick, $str_PasswordHash) {
		$str_CheckQuery = '
			select
				userPassword,
				userIsActive
			from users
			where userNick = ?';
		
		$obj_Resultset = $this->dbHandler->executePrepared($str_CheckQuery,
			array(
					array($str_UserNick => 's')
			));
		
		if (count($obj_Resultset) == 0) {
			throw new Exception('User does not exist');
			
		} elseif ($str_UserNick === NULL) {
			throw new Exception('User not defined');
			
		} elseif (count($obj_Resultset) > 1) {
			throw new Exception('Multiple user returned');
			
		} elseif ($obj_Resultset[0]['userIsActive'] = 0) {
			throw new Exception('User is not active');
			
		} elseif (strtoupper($obj_Resultset[0]['userPassword']) != strtoupper($str_PasswordHash)) {
			throw new Exception('Wrong username or password');
			
		} else {
			$str_SessionID = $this->generateSessionID();
			
			$this->writeSession($str_SessionID, $str_UserNick, NULL, $this->hostName);
			
			return array(
					'sessionID'			=>	$str_SessionID,
					'enabledCustomers'	=>	$this->getEnabledCustomers($str_UserNick, 'html')
			);
		}
	}
	
	
	
	/** Checks if the given user is authenticated
	 *
	 * @param string $str_SessionID
	 * @return boolean
	 */
	public function isAuthenticated($str_SessionID) {
		$str_Query = '
				select
					count(*) as counter
				from sessions
				where sessionID = ?';
		
		$obj_Resultset = $this->dbHandler->executePrepared($str_Query, array(
				array($str_SessionID	=>	's')
		));
		
		if ($obj_Resultset[0]['counter'] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	
	/** Checks if the user related to the current Session ID has grants to perform an action
	 * 
	 * @param string $str_SessionID
	 * @param string $str_ModuleCode
	 * @param integer $int_RequiredPermission
	 * @throws Exception
	 * @return boolean
	 */
	public function hasGrants($str_SessionID, $str_ModuleCode, $int_RequiredPermission = 0) {
		$str_Query = '
				select
					auth.writePermission
				from modules
				inner join modules_auths auth
					on modules.moduleCode = auth.moduleCode
				inner join user_groups groups
					on groups.groupCode = auth.groupCode
				inner join user_enabled_customers enabled
					on enabled.groupCode = auth.groupCode
					and enabled.authorizedCustomerID = auth.customerID
				inner join users
					on enabled.userNick = users.userNick
				inner join sessions
					on sessions.userNick = users.userNick
					and sessions.customerID = enabled.authorizedCustomerID
				where modules.moduleCode = ?
					and sessions.sessionID = ?';
		
		
		$arr_Resultset = $this->dbHandler->executePrepared($str_Query, array(
				array($str_ModuleCode	=>	's'),
				array($str_SessionID	=>	's')
		));
		
		if ($arr_Resultset !== NULL && count($arr_Resultset) == 1) {
			if ($arr_Resultset[0]['writePermission'] >= $int_RequiredPermission) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			throw new Exception('Invalid request');
		}
	}


	/** Checks if the current user has permission for the current module
	 *
	 * @param bool $bool_SendHeader
	 * @return bool
	 */
	public function checkPermissions($bool_SendHeader = FALSE) {
		// If the cookies are not set, the request is automatically aborted
		if (isset($_COOKIE['GawainSessionID'])) {
			$str_SessionID = $_COOKIE['GawainSessionID'];

			// If the user authentication is not valid, the request is automatically aborted
			if (!$this->isAuthenticated($str_SessionID)) {
				if ($bool_SendHeader) {
					header('Gawain-Response: Unauthorized', 0, 401);
				}
				return FALSE;
			} else {
				return TRUE;
			}

		} else {
			if ($bool_SendHeader) {
				header('Gawain-Response: Unauthorized', 0, 401);
			}
			return FALSE;
		}
	}

	
	
	
	/** Log the current user in and selects the current customer
	 *
	 * @param string $str_SessionID
	 * @param integer $int_SelectedCustomer
	 * @return boolean
	 */
	public function login($str_SessionID, $int_SelectedCustomer) {
		$str_CustomerCheckQuery = '
			select
				sessions.sessionID,
				sessions.userNick
			from sessions
			inner join user_enabled_customers enabled
				on sessions.userNick = enabled.userNick
			where sessions.sessionID = ?
				and enabled.authorizedCustomerID = ?';
	
		$obj_Resultset = $this->dbHandler->executePrepared($str_CustomerCheckQuery,
				array(
						array($str_SessionID => 's'),
						array($int_SelectedCustomer => 'i')
				));
	
		if (count($obj_Resultset) == 1) {
			$this->setSessionCustomer($str_SessionID, $int_SelectedCustomer);
			return TRUE;
			
		} else {
			return FALSE;
		}
	}
	
	
	
	/** Logs out from the current session
	 * 
	 * @param string $str_SessionID
	 * @return boolean
	 */
	public function logout($str_SessionID) {
		$this->removeSession($str_SessionID);
		
		return TRUE;
	}
	
	
	
	/** Generates a valid, hopefully unbreakable, session ID
	 *
	 * @return string
	 */
	private function generateSessionID() {
		$str_SessionID = sha1(sha1(microtime() . date('z') . uniqid(NULL, TRUE)));
		return $str_SessionID;
	}
	
	
	
	
	/** Get the list of enabled customers for the given user in the selected format
	 * The format can be:
	 * 	- 'raw' to output the result as PHP array
	 * 	- 'json' to output the result as JSON string
	 * 	- 'html' to output the result as HTML select options
	 * 
	 * @param string $str_UserNick
	 * @param string $str_Format
	 * 
	 * @return array
	 */
	private function getEnabledCustomers($str_UserNick, $str_Format = 'html') {
		$str_EnabledCustomersQuery = '
			select
				enabled.authorizedCustomerID as ID,
				customers.customerName as Name
			from user_enabled_customers enabled
			inner join customers
				on enabled.authorizedCustomerID = customers.customerID
			where enabled.userNick = ?';
		
		$obj_Resultset = $this->dbHandler->executePrepared($str_EnabledCustomersQuery,
				array(
						array($str_UserNick => 's')
				));

		$mix_Return = NULL;
		
		
		switch ($str_Format) {
			case 'json':
				$mix_Return = json_encode($obj_Resultset);
				break;
				
			case 'raw':
				$mix_Return =  $obj_Resultset;
				break;
				
			case 'html':
				$str_Template = '<option value="%ID%">%NAME%</option>';
				$arr_Output = array();
				
				foreach($obj_Resultset as $arr_Result) {
					$str_Output = str_replace('%ID%', $arr_Result['ID'], $str_Template);
					$str_Output = str_replace('%NAME%', $arr_Result['Name'], $str_Output);
					$arr_Output[] = $str_Output;
				}
				
				$mix_Return =  '<hr>' .
				               '<form class="form-horizontal" id="gawain-domain-selection-form">' .
				               '<div class="form-group">' .
				               '<label for="gawain-domain-selector" class="col-md-2 control-label">Domain</label>' .
				               '<div class="col-md-10">' .
				               '<select class="form-control" id="gawain-domain-selector" name="selectedCustomer">' .
				               implode(PHP_EOL, $arr_Output) .
				               '</select>' .
				               '</div>' .
				               '</div>' .
				               '<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="button" class="btn btn-primary gawain-controller-button" id="gawain-domain-selection-button"
	                                            data-gawain-controller="authentication"
	                                            data-gawain-controller-method="login"
	                                            data-gawain-request-method="POST"
	                                            data-gawain-request-target="gawain-domain-selection-form"
												data-gawain-response-redirect="' . LOGIN_LANDING_PAGE . '">Login</button>
									</div>
								</div>' .
				               '</form>';
				break;
		}

		return $mix_Return;
	}
	
	
	
	
	/** Writes the current session data into DB
	 * 
	 * @param string $str_SessionID
	 * @param string $str_UserNick
	 * @param integer $int_CustomerID
	 * @param string $str_Host
	 * @return boolean
	 */
	private function writeSession($str_SessionID, $str_UserNick, $int_CustomerID = NULL, $str_Host = 'localhost') {
		$str_InsertQuery = '
			insert into sessions (
				sessionID,
				userNick,
				customerID,
				sessionStartDate,
				sessionHostName
			) values (
				?,
				?,
				' . (is_null($int_CustomerID) ? 'NULL' : '?') . ',
				sysdate(),
				?			
			)';
		
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_InsertQuery,
				(is_null($int_CustomerID) ?
					array(
							array($str_SessionID => 's'),
							array($str_UserNick => 's'),
							array($str_Host => 's')
					) :
					array(
							array($str_SessionID => 's'),
							array($str_UserNick => 's'),
							array($int_CustomerID =>'i'),
							array($str_Host => 's')
					)
				)
				);
		$this->dbHandler->commit();
		
		return TRUE;
	}
	
	
	
	
	/** Sets the customerID for the given session
	 * 
	 * @param string $str_SessionID
	 * @param integer $int_CustomerID
	 * @return boolean
	 */
	private function setSessionCustomer($str_SessionID, $int_CustomerID) {
		$str_SetCustomerQuery = '
			update sessions
			set customerID = ?
				where sessionID = ?';
		
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_SetCustomerQuery,
				array(
						array($int_CustomerID	=>	'i'),
						array($str_SessionID	=>	's')
				));
		$this->dbHandler->commit();
		
		return TRUE;
	}
	
	
	
	
	/** Removes the given session from DB
	 * 
	 * @param string $str_SessionID
	 * @return boolean
	 */
	private function removeSession($str_SessionID) {
		$str_RemoveQuery = '
			delete from sessions
				where sessionID = ?';
		
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_RemoveQuery,
				array(
						array($str_SessionID	=>	's')
				));
		$this->dbHandler->commit();
		
		return TRUE;
	}
	
}
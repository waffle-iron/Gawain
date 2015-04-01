<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

class UserAuthManager {
	
	// Current user nick
	private $userNick;
	
	
	// IP address
	private $hostName;
	
	
	// DB handler
	private $dbHandler;
	
	
	// Options
	private $options;
	
	
	/** Constructor
	 * 
	 * @param string $str_UserNick
	 * @param string $str_Host
	 */
	public function __construct($str_UserNick, $str_Host = NULL) {
		$this->userNick = $str_UserNick;
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);
	}
	
	
	
	/** Authenticates with the given password hash
	 * 
	 * @param string $str_PasswordHash
	 * @throws Exception
	 * @return boolean
	 */
	public function authenticate($str_PasswordHash) {
		$str_CheckQuery = '
			select
				userPassword,
				userIsActive
			from users
			where userNick = ?';
		
		$obj_Resultset = $this->dbHandler->executePrepared($str_CheckQuery,
			array(
					array($this->userNick => 's')
			));
		
		if (count($obj_Resultset) == 0) {
			throw new Exception('User does not exist');
			return FALSE;
			
		} elseif ($this->userNick === NULL) {
			throw new Exception('User not defined');
			return FALSE;
			
		} elseif (count($obj_Resultset) > 1) {
			throw new Exception('Multiple user returned');
			return FALSE;
			
		} elseif ($obj_Resultset[0]['userIsActive'] = 0) {
			throw new Exception('User is not active');
			return FALSE;
			
		} elseif (strtoupper($obj_Resultset[0]['userPassword']) != strtoupper($str_PasswordHash)) {
			throw new Exception('Wrong username or password');
			return FALSE;
			
		} else {
			$str_SessionID = $this->generateSessionID();
			
			$this->writeSession($str_SessionID, $this->userNick, NULL, $this->hostName);
			
			return array(
					'sessionID'			=>	$str_SessionID,
					'enabledCustomers'	=>	$this->getEnabledCustomers('raw')
			);
		}
	}
	
	
	
	/** Log the current user in and selects the current customer
	 *
	 * @param string $str_SessionID
	 * @param integer $int_SelectedCustomer
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
	 * @param string $str_Format
	 * 
	 * @return array
	 */
	private function getEnabledCustomers($str_Format = 'html') {
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
						array($this->userNick => 's')
				));
		
		
		switch ($str_Format) {
			case 'json':
				return json_encode($obj_Resultset);
				break;
				
			case 'raw':
				return $obj_Resultset;
				break;
				
			case 'html':
				$str_Template = '<option value="%ID%">%NAME%</option>';
				$arr_Output = array();
				
				foreach($obj_Resultset as $arr_Result) {
					$str_Output = str_replace('%ID%', $arr_Result['ID'], $str_Template);
					$str_Output = str_replace('%NAME%', $arr_Result['Name'], $str_Output);
					$arr_Output[] = $str_Output;
				}
				
				return implode(PHP_EOL, $arr_Output);
				
		}
		
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

?>
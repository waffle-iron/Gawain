<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

class UserAuthManager {
	
	// Current user nick
	private $userNick;
	
	
	// DB handler
	private $dbHandler;
	
	
	// Options
	private $options;
	
	
	/** Constructor
	 * 
	 * @param string $str_UserNick
	 */
	public function __construct($str_UserNick = NULL) {
		$this->userNick = $str_UserNick;
		$this->dbHandler = db_autodefine($this->options);
	}
	
	
	
	/** Checks if a user is authorized with the given password hash
	 * 
	 * @param string $str_PasswordHash
	 * @throws Exception
	 * @return boolean
	 */
	public function isAuthorized($str_PasswordHash) {
		$str_CheckQuery = '
			select
				userPassword,
				userIsActive
			from users
			where userNick = ?';
		
		$obj_Resultset = $this->dbHandler->executePrepared($str_CheckQuery,
			array(
					array($this->userNick) => 's'
			));
		
		if (count($obj_Resultset) == 0) {
			throw new Exception('User does not exist');
			return FALSE;
		} elseif (count($obj_Resultset) > 1) {
			throw new Exception('Multiple user returned');
			return FALSE;
		} elseif ($obj_Resultset['userIsActive'] = 0) {
			throw new Exception('User is not active');
			return FALSE;
		} elseif ($obj_Resultset['userPassword'] != $str_PasswordHash) {
			throw new Exception('Wrong username or password');
			return FALSE;
		} else {
			return $this->generateSessionID();
		}
	}
	
	
	
	/** Generates a valid, hopefully unbreakable, session ID
	 *
	 * @return string
	 */
	private function generateSessionID() {
		$str_SessionID = sha1(sha1(microtime() . date('z') . uniqid(NULL, TRUE)));
		return str_Session;
	}
	
}

?>
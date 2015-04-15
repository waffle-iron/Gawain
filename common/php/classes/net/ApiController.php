<?php 

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

class ApiController {
	
	// Linked entity class
	private $entityClass;
	
	// Module code
	private $module;
	
	// Path to entity class definition
	private $classPath;
	
	// Internal class instance
	private $classInstance;
	
	// Session ID
	private $sessionID;
	
	// Request method
	public $requestMethod;
	
	// Request arguments
	public $requestArgs = array();
	
	// Array with the available methods for the selected class
	private $methods = array();
	
	// Class reflection for internal use
	private $classReflection;
	
	// Class reflection methods
	private $classReflectionMethods;
	
	// Internal Auth Manager
	private $authManager;
	
	// Request source
	private $hostName;
	
	// Perform the grants check before calling a method?
	private $doGrantsCheck;
	
	
	
	/** Constructor
	 * 
	 * @param string $str_EntityClass
	 * @param string $str_ClassPath
	 * @param string $str_Module
	 * @param string $str_HostName
	 * @param boolean $bool_RegisterDefaultMethods
	 * @param array $arr_ClassConstructArgs
	 * @param boolean $bool_DoGrantsCheck
	 * @param boolean $bool_DoAuthCheck
	 * @throws Exception
	 */
	public function __construct($str_EntityClass, $str_ClassPath, $str_Module, $str_HostName = 'localhost', $bool_RegisterDefaultMethods = TRUE, $arr_ClassConstructArgs = NULL, $bool_DoGrantsCheck = TRUE, $bool_DoAuthCheck = TRUE) {
		
		// Sets the session ID
		if ($bool_DoAuthCheck) {
			if (isset($_COOKIE['GawainSessionID'])) {
				$this->sessionID = $_COOKIE['GawainSessionID'];
			} else {
				throw new Exception('Unauthorized');
			}
		} else {
			$this->sessionID = NULL;
		}
		
		$this->authManager = new UserAuthManager();
		$this->module = $str_Module;
		$this->hostName = $str_HostName;
		$this->doGrantsCheck = $bool_DoGrantsCheck;
		
		
		if ($arr_ClassConstructArgs === NULL) {
			$arr_ClassConstructArgs = array($this->sessionID);
		}
		
		
		if (file_exists($str_ClassPath)) {
			require_once($str_ClassPath);
			
			if (class_exists($str_EntityClass)) {
				$this->entityClass = $str_EntityClass;
				$this->classReflection = new ReflectionClass($this->entityClass);
				
				$this->classInstance = $this->classReflection->newInstanceArgs($arr_ClassConstructArgs);
				$this->getReflectionClassMethods();
				$this->requestMethod = $_SERVER['REQUEST_METHOD'];
				$this->getRequestArgs();
				
				if ($bool_RegisterDefaultMethods) {
					$this->registerDefaultMethods();
				}
			}
		}
	}
	
	
	
	
	/** Registers a custom method for the given class
	 * 
	 * @param string $str_RequestMethod
	 * @param string $str_MethodName
	 * @param array $arr_MethodDescription
	 * @return boolean
	 */
	public function registerMethod($str_RequestMethod, $str_MethodName, $arr_MethodDescription) {
		
		// Checks if the method exists for the current class
		if (array_search($str_MethodName, $this->classReflectionMethods) !== FALSE) {
			$this->methods[$str_RequestMethod][$str_MethodName] = $arr_MethodDescription;
			return TRUE;
			
		} else {
			throw new Exception('Method not found');
			return FALSE;
		}
		
		
		return TRUE;
	}
	
	
	
	
	
	/** Calls the selected method. If no method is provided, the method name is inferred from the request
	 * 
	 * @param string $str_Method
	 * @throws Exception
	 * @return mixed
	 */
	public function callMethod($str_Method = NULL) {

		// If the method is not forced during the call, it is taken from request
		if ($str_Method === NULL) {
			if($this->requestMethod == 'GET') {
				$str_Method = 'read';
			} elseif (array_key_exists($this->requestArgs['method'], $this->methods[$this->requestMethod])) {
				$str_Method = $this->requestArgs['method'];
			} else {
				throw new Exception('Non existing method');				
			}
		}
		
		
		
		// Bypass grants check if selected so
		if ($this->doGrantsCheck) {
			if ($this::checkPermissions()) {
				if ($this->authManager->hasGrants($this->sessionID, $this->module)) {
					$str_Output = call_user_func_array(array($this->classInstance, $str_Method),
							$this->methods[$this->requestMethod][$str_Method]['arguments']);
					return $str_Output;
				} else {
					throw new Exception('Insufficient grants');
				}
			} else {
				throw new Exception('Insufficient grants');
			}
		} else {
			$str_Output = call_user_func_array(array($this->classInstance, $str_Method),
					$this->methods[$this->requestMethod][$str_Method]['arguments']);
			return $str_Output;
		}
	}
	
	
	
	
	
	
	/** Checks if the user credentials are correct
	 * 
	 * @param boolean $bool_SendHeader
	 * @return boolean
	 */
	public static function checkPermissions($bool_SendHeader = FALSE) {
		// If the cookies are not set, the request is automatically aborted
		if (isset($_COOKIE['GawainSessionID']) && isset($_COOKIE['GawainUser'])) {
			$str_SessionID = $_COOKIE['GawainSessionID'];
			$str_User = $_COOKIE['GawainUser'];
		
			// If the user authentication is not valid, the request is automatically aborted
			$obj_UserAuthManager = new UserAuthManager();
		
			if (!$obj_UserAuthManager->isAuthenticated($str_User, $str_SessionID)) {
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
	
	
	
	
	/** Saves the request arguments in an internal variable
	 * 
	 */
	private function getRequestArgs() {
		
		// Start getting existing arguments
		switch ($this->requestMethod) {
			case 'GET':
				$this->requestArgs = $_GET;
				break;
				
			case 'POST':
				$this->requestArgs = $_POST;
				break;
		}
		
		// If some argument is missing, is set to NULL
		$arr_AvailableArguments = array(
				'ID',
				'renderingType',
				'outputFormat',
				'method',
				'data'
		);
		
		foreach ($arr_AvailableArguments as $str_Argument) {
			if (!array_key_exists($str_Argument, $this->requestArgs)) {
				$this->requestArgs[$str_Argument] = NULL;
			}
		}
		
		// Parses 'data' argument from JSON to array
		$this->requestArgs['data'] = json_decode($this->requestArgs['data'], TRUE);
	}
	
	
	
	/** Registers the default class methods (defined in abstract class)
	 * 
	 */
	private function registerDefaultMethods() {
		$arr_DefaultMethods = array();
		
		// GET functions
		$arr_Get = array(
				'read'	=>	array(
						'writeGrant'	=>	0,
						'arguments'		=>	array(
								$this->requestArgs['ID'],
								$this->requestArgs['renderingType'],
								$this->requestArgs['outputFormat']
						)
				)
		);
		$arr_DefaultMethods['GET'] = $arr_Get;
		
		
		// POST functions
		$arr_Post = array(
				'insert'	=>	array(
						'writeGrant'	=>	1,
						'arguments'		=>	array(
								$this->requestArgs['data']
						)
				),
				'update'	=>	array(
						'writeGrant'	=>	1,
						'arguments'		=>	array(
								$this->requestArgs['ID'],
								$this->requestArgs['data']
						)
				),
				'delete'	=>	array(
						'writeGrant'	=>	1,
						'arguments'		=>	array(
								$this->requestArgs['ID']
						)
				)
		);
		$arr_DefaultMethods['POST'] = $arr_Post;
		
		// Add the default methods
		$this->methods = $arr_DefaultMethods;
		
	}
	
	
	
	/** Retrieves all the available methods for the current class
	 * 
	 */
	private function getReflectionClassMethods() {
		$arr_ReflectionMethods = $this->classReflection->getMethods(ReflectionMethod::IS_PUBLIC);
		$this->classReflectionMethods = array();
		
		foreach ($arr_ReflectionMethods as $obj_ReflectionMethod) {
			$this->classReflectionMethods[] = $obj_ReflectionMethod->name;
		}
	}
	

}

?>
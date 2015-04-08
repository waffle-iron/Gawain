<?php 

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

class ApiController {
	
	// Linked entity class
	private $entityClass;
	
	// Path to entity class definition
	private $classPath;
	
	// Internal class instance
	private $classInstance;
	
	// Request method
	private $requestMethod;
	
	// Request arguments
	private $requestArgs = array();
	
	// Array with the available methods for the selected class
	private $methods = array();
	
	
	
	public function __construct($str_EntityClass, $str_ClassPath, $str_Module) {
		if (file_exists($str_ClassPath)) {
			require_once($str_ClassPath);
			
			if (class_exists($str_EntityClass)) {
				$this->classInstance = new $str_EntityClass;
				$this->requestMethod = $_SERVER['REQUEST_METHOD'];
				$this->getRequestArgs();
				$this->registerDefaultMethods();
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
		
		// Checks if the method exixts for the current class
		$rfl_ClassReflection = new ReflectionClass($this->entityClass);
		$arr_ClassMethods = $rfl_ClassReflection->getMethods(ReflectionMethod::IS_PUBLIC);
		
		if (array_search($str_MethodName, $arr_ClassMethods) !== FALSE) {
			$arr_AddedMethod = array(
					$str_RequestMethod	=>	array(
							$str_MethodName		=>	$arr_MethodDescription
					)
			);
			
			$this->methods[] = $arr_AddedMethod;
			return TRUE;
			
		} else {
			throw new Exception('Method not found');
			return FALSE;
		}
		
		
		return TRUE;
	}
	
	
	
	
	
	public function callMethod($str_Method = NULL) {
		// If the method is not forced during the call, it is taken from request
		if ($str_Method === NULL) {
			if (array_key_exists($this->requestArgs['method'], $this->methods[$this->requestMethod])) {
				$str_Method = $this->requestArgs['method'];
			} else {
				throw new Exception('Non existing method');				
			}
		}
		
		if ($this::checkPermissions()) {
			$str_Output = call_user_func_array(array($this->classInstance, $str_Method),
					$this->methods[$this->requestMethod][$str_Method]['arguments']);
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
			$obj_UserAuthManager = new UserAuthManager($str_User);
		
			if (!$obj_UserAuthManager->isAuthenticated($str_SessionID)) {
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
		switch ($this->requestMethod) {
			case 'GET':
				$this->requestArgs = $_GET;
				break;
				
			case 'POST':
				$this->requestArgs = $_POST;
				break;
				
			default:
				$this->requestArgs = NULL;
				break;
		}
	}
	
	
	
	/** Registers the default class methods (defined in abstract class)
	 * 
	 */
	private function registerDefaultMethods() {
		$arr_DefaultMethods = array();
		
		// GET functions
		$arr_Get = array(
				'GET'	=>	array(
						'read'	=>	array(
								'writeGrant'	=>	0,
								'arguments'		=>	array(
										$this->requestArgs['ID'],
										$this->requestArgs['renderingType'],
										$this->requestArgs['outputFormat']
								)
						)
				)
		);
		$arr_DefaultMethods[] = $arr_Get;
		
		
		// POST functions
		$arr_Post = array(
				'POST'	=>	array(
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
				)
		);
		$arr_DefaultMethods[] = $arr_Post;
		
		
		// Add the default methods
		$this->methods[] = $arr_DefaultMethods;
	}
	

}

?>
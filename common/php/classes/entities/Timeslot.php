<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'entities/Entity.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');

/**
 * Class Timeslot
 */
class Timeslot extends Entity {

	/** Internal Auth Manager
	 *
	 * @var UserAuthManager
	 */

	private $authManager;

	/** Child constructor
	 *
	 * @param string $str_SessionID
	 */
	public function __construct($str_SessionID) {

		// Sets entity reference code
		$this->type = 'timeslot';

		// Creates the User Auth Manager
		$this->authManager = new UserAuthManager();

		// Call parent constructor
		parent::__construct($str_SessionID);
	}


	/** Helper method that retrieves all the entries linked to current user
	 *
	 * @param string $mix_Limits
	 * @param null   $int_ActivityID
	 * @param null   $int_TaskID
	 * @return array
	 * @throws Exception
	 */
	public function getCurrentUserEntries($mix_Limits = 'this_month', $int_ActivityID = NULL, $int_TaskID = NULL) {

		// First get current user nick
		$str_CurrentUser = $this->authManager->getCurrentUserNick($this->sessionID);

		$arr_Wheres = array(
			'timeslotUserNick'  =>  array(
				'operator'  =>  '=',
			    'arguments' =>  array($str_CurrentUser)
			)
		);


		$arr_Parameters = array(
			array($this->domainID =>  'i'),
			array($str_CurrentUser  =>  's')
		);

		if (!is_array($mix_Limits)) {

			$date_Today = new DateTime();

			// If the limit parameter is a string, interpret the string and add condition
			switch ($mix_Limits) {
				case 'this_day':
					$str_Today = $date_Today->format('Y-m-d');
					$arr_Wheres['timeslotReferenceDate'] = array(
						'operator'  => '=',
						'arguments' =>  array(
							$str_Today
						)
					);
					break;

				case 'this_week':
					$str_Limit = strftime('%Y-%m-%d', strtotime('this week', time()));
					$arr_Wheres['timeslotReferenceDate'] = array(
						'operator'  => '>=',
						'arguments' =>  array(
							$str_Limit
						)
					);
					break;

				case 'this_month':
					$str_Limit = $date_Today->format('Y-m-01');
					$arr_Wheres['timeslotReferenceDate'] = array(
						'operator'  => '>=',
						'arguments' =>  array(
							$str_Limit
						)
					);
					break;

				default:
					$str_Limit = strftime('%Y-%m-%d', strtotime('this month', time()));
					$arr_Wheres['timeslotReferenceDate'] = array(
						'operator'  => '>=',
						'arguments' =>  array(
							$str_Limit
						)
					);
					break;
			}

		} else {

			// If the limit parameter is an array, get the 'from' and 'to' elements
			if (isset($mix_Limits['from'])) {
				$arr_Wheres['timeslotReferenceDate'] = array(
					'operator'  => '>=',
					'arguments' =>  array(
						$mix_Limits['from']
					)
				);
			}

			if (isset($mix_Limits['to'])) {
				$arr_Wheres['timeslotReferenceDate'] = array(
					'operator'  => '<=',
					'arguments' =>  array(
						$mix_Limits['to']
					)
				);
			}

		}


		// Add condition on activity ID and task ID, if present
		if (!is_null($int_ActivityID)) {
			$arr_Wheres['timeslotActivityID'] = array(
				'operator'  => '=',
				'arguments' =>  array(
					$int_ActivityID
				)
			);
		}

		if (!is_null($int_TaskID)) {
			$arr_Wheres['timeslotTaskID'] = array(
				'operator'  => '=',
				'arguments' =>  array(
					$int_TaskID
				)
			);
		}


		// Execute the query and get entries
		return $this->read($arr_Wheres);

	}

}
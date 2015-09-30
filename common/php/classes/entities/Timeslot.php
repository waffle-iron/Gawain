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


		// Then retrieves data according to limits
		$str_TimeslotQuery = '
			select
				timeslots.timeslotID,
				activities.activityName,
				tasks.taskName,
				users.userName,
				timeslots.timeslotReferenceDate,
				timeslots.timeslotDuration,
				timeslots.timeslotDescription
			from timeslots
			inner join activities
				on activities.activityID = timeslots.timeslotActivityID
			left join tasks
				on tasks.taskID = timeslots.timeslotTaskID
			left join users
				on users.userNick = activities.activityManagerNick
			where timeslots.timeslotDomainID = ?
				and timeslots.timeslotUserNick = ?
		';

		$arr_Parameters = array(
			array($this->domainID =>  'i'),
			array($str_CurrentUser  =>  's')
		);

		if (!is_array($mix_Limits)) {

			$date_Today = new DateTime();

			// If the limit parameter is a string, interpret the string and add condition
			switch ($mix_Limits) {
				case 'this_day':
					$str_TimeslotQuery .= ' and timeslotReferenceDate = ?';
					$str_Today = $date_Today->format('Y-m-d');
					$arr_Parameters[] = array($str_Today => 's');
					break;

				case 'this_week':
					$str_TimeslotQuery .= ' and timeslotReferenceDate >= ?';
					$str_Limit = strtotime('this week', time());
					$arr_Parameters[] = array($str_Limit => 's');
					break;

				case 'this_month':
					$str_TimeslotQuery .= ' and timeslotReferenceDate >= ?';
					$str_Limit = strtotime('this month', time());
					$arr_Parameters[] = array($str_Limit => 's');
					break;

				default:
					break;
			}

		} else {

			// If the limit parameter is an array, get the 'from' and 'to' elements
			if (isset($mix_Limits['from'])) {
				$str_TimeslotQuery .= ' and timeslotReferenceDate >= ?';
				$arr_Parameters[] = array($mix_Limits['from'] => 's');
			}

			if (isset($mix_Limits['to'])) {
				$str_TimeslotQuery .= ' and timeslotReferenceDate <= ?';
				$arr_Parameters[] = array($mix_Limits['to'] => 's');
			}

		}


		// Add condition on activity ID and task ID, if present
		if (!is_null($int_ActivityID)) {
			$str_TimeslotQuery .= ' and timeslotActivityID = ?';
			$arr_Parameters[] = array($int_ActivityID => 'i');
		}

		if (!is_null($int_TaskID)) {
			$str_TimeslotQuery .= ' and timeslotTaskID = ?';
			$arr_Parameters[] = array($int_TaskID => 'i');
		}


		// Add reverse date sorting for better readability
		$str_TimeslotQuery .= ' order by timeslotReferenceDate desc';


		// Execute the query and get entries
		$obj_Resultset = $this->dbHandler->executePrepared($str_TimeslotQuery, $arr_Parameters);

		return $this->reformatResultset($obj_Resultset);

	}

}
<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'entities/Entity.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');

/**
 * Class Activity
 */
class Activity extends Entity {

	// Child constructor
	/**
	 * @param string $str_SessionID
	 */
	public function __construct($str_SessionID) {

		// Sets entity reference code
		$this->type = 'activity';

		// Call parent constructor
		parent::__construct($str_SessionID);
	}


	/** Gets the activity types for the current domain
	 *
	 * @return array
	 */
	public function getActivityTypes() {

		$str_Query = '
			select
				activityTypeID as ID,
				activityTypeName as name,
				activityTypeIsOfficial as isOfficial,
				activityTypeComment as comment
			from activity_type
			where activityTypeCustomerID = ?';


		$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
			array(
				array($this->domainID  =>  'i')
			));

		$arr_Output = array();

		foreach ($obj_Resultset as $arr_Row) {
			$int_ID = $arr_Row['ID'];
			unset($arr_Row['ID']);
			$arr_Output[$int_ID] = $arr_Row;
		}

		return $arr_Output;

	}


	/** Calculates the estimated effort for the given activity.
	 *  If the effort is manually set, it simply returns the value.
	 *  If the effort is set to be automatically calculated, the value is retrieved as the recursive sum of the descendant effort.
	 *
	 * @param int $int_ActivityID The activity ID for which the effort has to be calculated
	 * @return float
	 */
	public function getEstimatedEffort($int_ActivityID) {

		// First get activities and saved data
		$str_Query = '
			select
				activities.activityID,
				activities.activityEstimatedEffortHours as effort,
				activities.activityIsEstimatedEffortHoursAuto as isAuto
			from activities
			where activities.activityCustomerID = ?
				and activities.activityID = ?
		';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
		                                                   array(
			                                                   array($this->domainID => 'i'),
			                                                   array($int_ActivityID => 'i')
		                                                   ));


		$dbl_Effort = NULL;


		if (!(boolean) $obj_Resultset[0]['isAuto']) {

			// If a value is present and the Aut flag si false, simply return the overridden value
			$dbl_Effort = is_null($obj_Resultset[0]['effort']) ? 0 : floatval($obj_Resultset[0]['effort']);

		} else {

			// If the Auto flag is set to True, the effort is recursively calculated from the child tasks
			$str_Query = '
				select
					activities.activityID,
					activities.activityParentID,
					activities.activityEstimatedEffortHours as effort,
					activities.activityIsEstimatedEffortHoursAuto as isAuto
				from activities
				where activities.activityCustomerID = ?
					and activities.activityParentID = ?
			';

			$obj_ResultsetChild = $this->dbHandler->executePrepared($str_Query,
			                                                   array(
				                                                   array($this->domainID => 'i'),
				                                                   array($int_ActivityID => 'i')
			                                                   ));


			if (count($obj_ResultsetChild) == 0) {

				// If no child is found, return zero
				$dbl_Effort = 0;

			} else {

				// If children are found, for each of them repeat the procedure and get the effort
				$arr_ChildEffort = array();

				foreach ($obj_ResultsetChild as $arr_Datarow) {
					$arr_ChildEffort[] = $this->getEstimatedEffort($arr_Datarow['activityID']);
				}

				// The final effort is the sum of the children's effort
				$dbl_Effort = array_sum($arr_ChildEffort);

			}

		}


		return $dbl_Effort;

	}


	/** Gets the child activities of the selected activity
	 *
	 * @param int $int_ActivityID The given activity ID
	 * @return array
	 */
	public function getChildActivities($int_ActivityID) {

		$str_Query = '
			select
				activities.activityID,
				activities.activityName,
				users.userName
			from activities
			left join users
				on users.userNick = activities.activityManagerNick
			where activities.activityCustomerID = ?
				and activities.activityParentID = ?
		';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
		                                                   array(
			                                                   array($this->domainID => 'i'),
			                                                   array($int_ActivityID => 'i')
		                                                   ));

		$arr_Result = array();

		foreach ($obj_Resultset as $arr_Datarow) {
			$arr_Result[$arr_Datarow['activityID']] = array(
				'name' => $arr_Datarow['activityName'],
				'manager' => $arr_Datarow['userName']
			);
		}


		return $arr_Result;

	}


	/** Gets the current activity's timeslot hours, grouped by user.
	 *  The retrieval can be performed recursively, to get also the children activities' timeslots,
	 *  or in plain mode, to get only direct timeslots.
	 *
	 * @param int $int_ActivityID The given activity ID
	 * @param bool $bool_RecursiveMode Flag that activates or deactivates the recursive mode
	 * @return array
	 */
	public function getTimeslotHours($int_ActivityID, $bool_RecursiveMode = TRUE) {

		// First get direct timeslot hours
		$arr_TimeslotHours = array();

		$str_Query = '
			select
				users.userName,
				sum(timeslots.timeslotDuration) as timeslotHours
			from timeslots
			inner join users
				on timeslots.timeslotUserNick = users.userNick
			where timeslots.timeslotActivityID = ?
			group by users.userName
		';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
		                                                   array(
			                                                   array($int_ActivityID => 'i')
		                                                   ));

		// Start populating the array
		foreach ($obj_Resultset as $arr_Datarow) {
			$arr_TimeslotHours[$arr_Datarow['userName']] = isset($arr_TimeslotHours[$arr_Datarow['userName']]) ?
				($arr_TimeslotHours[$arr_Datarow['userName']] + floatval($arr_Datarow['timeslotHours'])) :
				floatval($arr_Datarow['timeslotHours']);
		}


		// If the Recursive Mode flag is True, retrieve timeslot hours of the descendants
		if ($bool_RecursiveMode) {

			$arr_ChildrenActivities = $this->getChildActivities($int_ActivityID);

			foreach (array_keys($arr_ChildrenActivities) as $int_ChildID) {
				$arr_ChildHours = $this->getTimeslotHours($int_ChildID);

				//var_dump($arr_ChildHours);

				foreach ($arr_ChildHours as $str_User => $dbl_Hours) {
					$arr_TimeslotHours[$str_User] = isset($arr_TimeslotHours[$str_User]) ?
						($arr_TimeslotHours[$str_User] + floatval($dbl_Hours)) :
						floatval($dbl_Hours);
				}
			}

		}


		return $arr_TimeslotHours;

	}


	/** Calculates activity completion based on activity timeslots.
	 *  If the completion is manually set, it simply returns the value.
	 *  If the completion is set to be automatically calculated, the value is calculated upon activity estimated effort and whole timeslots.
	 * .
	 * @param int $int_ActivityID The given Activity ID
	 * @return float
	 */
	public function getCompletion($int_ActivityID) {

		// First get activity and saved data
		$str_Query = '
			select
				activities.activityID,
				activities.activityCompletion as completion,
				activities.activityIsCompletionAuto as isAuto
			from activities
			where activities.activityCustomerID = ?
				and activities.activityID = ?
		';

		$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
		                                                   array(
			                                                   array($this->domainID => 'i'),
			                                                   array($int_ActivityID => 'i')
		                                                   ));


		$dbl_Completion = NULL;

		if (!(boolean) $obj_Resultset[0]['isAuto']) {

			// If Auto flag is set to False, the saved value is returned
			$dbl_Completion = is_null($obj_Resultset[0]['completion']) ? 0 : floatval($obj_Resultset[0]['completion']);

		} else {

			// Get activity's whole timeslots and estimated effort
			$dbl_TimeslotHours = array_sum($this->getTimeslotHours($int_ActivityID, TRUE));
			$dbl_EffortHours = $this->getEstimatedEffort($int_ActivityID);

			// Calculates the completion
			if ($dbl_EffortHours > 0) {
				$dbl_Completion = $dbl_TimeslotHours / $dbl_EffortHours * 100;
			} else {
				$dbl_Completion = 0;
			}

		}


		return $dbl_Completion;

	}


	/** Calculates the end date for the given activity, using working days
	 *
	 * @param int $int_ActivityID
	 * @param bool $bool_AdvancedCalculation
	 * @return string
	 */
	public function getEndDate($int_ActivityID, $bool_AdvancedCalculation = FALSE) {

		// First, get the starting date in string format
		$arr_Data = $this->read($int_ActivityID);
		$str_StartDate = $arr_Data[$int_ActivityID]['activityStartDate'];

		$str_EndDate = NULL;


		if ($bool_AdvancedCalculation) {

			// Coming soon...
			// TODO: add advanced end date calculation method

		} else {

			$dbl_EffortHours = $this->getEstimatedEffort($int_ActivityID);
			$int_DaysToAdd = round($dbl_EffortHours / 8);

			$str_EndDate = date('Y-m-d', strtotime($str_StartDate . ' +' . $int_DaysToAdd . ' Weekday'));

		}


		return $str_EndDate;

	}


	/** Returns the XML string for Gantt construction
	 *
	 * @param int $int_ActivityID If given, outputs data for the given activity only
	 * @return string
	 */
	public function getGanttData($int_ActivityID = NULL) {

		$arr_ActivityTypes = $this->getActivityTypes();

		$arr_GanttData = array();


		// Get all activities, starting from main categories
		foreach ($arr_ActivityTypes as $int_ActivityTypeID => $str_ActivityTypeData) {

			// Add activity type Gantt group
			$arr_ActivityTypeGanttData = array(
				'pID'       =>  -$int_ActivityTypeID,
				'pName'     =>  $str_ActivityTypeData['name'],
				'pStart'    =>  '',
				'pEnd'      =>  '',
				'pClass'    =>  'ggroupblack',
				'pMile'     =>  0,
				'pComp'     =>  0,
				'pGroup'    =>  1,
				'pParent'   =>  0,
				'pOpen'     =>  1
			);

			$arr_GanttData['task'][] = $arr_ActivityTypeGanttData;

			// Query to get basic information for Gantt
			$str_Query = '
				select
					activities.activityID,
					activities.activityParentID,
					activities.activityName,
					users.userName,
					activities.activityStartDate
				from activities
				inner join users
					on activities.activityManagerNick = users.userNick
				where activities.activityCustomerID = ?
			';

			if ($int_ActivityID !== NULL) {
				$str_Query .= ' and activities.activityID = ?';
			}

			// Execute query
			if ($int_ActivityID === NULL) {
				$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
				                                                   array(
					                                                   array($this->domainID => 'i')
				                                                   ));
			} else {
				$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
				                                                   array(
					                                                   array($this->domainID => 'i'),
					                                                   array($int_ActivityID => 'i')
				                                                   ));
			}


			// Fetch result and compose Gantt data array
			foreach ($obj_Resultset as $arr_Datarow) {

				$arr_ActivityGanttData = array(
					'pID'       =>  $arr_Datarow['activityID'],
					'pName'     =>  $arr_Datarow['activityName'],
					'pStart'    =>  $arr_Datarow['activityStartDate'],
					'pEnd'      =>  $this->getEndDate($arr_Datarow['activityID']),
					'pRes'      =>  $arr_Datarow['userName'],
					'pMile'     =>  0,
					'pComp'     =>  $this->getCompletion($arr_Datarow['activityID']),
					'pGroup'    =>  1,
					'pParent'   =>  is_null($arr_Datarow['activityParentID']) ? -$int_ActivityTypeID : $arr_Datarow['activityParentID'],
					'pOpen'     =>  0
				);



				$arr_GanttData['task'][] = $arr_ActivityGanttData;

			}

			// TODO: add retrieval of milestone events

		}

		return array2xml($arr_GanttData, 'project');

	}

	

}
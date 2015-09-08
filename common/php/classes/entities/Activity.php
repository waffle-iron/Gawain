<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'entities/Entity.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');

class Activity extends Entity {

	// Child constructor
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
	 *  If the effort is set to be automatically calculated, the value is retrieved as the recursive sum of the descendant effort
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
			$dbl_Completion = $dbl_TimeslotHours / $dbl_EffortHours * 100;

		}


		return $dbl_Completion;

	}






	

}
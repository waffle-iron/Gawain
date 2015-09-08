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
				activities.activityParentID,
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


		$int_Result = NULL;


		if (!(boolean) $obj_Resultset[0]['isAuto']) {

			// If a value is present and the Aut flag si false, simply return the overridden value
			$int_Result = is_null($obj_Resultset[0]['effort']) ? 0 : floatval($obj_Resultset[0]['effort']);

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
				$int_Result = 0;

			} else {

				// If children are found, for each of them repeat the procedure and get the effort
				$arr_ChildEffort = array();

				foreach ($obj_ResultsetChild as $arr_Datarow) {
					$arr_ChildEffort[] = $this->getEstimatedEffort($arr_Datarow['activityID']);
				}

				// The final effort is the sum of the children's effort
				$int_Result = array_sum($arr_ChildEffort);

			}

		}


		return $int_Result;

	}



	

}
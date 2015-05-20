<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'entities/Entity.php');

class Activity extends Entity {

	// Child constructor
	public function __construct($str_SessionID) {

		// Sets entity reference code
		$this->entityCode = 'activity';

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
				array($this->currentCustomerID  =>  'i')
			));

		$arr_Output = array();

		foreach ($obj_Resultset as $arr_Row) {
			$int_ID = $arr_Row['ID'];
			unset($arr_Row['ID']);
			$arr_Output[$int_ID] = $arr_Row;
		}

		return $arr_Output;

	}
	

}
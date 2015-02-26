<?php

require_once(__DIR__ . '/../../classes/options/options.php');

abstract class entity_manager {

	// Reference entity code
	protected $entityCode;


	// Reference entity label
	public $entityLabel;


	// All available fields for selected entity
	protected $availableFields;


	// Currently enabled fields
	protected $enabledFields;


	// Current customer ID
	public $currentCustomerID;


	// Session code
	protected $sessionID;


	// Database Hanlder
	protected $db_handler;


	// Options
	protected $options;



	// Constructor
	public function __construct($str_SessionID) {

		// Sets inner class' data
		$this->sessionID = $str_SessionID;
		$this->options = new options;


		// Sets the correct DB handler
		switch($this->options->getValue()['environment']['DB']['type']) {
			case 'MySQL':
				require_once(__DIR__ . '/../../classes/database/mysql_handler.php');
				$this->db_handler = new mysql_handler;
				break;
		}


		// Sets remaining fields
		$this->retrieveCurrentCustomer();
		$this->retrieveCurrentEntityLabel();
		$this->retrieveAvailableFields();
		$this->retrieveEnabledFields();

	}


	// Get current customer ID
	protected function retrieveCurrentCustomer() {

		$str_CustomerPrepQuery =
			'select
				customerID
			from sessions
			where sessionID = ?';

		$arr_Result = $this->db_handler->execute_prepared($str_CustomerPrepQuery,
			array(
				$this->sessionID => 's'
			));

		$this->currentCustomerID = $arr_Result[0]['customerID'];

	}



	// Get the current entity label
	protected function retrieveCurrentEntityLabel() {

		$str_LabelPrepQuery =
			'select
				entityLabel
			from entities_label
			where customerID = ?
				and entityCode = ?';

		$obj_Result = $this->db_handler->execute_prepared($str_LabelPrepQuery,
			array(
				$this->currentCustomerID => 'i',
				$this->entityCode => 's'
			));

		$this->entityLabel = $obj_Result[0]['entityLabel'];

	}



	// Get all the available fields for selected entity
	protected function retrieveAvailableFields() {

		$str_AvailableFieldsPrepQuery = 
			'select
				fieldCode,
				fieldIsPrimaryID,
				tableName,
				columnName,
				referentialTableName,
				referentialCodeColumnName,
				referentialValueColumnName,
				referentialCustomerDependencyColumnName,
				fieldComment
			from entities_reference_fields
			where entityCode = ?';
	
		$obj_Result = $this->db_handler->execute_prepared($str_AvailableFieldsPrepQuery,
			array(
				$this->entityCode => 's'
			));

		foreach($obj_Result as $obj_ResultEntry) {
			$this->availableFields[$obj_ResultEntry['fieldCode']]['fieldIsPrimaryID'] = $obj_ResultEntry['fieldIsPrimaryID'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['tableName'] = $obj_ResultEntry['tableName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['columnName'] = $obj_ResultEntry['columnName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['referentialTableName'] = $obj_ResultEntry['referentialTableName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['referentialCodeColumnName'] = $obj_ResultEntry['referentialCodeColumnName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['referentialValueColumnName'] = $obj_ResultEntry['referentialValueColumnName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['referentialCustomerDependencyColumnName'] = $obj_ResultEntry['referentialCustomerDependencyColumnName'];
			$this->availableFields[$obj_ResultEntry['fieldCode']]['fieldComment'] = $obj_ResultEntry['fieldComment'];
		}

	}



	// Get all enabled fields with full specs
	protected function retrieveEnabledFields() {

		$str_EnabledFieldsPrepQuery = 
			'select
				render.renderingTypeCode,
				reference.fieldCode,
				reference.fieldIsPrimaryID,
				link.fieldOrderingIndex,
				link.fieldLabel,
				link.fieldTooltip,
				reference.tableName,
				reference.columnName,
				reference.fieldType,
				reference.referentialJoinType,
				reference.referentialTableName,
				reference.referentialCodeColumnName,
				reference.referentialValueColumnName,
				reference.referentialCustomerDependencyColumnName,
				display.elementBaseTag as displayElementBaseTag,
				display.elementTemplate as displayElementTemplate,
				edit.elementBaseTag as editElementBaseTag,
				edit.elementTemplate as editElementTemplate
			from entities_reference_fields reference
			inner join entities_linked_rendering_elements link
				on reference.fieldCode = link.fieldCode
			inner join rendering_types render
				on render.renderingTypeCode = link.renderingTypeCode
			left join rendering_display_elements display
				on link.fieldDisplayElementCode = display.elementCode
			left join rendering_edit_elements edit
				on link.fieldDisplayElementCode = edit.elementCode
			where reference.entityCode = ?
				and link.customerID = ?
			order by
				render.renderingTypeCode,
				link.fieldOrderingIndex,
				link.fieldLabel';


		$obj_Result = $this->db_handler->execute_prepared($str_EnabledFieldsPrepQuery,
			array(
				$this->entityCode => 's',
				$this->currentCustomerID => 'i'
			));

		foreach($obj_Result as $obj_ResultEntry) {$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['fieldIsPrimaryID'] = (bool) ($obj_ResultEntry['fieldIsPrimaryID']);

			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['fieldOrderingIndex'] = $obj_ResultEntry['fieldOrderingIndex'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['fieldLabel'] = $obj_ResultEntry['fieldLabel'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['fieldTooltip'] = $obj_ResultEntry['fieldTooltip'];

			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['tableName'] = $obj_ResultEntry['tableName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['columnName'] = $obj_ResultEntry['columnName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['fieldType'] = $obj_ResultEntry['fieldType'];

			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['referentialJoinType'] = $obj_ResultEntry['referentialJoinType'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['referentialTableName'] = $obj_ResultEntry['referentialTableName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['referentialCodeColumnName'] = $obj_ResultEntry['referentialCodeColumnName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['referentialValueColumnName'] = $obj_ResultEntry['referentialValueColumnName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['referentialCustomerDependencyColumnName'] = $obj_ResultEntry['referentialCustomerDependencyColumnName'];

			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['displayElementBaseTag'] = $obj_ResultEntry['displayElementBaseTag'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['displayElementTemplate'] = $obj_ResultEntry['displayElementTemplate'];

			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['editElementBaseTag'] = $obj_ResultEntry['editElementBaseTag'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]
				[$obj_ResultEntry['fieldCode']]
					['editElementTemplate'] = $obj_ResultEntry['editElementTemplate'];
		}

	}



	// Reads and returns data into formatted templates
	protected function readData($arr_Wheres, $str_RenderingType, $str_DisplayMode) {
		
		/*
			The Where conditions are expressed in this way:
				array(column_name => array('operator' => '=', 'arguments' => array(1))) 
		*/
		
		// Variables initialization
		$arr_SelectFields = array();
		$arr_CustomerDependency = array();
		$arr_From = array();

		// First compile the select query string
		foreach ($this->enabledFields[$str_RenderingType] as $str_FieldCode => $arr_FieldEntry) {
			
			$arr_From[] = $arr_FieldEntry['tableName'];
			
			// Checks if the field references another table
			if ($arr_FieldEntry['referentialJoinType'] !== NULL && $arr_FieldEntry['referentialTableName'] !== NULL && $arr_FieldEntry['referentialCodeColumnName'] !== NULL && $arr_FieldEntry['referentialValueColumnName'] !== NULL) {
				
				$str_Random = chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25)) .
					chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25)) . chr(65+rand(0,25));
				
				$arr_Joins[] = array(
					'table' => $arr_FieldEntry['referentialTableName'],
					'alias' => $str_Random,
					'customerColumnName' => $arr_FieldEntry['referentialCustomerDependencyColumnName'],
					'join' => array(
						'type' => $arr_FieldEntry['referentialJoinType'],
						'innerColumnName' => $arr_FieldEntry['columnName'],
						'outerColumnName' => $arr_FieldEntry['referentialCodeColumnName']
					)
				);
				
				// Checks if the referenced table has a customer dependency
				if ($arr_FieldEntry['referentialCustomerDependencyColumnName'] !== NULL) {
					$arr_CustomerDependency[] = $str_Random . '.' . $arr_FieldEntry['referentialCustomerDependencyColumnName'] . ' = ' . $this->currentCustomerID;
				}
				
				$arr_SelectFields[] = $str_Random . '.' . $arr_FieldEntry['referentialValueColumnName'] . ' as ' . $arr_FieldEntry['columnName'];
			} else {
				$arr_SelectFields[] = $arr_FieldEntry['tableName'] . '.' . $arr_FieldEntry['columnName']; 
			}
			
		}
		
		
		$str_QueryString = 'select ' . PHP_EOL;
		$str_QueryString .= implode(', ' . PHP_EOL, $arr_SelectFields) . PHP_EOL;
		$str_QueryString .= 'from ' . implode(', ', array_unique($arr_From)) . PHP_EOL;
		
		
		// Create join part
		$str_JoinString = '';
		
		foreach ($arr_Joins as $arr_RefData) {
			if ($arr_RefData['customerColumnName'] !== NULL) {
				$str_JoinString .= $arr_RefData['join']['type'] . ' join ' .  PHP_EOL;
				$str_JoinString .= '(select * from ' . $arr_RefData['table'] . ' where ' . $arr_RefData['customerColumnName'] . ' = ' . $this->currentCustomerID . ') '. $arr_RefData['alias'] . PHP_EOL;
			} else {
				$str_JoinString .= $arr_RefData['join']['type'] . ' join ' .  $arr_RefData['table'] . ' ' . $arr_RefData['alias'] . PHP_EOL;
			}
			
			$str_JoinString .= 'on ' . implode(', ', array_unique($arr_From)) . '.' . $arr_RefData['join']['innerColumnName'] . ' = ' . 
				$arr_RefData['alias'] . '.' . $arr_RefData['join']['outerColumnName'] . PHP_EOL;
		}
		
		$str_QueryString .= $str_JoinString;
		
		
		// Chans all the input where conditions
		if ($arr_Wheres !== NULL) {
			$arr_WhereFields = array();
			$arr_Parameters = array();
			
			foreach ($arr_Wheres as $str_WhereColumn => $arr_WhereCondition) {
				$str_WhereCondition = implode(', ', array_unique($arr_From)) . '.' . $str_WhereColumn . ' ' . $arr_WhereCondition['operator'] . ' ';
				
				// Currently the array arguments feature is used only in 'IN' conditions.
				// TODO: add support to more clauses that uses multiple arguments
				
				switch (strtolower($arr_WhereCondition['operator'])) {
					case 'in':
						$str_WhereCondition .= '(' . implode(', ', array_fill(1, count($arr_WhereCondition['arguments']), '?')) . ')';
						break;
					default:
						$str_WhereCondition .= implode(', ', array_fill(1, count($arr_WhereCondition['arguments']), '?'));
						break;
				}
				
				foreach ($arr_WhereCondition['arguments'] as $str_Argument) {
					$arr_Parameters[$str_Argument] = $this->enabledFields[$str_RenderingType][$str_WhereColumn]['fieldType'] == 'NUM' ? 'i' : 's';
				}
				
				$arr_WhereFields[] = $str_WhereCondition;
			}
			
			$str_QueryString .= ' where ' . implode(' and ', $arr_WhereFields);
		}
		
		
		
		// Execute the query and get raw data
		$arr_GetResult = $this->db_handler->execute_prepared($str_QueryString, $arr_Parameters);
		
		
		// Parse the results and render the result using display elements
		$str_RenderedResult = utf8_encode($this->renderData($arr_GetResult, $str_RenderingType, $str_DisplayMode));
		
		$arr_Result = array(
			'raw' 		=> $arr_GetResult,
			'rendered' 	=> $str_RenderedResult
		);
		
		return json_encode($arr_Result);

	}
	
	
	
	
	protected function insertData($str_Wheres, $arr_DataRows) {
		
	}
	
	
	
	
	
	// Renders the given items into their respective elements
	protected function renderData($arr_DataRows, $str_RenderingType, $str_DisplayMode) {
		
		$arr_Output = array();
		
		// If the input data row set is not null, renders the given data...
		if ($arr_DataRows !== NULL) {

			foreach ($arr_DataRows as $arr_ItemRow) {
				foreach ($arr_ItemRow as $str_ItemField => $str_ItemValue) {
					// Replace the element's markers with data
					$arr_Substitutions = array(
							// TODO: add more substitution expressions
							'%ID%'			=>	$this->enabledFields[$str_RenderingType][$str_ItemField]['columnName'],
							'%COLNAME%'		=>	$this->enabledFields[$str_RenderingType][$str_ItemField]['columnName'],
							'%LABEL%'		=>	$this->enabledFields[$str_RenderingType][$str_ItemField]['fieldLabel'],
							'%VALUE%'		=>	$str_ItemValue,
							'%IS_CHECKED%'	=>	(bool) $str_ItemValue ? 'checked' : ''
						);
					
					// The display mode can be either "display" or "edit"
					if ($str_DisplayMode == 'display') {
						$str_RenderedElement = str_replace(array_keys($arr_Substitutions),
								array_values($arr_Substitutions),
								$this->enabledFields[$str_RenderingType][$str_ItemField]['displayElementTemplate']);
					
					} elseif ($str_DisplayMode == 'edit') {
						$str_RenderedElement = str_replace(array_keys($arr_Substitutions),
								array_values($arr_Substitutions),
								$this->enabledFields[$str_RenderingType][$str_ItemField]['editElementTemplate']);
					}
					
					array_push($arr_Output, $str_RenderedElement);
				}
			}		
			
		} else {	// ... Else it returns empty or edit-ready rendering
			foreach ($this->enabledFields[$str_RenderingType] as $arr_EnabledField) {
				$arr_Substitutions = array(
						'%ID%'			=>	$arr_EnabledField['columnName'],
						'%COLNAME%'		=>	$arr_EnabledField['columnName'],
						'%LABEL%'		=>	$arr_EnabledField['fieldLabel'],
						'%VALUE%'		=>	'',
						'%IS_CHECKED%'	=>	''					
					);
				
				if ($str_DisplayMode == 'display') {
					$str_RenderedElement = NULL;
						
				} elseif ($str_DisplayMode == 'edit') {
					$str_RenderedElement = str_replace(array_keys($arr_Substitutions),
							array_values($arr_Substitutions),
							$arr_EnabledField['editElementTemplate']);
				}
					
				array_push($arr_Output, $str_RenderedElement);
			}
		}
	
		
		return implode('', $arr_Output);
		
	}

}

?>
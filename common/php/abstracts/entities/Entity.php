<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');



abstract class Entity {

	// Reference entity code
	protected $entityCode;


	// Reference entity label
	public $entityLabel;


	// Reference entity item label
	public $entityItemLabel;
	
	
	// Entity main ID field
	public $mainID;
	
	
	// Entity reference table
	protected $entityReferenceTable;


	// Entity domain dependency column
	protected $entityDomainDependencyColumn;


	// All available fields for selected entity
	protected $availableFields;


	// Current customer ID
	public $currentCustomerID;


	// Session code
	protected $sessionID;


	// Database Hanlder
	protected $dbHandler;


	// Options
	protected $options;


	// Renderer
	protected $renderer;



	/** Constructor
	 * 
	 * @param string $str_SessionID
	 */
	public function __construct($str_SessionID) {

		// Sets inner class' data
		$this->sessionID = $str_SessionID;
		$this->options = new Options();
		$this->dbHandler = db_autodefine($this->options);


		// Sets remaining fields
		$this->getCurrentCustomer();
		$this->getEntityInfo();
		$this->getAvailableFields();

	}


	// Get current customer ID
	private function getCurrentCustomer() {

		$str_CustomerPrepQuery =
			'select
				customerID
			from sessions
			where sessionID = ?';

		$arr_Result = $this->dbHandler->executePrepared($str_CustomerPrepQuery,
			array(
				array($this->sessionID => 's')
			));

		$this->currentCustomerID = $arr_Result[0]['customerID'];

	}



	// Get entity info
	private function getEntityInfo() {

		$str_InfoPrepQuery =
			'select
				entities_label.entityLabel,
				entities_label.entityItemLabel,
				entities.entityReferenceTable,
				entities.entityDomainDependencyColumnName
			from entities_label
			inner join entities
				on entities.entityCode = entities_label.entityCode
			where entities_label.customerID = ?
				and entities_label.entityCode = ?';

		$obj_Result = $this->dbHandler->executePrepared($str_InfoPrepQuery,
			array(
				array($this->currentCustomerID => 'i'),
				array($this->entityCode => 's')
			));

		$this->entityLabel = $obj_Result[0]['entityLabel'];
		$this->entityItemLabel = $obj_Result[0]['entityItemLabel'];
		$this->entityReferenceTable = $obj_Result[0]['entityReferenceTable'];
		$this->entityDomainDependencyColumn = $obj_Result[0]['entityDomainDependencyColumnName'];
	}



	// Get all the available fields for selected entity
	private function getAvailableFields() {

		$str_AvailableFieldsPrepQuery = 
			'select
				field.columnName,
				field.fieldIsMainID,
				field.fieldIsAutoIncrement,
				field.fieldIsNillable,
				field.fieldType,
				field.referentialJoinType,
				field.referentialTableName,
				field.referentialCodeColumnName,
				field.referentialValueColumnName,
				field.referentialCustomerDependencyColumnName,
				field.fieldComment,
				label.fieldLabel,
				label.fieldOrderingIndex,
				label.fieldComment
			from entities_reference_fields field
			inner join entities_columns_label label
				on field.entityCode = label.entityCode
				and field.columnName = label.columnName
			where label.customerID = ?
				and label.entityCode = ?
			order by label.fieldOrderingIndex';
	
		$obj_Result = $this->dbHandler->executePrepared($str_AvailableFieldsPrepQuery,
			array(
				array($this->currentCustomerID  =>  'i'),
				array($this->entityCode => 's')
			));

		foreach ($obj_Result as $obj_ResultEntry) {
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldIsAutoIncrement'] = $obj_ResultEntry['fieldIsAutoIncrement'];
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldIsNillable'] = $obj_ResultEntry['fieldIsNillable'];
			
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldType'] = $obj_ResultEntry['fieldType'];
			
			$this->availableFields[$obj_ResultEntry['columnName']]['referentialJoinType'] = $obj_ResultEntry['referentialJoinType'];
			$this->availableFields[$obj_ResultEntry['columnName']]['referentialTableName'] = $obj_ResultEntry['referentialTableName'];
			$this->availableFields[$obj_ResultEntry['columnName']]['referentialCodeColumnName'] = $obj_ResultEntry['referentialCodeColumnName'];
			$this->availableFields[$obj_ResultEntry['columnName']]['referentialValueColumnName'] = $obj_ResultEntry['referentialValueColumnName'];
			$this->availableFields[$obj_ResultEntry['columnName']]['referentialCustomerDependencyColumnName'] = $obj_ResultEntry['referentialCustomerDependencyColumnName'];
			
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldComment'] = $obj_ResultEntry['fieldComment'];

			$this->availableFields[$obj_ResultEntry['columnName']]['fieldLabel'] = $obj_ResultEntry['fieldLabel'];
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldOrderingIndex'] = $obj_ResultEntry['fieldOrderingIndex'];
			$this->availableFields[$obj_ResultEntry['columnName']]['fieldComment'] = $obj_ResultEntry['fieldComment'];

			
			if ($obj_ResultEntry['fieldIsMainID'] == 1) {
				$this->mainID = $obj_ResultEntry['columnName'];
			}

		}

	}




	/**
	 * Reads data
	 *
	 * <p>
	 * The Where conditions are expressed in this way:
	 * <pre>
	 * array(column_name => array(
	 * 		'operator' => '=',
	 * 		'arguments' => array(
	 * 			1
	 * 		)
	 * ))
	 * </pre>
	 * </p>
	 *
	 * @param mixed $arr_Wheres
	 * @param array $arr_SkipReferentialsFor
	 * @return array
	 */
	public function read($arr_Wheres, $arr_SkipReferentialsFor = array()) {

		// If $arr_Wheres is not an array, the main ID is assumed to be passed instead
		if (!is_array($arr_Wheres) && $arr_Wheres !== NULL) {
			$arr_Wheres = array(
				$this->mainID => array(
					'operator' => '=',
					'arguments' => array(
						$arr_Wheres
					)
				)
			);
		}

		// Variables initialization
		$arr_SelectFields = array();
		$arr_CustomerDependency = array();
		$arr_Joins = array();

		// First compile the select query string
		foreach ($this->availableFields as $str_FieldName => $arr_FieldEntry) {

			// Checks if the field references another table
			if ($arr_FieldEntry['referentialJoinType'] !== NULL
			    && $arr_FieldEntry['referentialTableName'] !== NULL
			    && $arr_FieldEntry['referentialCodeColumnName'] !== NULL
			    && $arr_FieldEntry['referentialValueColumnName'] !== NULL
				&& !in_array($str_FieldName, $arr_SkipReferentialsFor)) {

				$str_Random = generate_random_string();

				$arr_Joins[] = array(
					'table' => $arr_FieldEntry['referentialTableName'],
					'alias' => $str_Random,
					'customerColumnName' => $arr_FieldEntry['referentialCustomerDependencyColumnName'],
					'join' => array(
						'type' => $arr_FieldEntry['referentialJoinType'],
						'innerColumnName' => $str_FieldName,
						'outerColumnName' => $arr_FieldEntry['referentialCodeColumnName']
					)
				);

				// Checks if the referenced table has a customer dependency
				if ($arr_FieldEntry['referentialCustomerDependencyColumnName'] !== NULL) {
					$arr_CustomerDependency[] = $str_Random . '.' . $arr_FieldEntry['referentialCustomerDependencyColumnName'] . ' = ' . $this->currentCustomerID;
				}

				$arr_SelectFields[] = $str_Random . '.' . $arr_FieldEntry['referentialValueColumnName'] . ' as ' . $str_FieldName;
			} else {
				$arr_SelectFields[] = $this->entityReferenceTable . '.' . $str_FieldName;
			}

		}

		// In any case, always add main ID as first field
		array_unshift($arr_SelectFields, $this->entityReferenceTable . '.' . $this->mainID . ' as _entityMainID');


		$str_QueryString = 'select ' . PHP_EOL;
		$str_QueryString .= implode(', ' . PHP_EOL, $arr_SelectFields) . PHP_EOL;

		// If the domain dependency is set, a subquery is printed instead of the raw table name
		if ($this->entityDomainDependencyColumn !== NULL) {
			$str_QueryString .= 'from (select * from ' .
			                    $this->entityReferenceTable .
			                    ' where ' . $this->entityDomainDependencyColumn . ' = ' .
			                    $this->currentCustomerID . ') ' . $this->entityReferenceTable . PHP_EOL;
		} else {
			$str_QueryString .= 'from ' . $this->entityReferenceTable . PHP_EOL;
		}


		// Create join part
		$str_JoinString = '';

		foreach ($arr_Joins as $arr_RefData) {
			if ($arr_RefData['customerColumnName'] !== NULL) {
				$str_JoinString .= $arr_RefData['join']['type'] . ' join ' .  PHP_EOL;
				$str_JoinString .= '(select * from ' . $arr_RefData['table'] . ' where ' . $arr_RefData['customerColumnName'] . ' = ' . $this->currentCustomerID . ') '. $arr_RefData['alias'] . PHP_EOL;
			} else {
				$str_JoinString .= $arr_RefData['join']['type'] . ' join ' .  $arr_RefData['table'] . ' ' . $arr_RefData['alias'] . PHP_EOL;
			}

			$str_JoinString .= 'on ' . $this->entityReferenceTable . '.' . $arr_RefData['join']['innerColumnName'] . ' = ' .
			                   $arr_RefData['alias'] . '.' . $arr_RefData['join']['outerColumnName'] . PHP_EOL;
		}

		$str_QueryString .= $str_JoinString;


		// Chains all the input where conditions
		$arr_WhereOutput = $this->parseWhereArray($arr_Wheres);

		$str_QueryString .= $arr_WhereOutput['query'];
		$arr_Parameters = $arr_WhereOutput['parameters'];


		// Execute the query and get raw data
		$arr_GetResult = $this->dbHandler->executePrepared($str_QueryString, $arr_Parameters);

		$arr_Dataset = array(
			'data'      =>  array(),
			'fields'    =>  array()
		);


		// Groups data using main ID as key
		foreach ($arr_GetResult as $arr_GetRow) {
			$str_MainID = $arr_GetRow['_entityMainID'];
			unset($arr_GetRow['_entityMainID']);
			$arr_Dataset['data'][$str_MainID] = $arr_GetRow;
		}


		// Add fields info to dataset
		foreach ($this->availableFields as $str_ColumnName => $arr_ColumnSpec) {
			$arr_Dataset['fields'][$str_ColumnName]['label'] = $arr_ColumnSpec['fieldLabel'];
			$arr_Dataset['fields'][$str_ColumnName]['orderingIndex'] = $arr_ColumnSpec['fieldOrderingIndex'];
			$arr_Dataset['fields'][$str_ColumnName]['comment'] = $arr_ColumnSpec['fieldComment'];
			$arr_Dataset['fields'][$str_ColumnName]['type'] = $arr_ColumnSpec['fieldType'];
			$arr_Dataset['fields'][$str_ColumnName]['autoIncrement'] = (boolean) $arr_ColumnSpec['fieldIsAutoIncrement'];
			$arr_Dataset['fields'][$str_ColumnName]['nillable'] = (boolean) $arr_ColumnSpec['fieldIsNillable'];


			if ($arr_ColumnSpec['referentialJoinType'] !== NULL) {
				$str_ReferentialFieldsQuery = 'select ' .
				                              $arr_ColumnSpec['referentialCodeColumnName'] . ' as ID, ' .
				                              $arr_ColumnSpec['referentialValueColumnName'] . ' as value ' .
				                              ' from ' . $arr_ColumnSpec['referentialTableName'];

				if ($arr_ColumnSpec['referentialCustomerDependencyColumnName'] !== NULL) {
					$str_ReferentialFieldsQuery .= ' where ' . $arr_ColumnSpec['referentialCustomerDependencyColumnName'] . ' = ?';
					$arr_Resultset = $this->dbHandler->executePrepared($str_ReferentialFieldsQuery,
					                                                   array(
						                                                   array($this->currentCustomerID  =>  'i')
					                                                   ));

				} else {
					$arr_Resultset = $this->dbHandler->executePrepared($str_ReferentialFieldsQuery, NULL);
				}


				$arr_Referentials = array();

				foreach ($arr_Resultset as $arr_Row) {
					$arr_Referentials[$arr_Row['ID']] = $arr_Row['value'];
				}

				$arr_Dataset['fields'][$str_ColumnName]['referentials'] = $arr_Referentials;
			} else {
				$arr_Dataset['fields'][$str_ColumnName]['referentials'] = NULL;
			}
		}


		return $arr_Dataset;

	}


	
	
	
	/**
	 * Inserts new data.
	 * Data rows derive from JSON format (converted in connector)
	 * 
	 * @param array $arr_DataRows
	 * @throws Exception
	 * @return boolean
	 */
	public function insert($arr_DataRows) {
		// TODO: add multitenancy enforcement to insert statement
		// First, check if the proposed datarows keys are contained in entity available fields
		$arr_DataRowsFields = array_keys($arr_DataRows);
		$arr_AvailableFields = array_keys($this->availableFields);
		
		if (sizeof(array_diff($arr_DataRowsFields, $arr_AvailableFields)) > 1) {
			throw new Exception('Invalid fields in insert statement');
			
		} else {
			// Compose the insert statement
			$str_Query = 'insert into ' . $this->entityReferenceTable . PHP_EOL;
			$str_Query .= '(' . implode(', ', $arr_DataRowsFields) . ') ' . PHP_EOL;
			
			// Loop to insert prepared statement marks
			$arr_PreparedMarks = array();
			$arr_ParametersType = array();
			$arr_Parameters = array();
			
			foreach ($arr_DataRowsFields as $str_FieldName) {
				$arr_PreparedMarks[] = '?';
				$arr_ParametersType[] = $this->availableFields[$str_FieldName]['fieldType'] == 'NUM' ? 'i' : 's';
			}
			
			$str_Query .= 'values (' . implode(', ', $arr_PreparedMarks) . ')';
			
			$arr_ParametersValue = array_values($arr_DataRows);
			
			for ($int_ParameterCounter = 0; $int_ParameterCounter < sizeof($arr_ParametersType); $int_ParameterCounter++) {
				$arr_Parameters[] = array($arr_ParametersValue[$int_ParameterCounter] => $arr_ParametersType[$int_ParameterCounter]);
			}
			
			
			// Starts transaction and insert data
			$this->dbHandler->beginTransaction();
			$this->dbHandler->executePrepared($str_Query, $arr_Parameters);
			$this->dbHandler->commit();
			
			return TRUE;
			
		}
		
	}
	
	
	
	
	
	/** Updates existing data
	 * 
	 * @param array $arr_Wheres
	 * @param array $arr_DataRows
	 * @throws Exception
	 * @return boolean
	 */
	public function update($arr_Wheres, $arr_DataRows) {
		
		// If $arr_Wheres is not an array, the main ID is assumed to be passed instead
		if (!is_array($arr_Wheres) && $arr_Wheres !== NULL) {
			$arr_Wheres = array(
					$this->mainID => array(
							'operator' => '=',
							'arguments' => array(
									$arr_Wheres
							)
					)
			);
		}
		
		// First, check if the proposed datarows keys are contained in entity avaiable fields
		$arr_DataRowsFields = array_keys($arr_DataRows);
		$arr_AvailableFields = array_keys($this->availableFields);
		
		if (sizeof(array_diff($arr_DataRowsFields, $arr_AvailableFields)) > 1) {
			throw new Exception('Invalid fields in insert statement');

		} else {
			
			// Compose the update statement
			$str_Query = 'update ' . $this->entityReferenceTable . PHP_EOL;
			
			// Create the 'set' part
			$str_Set = 'set ';
			$arr_SetValues = array();
			
			foreach (array_keys($arr_DataRows) as $str_SetValue) {
				$arr_SetValues[] = $str_SetValue . ' = ?';
			}
			
			$str_Set .= implode(', ', $arr_SetValues);
			$str_Query .= $str_Set;
			
			
			// Create the 'where' part
			$arr_WhereOutput = $this->parseWhereArray($arr_Wheres);
			$str_Where = $arr_WhereOutput['query'];
			$str_Query .= $str_Where;

			
			// Create the parameters array
			$arr_PreparedMarks = array();
			$arr_ParametersType = array();
			$arr_Parameters = array();
				
			foreach ($arr_DataRowsFields as $str_FieldName) {
				$arr_PreparedMarks[] = '?';
				$arr_ParametersType[] = $this->availableFields[$str_FieldName]['fieldType'] == 'NUM' ? 'i' : 's';
			}
				
			$arr_ParametersValue = array_values($arr_DataRows);
				
			for ($int_ParameterCounter = 0; $int_ParameterCounter < sizeof($arr_ParametersType); $int_ParameterCounter++) {
				$arr_Parameters[] = array($arr_ParametersValue[$int_ParameterCounter] => $arr_ParametersType[$int_ParameterCounter]);
			}
			
			$arr_Parameters = array_merge($arr_Parameters, $arr_WhereOutput['parameters']);
			
			
			// Perform the update
			$this->dbHandler->beginTransaction();
			$this->dbHandler->executePrepared($str_Query, $arr_Parameters);
			$this->dbHandler->commit();
				
			return TRUE;
		}
	}
	
	
	
	
	/** Deletes existing data
	 * 
	 * @param array $arr_Wheres
	 * @return boolean
	 */
	public function delete($arr_Wheres) {
		
		// If $arr_Wheres is not an array, the main ID is assumed to be passed instead
		if (!is_array($arr_Wheres) && $arr_Wheres !== NULL) {
			$arr_Wheres = array(
					$this->mainID => array(
							'operator' => '=',
							'arguments' => array(
									$arr_Wheres
							)
					)
			);
		}
		
		// Start writing the query
		$str_Query = 'delete from ' . $this->entityReferenceTable . PHP_EOL;
		
		// Create the 'where' part
		$arr_WhereOutput = $this->parseWhereArray($arr_Wheres);
		$str_Query .= $arr_WhereOutput['query'];
		$arr_Parameters = $arr_WhereOutput['parameters'];
		
		// Perform the delete
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_Query, $arr_Parameters);
		$this->dbHandler->commit();
		
		return TRUE;
	}


	/** Gets the referential values for the given column
	 *
	 * @param string $str_ColumnName
	 * @return mixed
	 */
	public function getReferentialValuesFor($str_ColumnName) {

		if ($this->availableFields[$str_ColumnName]['referentialJoinType'] !== NULL
			&& $this->availableFields[$str_ColumnName]['referentialTableName'] !== NULL
			&& $this->availableFields[$str_ColumnName]['referentialCodeColumnName'] !== NULL
			&& $this->availableFields[$str_ColumnName]['referentialValueColumnName'] !== NULL) {

			$str_Query = '
				select
					' . $this->availableFields[$str_ColumnName]['referentialCodeColumnName'] . ' as ID,
					' . $this->availableFields[$str_ColumnName]['referentialValueColumnName'] . ' as value
				from ' . $this->availableFields[$str_ColumnName]['referentialTableName'] . ' ';

			if ($this->availableFields[$str_ColumnName]['referentialCustomerDependencyColumnName'] !== NULL) {
				$str_Query .= 'where ' .
				              $this->availableFields[$str_ColumnName]['referentialCustomerDependencyColumnName'] .
				              ' = ' . $this->currentCustomerID;
			}

			$arr_Result = $this->dbHandler->executePrepared($str_Query, NULL);
			$arr_Output = array();

			foreach ($arr_Result as $str_Value) {
				$arr_Output[$str_Value['ID']] = $str_Value['value'];
			}

			return $arr_Output;


		} else {
			return false;
		}

	}




	/** Parses Where array to compose a well formed Where condition
	 *
	 * @param array $arr_Wheres
	 *
	 * @return array
	 */
	protected function parseWhereArray($arr_Wheres) {
		if ($arr_Wheres !== NULL) {
			$arr_WhereFields = array();
			$arr_Parameters = array();

			foreach ($arr_Wheres as $str_WhereColumn => $arr_WhereCondition) {
				$str_WhereCondition = $this->entityReferenceTable . '.' .
				                      $str_WhereColumn . ' ' . $arr_WhereCondition['operator'] . ' ';

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
					$arr_Parameters[] = array($str_Argument => $this->availableFields[$str_WhereColumn]['fieldType'] == 'NUM' ? 'i' : 's');
				}

				$arr_WhereFields[] = $str_WhereCondition;
			}

			$str_QueryString = ' where ' . implode(' and ', $arr_WhereFields);

			$str_QueryString .= ' and ' . $this->entityReferenceTable . '.' . $this->entityDomainDependencyColumn . ' = ' . $this->currentCustomerID;


		} else {
			$arr_Parameters = NULL;
			$str_QueryString = ' where ' . $this->entityReferenceTable . '.' . $this->entityDomainDependencyColumn . ' = ' . $this->currentCustomerID;
		}

		$arr_Output = array(
			'query'			=>	$str_QueryString,
			'parameters'	=>	$arr_Parameters
		);

		return($arr_Output);
	}
	
	
	

}
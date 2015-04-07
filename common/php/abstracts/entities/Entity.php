<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

abstract class Entity {

	// Reference entity code
	protected $entityCode;


	// Reference entity label
	public $entityLabel;
	
	
	// Entity main ID field
	public $mainID;
	
	
	// Entity reference table
	protected $entityReferenceTable;


	// All available fields for selected entity
	protected $availableFields;


	// Currently enabled fields
	protected $enabledFields;


	// Current customer ID
	public $currentCustomerID;


	// Session code
	protected $sessionID;


	// Database Hanlder
	protected $dbHandler;


	// Options
	protected $options;



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
		$this->getEnabledFields();

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
				entityLabel,
				entityReferenceTable
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
		$this->entityReferenceTable = $obj_Result[0]['entityReferenceTable'];
	}



	// Get all the available fields for selected entity
	private function getAvailableFields() {

		$str_AvailableFieldsPrepQuery = 
			'select
				columnName,
				fieldIsMainID,
				fieldIsAutoIncrement,
				fieldIsNillable,
				fieldType,
				referentialJoinType,
				referentialTableName,
				referentialCodeColumnName,
				referentialValueColumnName,
				referentialCustomerDependencyColumnName,
				fieldComment
			from entities_reference_fields
			where entityCode = ?';
	
		$obj_Result = $this->dbHandler->executePrepared($str_AvailableFieldsPrepQuery,
			array(
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
			
			$this->mainID = $obj_ResultEntry['fieldIsMainID'];
		}

	}



	// Get all enabled fields with full specs
	private function getEnabledFields() {

		$str_EnabledFieldsPrepQuery = 
			'select
				rendering_types.renderingTypeCode,
				entities.entityReferenceTable as tableName,
				reference.columnName,
				reference.fieldIsAutoIncrement,
				reference.fieldIsNillable,
				link.fieldOrderingIndex,
				link.fieldGroupingLevel,
				link.fieldGroupingFunction,
				link.fieldLabel,
				link.fieldTooltip,
				reference.fieldType,
				reference.referentialJoinType,
				reference.referentialTableName,
				reference.referentialCodeColumnName,
				reference.referentialValueColumnName,
				reference.referentialCustomerDependencyColumnName,
				rendering_types.renderingTypeBeforeAllSnippet,
				rendering_types.renderingTypeBeforeEachRecordSnippet,
				render.elementBaseTag as renderingElementBaseTag,
				render.elementTemplate as renderingElementTemplate,
				rendering_types.renderingTypeBetweenEachRecordSnippet,
				rendering_types.renderingTypeAfterEachRecordSnippet,
				rendering_types.renderingTypeAfterAllSnippet
			from entities entities
			inner join entities_reference_fields reference
				on reference.entityCode = entities.entityCode
			inner join entities_linked_rendering_elements link
				on link.entityCode = reference.entityCode
				and link.columnName = reference.columnName
			inner join rendering_types rendering_types
				on rendering_types.renderingTypeCode = link.renderingTypeCode
			left join rendering_elements render
				on link.fieldRenderingElementCode = render.elementCode
			where reference.entityCode = ?
				and link.customerID = ?
			order by
				rendering_types.renderingTypeCode,
				link.fieldOrderingIndex,
				link.fieldLabel';


		$obj_Result = $this->dbHandler->executePrepared($str_EnabledFieldsPrepQuery,
			array(
				array($this->entityCode => 's'),
				array($this->currentCustomerID => 'i')
			));

		foreach ($obj_Result as $obj_ResultEntry) {
			// Field info
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldIsAutoIncrement'] = (bool) ($obj_ResultEntry['fieldIsAutoIncrement']);
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldIsNillable'] = (bool) ($obj_ResultEntry['fieldIsNillable']);
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldType'] = $obj_ResultEntry['fieldType'];
			
			// Field representation
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldOrderingIndex'] = $obj_ResultEntry['fieldOrderingIndex'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldGroupingLevel'] = $obj_ResultEntry['fieldGroupingLevel'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldGroupingFunction'] = $obj_ResultEntry['fieldGroupingFunction'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldLabel'] = $obj_ResultEntry['fieldLabel'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['fieldTooltip'] = $obj_ResultEntry['fieldTooltip'];


			// Referentials
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['referentialJoinType'] = $obj_ResultEntry['referentialJoinType'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['referentialTableName'] = $obj_ResultEntry['referentialTableName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['referentialCodeColumnName'] = $obj_ResultEntry['referentialCodeColumnName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['referentialValueColumnName'] = $obj_ResultEntry['referentialValueColumnName'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['referentialCustomerDependencyColumnName'] = $obj_ResultEntry['referentialCustomerDependencyColumnName'];
			
			
			// Rendering elements
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['renderingElementBaseTag'] = $obj_ResultEntry['renderingElementBaseTag'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['fields']
				[$obj_ResultEntry['columnName']]
					['renderingElementTemplate'] = $obj_ResultEntry['renderingElementTemplate'];
			
			// Global rendering settings
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['global']
				['renderingTypeBeforeAllSnippet'] = $obj_ResultEntry['renderingTypeBeforeAllSnippet'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['global']
				['renderingTypeBeforeEachRecordSnippet'] = $obj_ResultEntry['renderingTypeBeforeEachRecordSnippet'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['global']
				['renderingTypeBetweenEachRecordSnippet'] = $obj_ResultEntry['renderingTypeBetweenEachRecordSnippet'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['global']
				['renderingTypeAfterEachRecordSnippet'] = $obj_ResultEntry['renderingTypeAfterEachRecordSnippet'];
			$this->enabledFields[$obj_ResultEntry['renderingTypeCode']]['global']
				['renderingTypeAfterAllSnippet'] = $obj_ResultEntry['renderingTypeAfterAllSnippet'];
		}

	}



	/**
	 * Reads and returns data into formatted templates
	 * 
	 * The Where conditions are expressed in this way:
	 * array(column_name => array(
	 * 		'operator' => '=',
	 * 		'arguments' => array(
	 * 			1
	 * 		)
	 * ))
	 * 
	 * @param array $arr_Wheres
	 * @param string $str_RenderingType
	 * @param string $str_OutputFormat
	 * @return string
	 */
	public function read($arr_Wheres, $str_RenderingType, $str_OutputFormat = 'rendered') {
		
		// Variables initialization
		$arr_SelectFields = array();
		$arr_CustomerDependency = array();

		// First compile the select query string
		foreach ($this->enabledFields[$str_RenderingType]['fields'] as $str_FieldName => $arr_FieldEntry) {
			
			// Checks if the field references another table
			if ($arr_FieldEntry['referentialJoinType'] !== NULL && $arr_FieldEntry['referentialTableName'] !== NULL && $arr_FieldEntry['referentialCodeColumnName'] !== NULL && $arr_FieldEntry['referentialValueColumnName'] !== NULL) {
				
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
		
		
		$str_QueryString = 'select ' . PHP_EOL;
		$str_QueryString .= implode(', ' . PHP_EOL, $arr_SelectFields) . PHP_EOL;
		$str_QueryString .= 'from ' . $this->entityReferenceTable . PHP_EOL;
		
		
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
		$arr_WhereOutput = parse_where_array($arr_Wheres, $this->enabledFields[$str_RenderingType]['fields'], $this->entityReferenceTable);
		
		$str_QueryString .= $arr_WhereOutput['query'];
		$arr_Parameters = $arr_WhereOutput['parameters'];
		
		
		// Execute the query and get raw data
		$arr_GetResult = $this->dbHandler->executePrepared($str_QueryString, $arr_Parameters);
		
		
		// Set output type according to specified format
		switch ($str_OutputFormat) {
			case 'raw':
				// Outputs the required data in JSON format
				$str_Output = json_encode($arr_GetResult);
				break;
				
			case 'rendered':
				// Parse the results and render the result using display elements
				$str_Output = $this->render($arr_GetResult, $str_RenderingType);
				break;
				
			case 'blank':
				// Outputs a blank rendered form for data insertion
				$str_Output = $this->render(NULL, $str_RenderingType);
				break;
		}
		
		
		return $str_Output;

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
		// TODO: correct check and selection from available fields to enabled fields
		// First, check if the proposed datarows keys are contained in entity avaiable fields
		$arr_DataRowsFields = array_keys($arr_DataRows);
		$arr_AvailableFields = array_keys($this->availableFields);
		
		if (sizeof(array_diff($arr_DataRowsFields, $arr_AvailableFields)) > 1) {
			throw new Exception('Invalid fields in insert statement');
			return FALSE;
			
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
		
		// First, check if the proposed datarows keys are contained in entity avaiable fields
		$arr_DataRowsFields = array_keys($arr_DataRows);
		$arr_AvailableFields = array_keys($this->availableFields);
		
		if (sizeof(array_diff($arr_DataRowsFields, $arr_AvailableFields)) > 1) {
			throw new Exception('Invalid fields in insert statement');
			return FALSE;
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
			$arr_WhereOutput = parse_where_array($arr_Wheres, $this->availableFields, $this->entityReferenceTable);
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
		
		// Start writing the query
		$str_Query = 'delete from ' . $this->entityReferenceTable . PHP_EOL;
		
		// Create the 'where' part
		$arr_WhereOutput = parse_where_array($arr_Wheres, $this->availableFields, $this->entityReferenceTable);
		$str_Query .= $arr_WhereOutput['query'];
		$arr_Parameters = $arr_WhereOutput['parameters'];
		
		// Perform the delete
		$this->dbHandler->beginTransaction();
		$this->dbHandler->executePrepared($str_Query, $arr_Parameters);
		$this->dbHandler->commit();
		
		return TRUE;
	}
	
	
	
	
	

	/** Renders the given items into their respective elements
	 * 
	 * @param array $arr_DataRows
	 * @param string $str_RenderingType
	 * @return string
	 */
	protected function render($arr_DataRows, $str_RenderingType) {
		
		$arr_Output = array();
		
		// If the input data row set is not null, renders the given data...
		if ($arr_DataRows !== NULL) {

			foreach ($arr_DataRows as $arr_ItemRow) {
				$arr_OutputRow = array();
				
				foreach ($arr_ItemRow as $str_ItemField => $str_ItemValue) {
					// If base element is a select, fill the options using data from referential tables
					$str_Options = '';
					
					if ($this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['renderingElementBaseTag'] == 'select' &&
							$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialCodeColumnName'] !== NULL &&
							$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialValueColumnName'] !== NULL &&
							$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialTableName'] !== NULL) {
								
						try {
							$str_SelectStatement = 'select ' .
								$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialCodeColumnName'] . ' as ID, ' .
								$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialValueColumnName'] . ' as value ' .
								'from ' . $this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['referentialTableName'];
								
							$obj_SelectResults = $this->dbHandler->executePrepared($str_SelectStatement, NULL);
						} catch (Exception $exc_WrongReferential) {
							echo 'Wrong referential: ' . $exc_WrongReferential->getMessage();
						}
						
						
						// NULL value if no option is present
						$str_Options .= '<option value=\'NULL\'' .
								($str_ItemValue == NULL ? 'selected' : '') . '>' .
								'---</option>';
						
						foreach ($obj_SelectResults as $arr_SelectResult) {
							$str_Options .= '<option value=\'' .
								$arr_SelectResult['ID'] . '\'' .
								($arr_SelectResult['value'] == $str_ItemValue ? 'selected' : '') . '>' .
								$arr_SelectResult['value'] . '</option>';
						}
					}
					
					
					// Replace the element's markers with data
					$arr_Substitutions = array(
							// TODO: add more substitution expressions
							'%ID%'			=>	$str_ItemField,
							'%TABLENAME%'	=>	$this->entityReferenceTable,
							'%COLNAME%'		=>	$str_ItemField,
							'%LABEL%'		=>	$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['fieldLabel'],
							'%VALUE%'		=>	$str_ItemValue,
							'%IS_CHECKED%'	=>	(bool) $str_ItemValue ? 'checked' : '',
							'%IS_REQUIRED%'	=>	(bool) $this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['fieldIsNillable'] ? '' : 'required',
							'%OPTIONS%'		=>	$str_Options
					);
						
						
					
					$str_RenderedElement = str_replace(array_keys($arr_Substitutions),
							array_values($arr_Substitutions),
							$this->enabledFields[$str_RenderingType]['fields'][$str_ItemField]['renderingElementTemplate']);
						
					// Regular expression replace to minify the code
					//$str_RenderedElement = preg_replace(['/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s'], ['>','<','\\1'], $str_RenderedElement);
					
					$arr_OutputRow[] = $str_RenderedElement;
				}
				
				$arr_Output[] = $this->enabledFields[$str_RenderingType]['global']['renderingTypeBeforeEachRecordSnippet'] .
					implode(PHP_EOL, $arr_OutputRow) .
					$this->enabledFields[$str_RenderingType]['global']['renderingTypeAfterEachRecordSnippet'];
				
			}
			
		} else {	// ... Else it returns empty or edit-ready rendering
			foreach ($this->enabledFields[$str_RenderingType]['fields'] as $str_FieldName => $arr_EnabledField) {
				// If base element is a select, fill the options using data from referential tables
				$str_Options = '';
					
				if ($this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['renderingElementBaseTag'] == 'select' &&
					$this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialCodeColumnName'] !== NULL &&
					$this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialValueColumnName'] !== NULL &&
					$this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialTableName'] !== NULL) {
			
						try {
							$str_SelectStatement = 'select ' .
									$this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialCodeColumnName'] . ' as ID, ' .
									$this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialValueColumnName'] . ' as value ' .
									'from ' . $this->enabledFields[$str_RenderingType]['fields'][$str_FieldName]['referentialTableName'];
			
							$obj_SelectResults = $this->dbHandler->executePrepared($str_SelectStatement, NULL);
						} catch (Exception $exc_WrongReferential) {
							echo 'Wrong referential: ' . $exc_WrongReferential->getMessage();
						}
			
			
						// NULL value if no option is present
						$str_Options .= '<option value=\'NULL\' selected>' .
								'---</option>';
			
						foreach ($obj_SelectResults as $arr_SelectResult) {
							$str_Options .= '<option value=\'' .
									$arr_SelectResult['ID'] . '\'>' .
									$arr_SelectResult['value'] . '</option>';
						}
					}
						
						
				$arr_Substitutions = array(
						'%ID%'			=>	$str_FieldName,
						'%TABLENAME%'	=>	$this->entityReferenceTable,
						'%COLNAME%'		=>	$str_FieldName,
						'%LABEL%'		=>	$arr_EnabledField['fieldLabel'],
						'%VALUE%'		=>	'',
						'%IS_CHECKED%'	=>	'',
						'%IS_REQUIRED%'	=>	(bool) $arr_EnabledField['fieldIsNillable'] ? '' : 'required',
						'%OPTIONS%'		=>	$str_Options
					);
				
				$str_RenderedElement = str_replace(array_keys($arr_Substitutions),
						array_values($arr_Substitutions),
						$arr_EnabledField['renderingElementTemplate']);
					
				$arr_OutputRow[] = $str_RenderedElement;
			}
			
			$arr_Output[] = $this->enabledFields[$str_RenderingType]['global']['renderingTypeBeforeEachRecordSnippet'] .
				implode(PHP_EOL, $arr_OutputRow) .
				$this->enabledFields[$str_RenderingType]['global']['renderingTypeAfterEachRecordSnippet'];
			
		}
	
		
		return $this->enabledFields[$str_RenderingType]['global']['renderingTypeBeforeAllSnippet'] . 
			implode($this->enabledFields[$str_RenderingType]['global']['renderingTypeBetweenEachRecordSnippet'], $arr_Output) .
			$this->enabledFields[$str_RenderingType]['global']['renderingTypeAfterAllSnippet'];
		
	}
	
	
	
	
	
	/** Returns data using main ID as identifier
	 * 
	 * @param mixed $str_ID
	 * @param string $str_RenderingType
	 * @param string $str_OutputFormat
	 * @return mixed
	 */
	public function readByID($str_ID, $str_RenderingType, $str_OutputFormat) {
		return $this->read(array(
				array($this->mainID	=>	array(
						'operator'	=>	'=',
						'arguments'	=>	array($str_ID)
				))
		), $str_RenderingType, $str_OutputFormat);
	}

}

?>
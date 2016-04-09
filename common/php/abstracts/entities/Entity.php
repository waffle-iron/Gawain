<?php

require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_VENDOR_DIR . 'PHPColors/Color.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');


/**
 * Class from which derives all the other entities defined in Gawain.
 * To implement a new entity, the class must inherit this abstract
 */
abstract class Entity
{

    /** Reference entity code
     * @var Integer
     */
    protected $type;


    /** Reference entity label
     * @var string
     */
    protected $label;


    /** Reference entity item label
     * @var string
     */
    protected $itemLabel;


    /** Entity main ID field
     * @var string
     */
    protected $primaryKey;


    /** Entity reference table
     * @var string
     */
    protected $referenceTable;


    /** Entity domain dependency column
     * @var string
     */
    protected $domainDependencyColumn;


    /** All available fields for selected entity
     * @var array
     */
    protected $availableFields;


    /** Current domain ID
     * @var integer
     */
    protected $domainID;


    /** Session code
     * @var string
     */
    protected $sessionID;


    /** Database Hanlder
     * @var dbHandler
     */
    protected $dbHandler;


    /** Options
     * @var Options
     */
    protected $options;


    /** Constructor
     *
     * @param string $str_SessionID
     */
    public function __construct($str_SessionID)
    {

        // Sets inner class' data
        $this->sessionID = $str_SessionID;
        $this->options = new Options();
        $this->dbHandler = db_autodefine($this->options);


        // Sets remaining fields
        $this->getCurrentCustomer();
        $this->getEntityInfo();
        $this->getAvailableFields();

    }


    /** Get current customer ID
     *
     */
    private function getCurrentCustomer()
    {

        $str_CustomerPrepQuery = 'select
				customerID
			from sessions
			where sessionID = ?';

        $arr_Result = $this->dbHandler->executePrepared($str_CustomerPrepQuery, array(
            array($this->sessionID => 's')
        ));

        $this->domainID = $arr_Result[0]['customerID'];

    }


    /** Get entity info
     *
     */
    private function getEntityInfo()
    {

        $str_InfoPrepQuery = 'select
				entities_label.entityLabel,
				entities_label.entityItemLabel,
				entities.entityReferenceTable,
				entities.entityDomainDependencyColumnName
			from entities_label
			inner join entities
				on entities.entityCode = entities_label.entityCode
			where entities_label.customerID = ?
				and entities_label.entityCode = ?';

        $obj_Result = $this->dbHandler->executePrepared($str_InfoPrepQuery, array(
            array($this->domainID => 'i'),
            array($this->type => 's')
        ));

        // Parsing entity general info
        $this->label = isset($obj_Result[0]['entityLabel']) ? $obj_Result[0]['entityLabel'] : null;
        $this->itemLabel = isset($obj_Result[0]['entityItemLabel']) ? $obj_Result[0]['entityItemLabel'] : null;
        $this->referenceTable = isset($obj_Result[0]['entityReferenceTable']) ? $obj_Result[0]['entityReferenceTable'] : null;
        $this->domainDependencyColumn = isset($obj_Result[0]['entityDomainDependencyColumnName']) ? $obj_Result[0]['entityDomainDependencyColumnName'] : null;
    }


    /** Get all the available fields for selected entity
     *
     */
    private function getAvailableFields()
    {

        $str_AvailableFieldsPrepQuery = 'select
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

        $obj_Result = $this->dbHandler->executePrepared($str_AvailableFieldsPrepQuery, array(
            array($this->domainID => 'i'),
            array($this->type => 's')
        ));


        // Parsing entity fields info
        foreach ($obj_Result as $obj_ResultEntry) {
            $this->availableFields[$obj_ResultEntry['columnName']]['isAutoIncrement'] = (boolean)$obj_ResultEntry['fieldIsAutoIncrement'];
            $this->availableFields[$obj_ResultEntry['columnName']]['isNillable'] = (boolean)$obj_ResultEntry['fieldIsNillable'];

            $this->availableFields[$obj_ResultEntry['columnName']]['type'] = $obj_ResultEntry['fieldType'];

            $this->availableFields[$obj_ResultEntry['columnName']]['referentialJoinType'] = $obj_ResultEntry['referentialJoinType'];
            $this->availableFields[$obj_ResultEntry['columnName']]['referentialTableName'] = $obj_ResultEntry['referentialTableName'];
            $this->availableFields[$obj_ResultEntry['columnName']]['referentialCodeColumnName'] = $obj_ResultEntry['referentialCodeColumnName'];
            $this->availableFields[$obj_ResultEntry['columnName']]['referentialValueColumnName'] = $obj_ResultEntry['referentialValueColumnName'];
            $this->availableFields[$obj_ResultEntry['columnName']]['referentialCustomerDependencyColumnName'] = $obj_ResultEntry['referentialCustomerDependencyColumnName'];

            $this->availableFields[$obj_ResultEntry['columnName']]['comment'] = $obj_ResultEntry['fieldComment'];

            $this->availableFields[$obj_ResultEntry['columnName']]['label'] = $obj_ResultEntry['fieldLabel'];
            $this->availableFields[$obj_ResultEntry['columnName']]['orderingIndex'] = $obj_ResultEntry['fieldOrderingIndex'];


            // Search for entity primary key
            if ($obj_ResultEntry['fieldIsMainID'] == 1) {
                $this->primaryKey = $obj_ResultEntry['columnName'];
            }


            // Additional info about referentials
            if ($obj_ResultEntry['referentialJoinType'] !== null) {
                $str_ReferentialFieldsQuery = 'select ' . $obj_ResultEntry['referentialCodeColumnName'] . ' as ID, ' . $obj_ResultEntry['referentialValueColumnName'] . ' as value ' . ' from ' . $obj_ResultEntry['referentialTableName'];

                if ($obj_ResultEntry['referentialCustomerDependencyColumnName'] !== null) {
                    $str_ReferentialFieldsQuery .= ' where ' . $obj_ResultEntry['referentialCustomerDependencyColumnName'] . ' = ?';
                    $arr_Resultset = $this->dbHandler->executePrepared($str_ReferentialFieldsQuery, array(
                        array($this->domainID => 'i')
                    ));

                } else {
                    $arr_Resultset = $this->dbHandler->executePrepared($str_ReferentialFieldsQuery, null);
                }


                $arr_Referentials = array();

                foreach ($arr_Resultset as $arr_Row) {
                    $arr_Referentials[$arr_Row['ID']] = $arr_Row['value'];
                }

                $this->availableFields[$obj_ResultEntry['columnName']]['referentials'] = $arr_Referentials;
            } else {
                $this->availableFields[$obj_ResultEntry['columnName']]['referentials'] = null;
            }

        }

    }


    /** Reads data
     *
     * <p>
     * The Where conditions are expressed in this way:
     * <pre>
     * array(column_name => array(
     *        'operator' => '=',
     *        'arguments' => array(
     *            1
     *        )
     * ))
     * </pre>
     * </p>
     *
     * @param mixed $arr_Wheres
     * @param array $arr_SkipReferentialsFor
     *
     * @return array
     */
    public function read($arr_Wheres, $arr_SkipReferentialsFor = array())
    {

        // If $arr_Wheres is not an array, the main ID is assumed to be passed instead
        if (!is_array($arr_Wheres) && $arr_Wheres !== null) {
            $arr_Wheres = array(
                $this->primaryKey => array(
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

            // Skip printing of customer dependency column (hidden multitenancy) and main index printing (always included further in the code)
            if ($str_FieldName != $this->domainDependencyColumn && $str_FieldName != $this->primaryKey) {

                // Checks if the field references another table
                if ($arr_FieldEntry['referentialJoinType'] !== null && $arr_FieldEntry['referentialTableName'] !== null && $arr_FieldEntry['referentialCodeColumnName'] !== null && $arr_FieldEntry['referentialValueColumnName'] !== null && !in_array($str_FieldName,
                                                                                                                                                                                                                                                        $arr_SkipReferentialsFor)
                ) {

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
                    if ($arr_FieldEntry['referentialCustomerDependencyColumnName'] !== null) {
                        $arr_CustomerDependency[] = $str_Random . '.' . $arr_FieldEntry['referentialCustomerDependencyColumnName'] . ' = ' . $this->domainID;
                    }

                    $arr_SelectFields[] = $str_Random . '.' . $arr_FieldEntry['referentialValueColumnName'] . ' as ' . $str_FieldName;
                } else {
                    $arr_SelectFields[] = $this->referenceTable . '.' . $str_FieldName;
                }
            }

        }

        // In any case, always add main ID as first field
        array_unshift($arr_SelectFields, $this->referenceTable . '.' . $this->primaryKey . ' as _entityMainID');


        $str_QueryString = 'select ' . PHP_EOL;
        $str_QueryString .= implode(', ' . PHP_EOL, $arr_SelectFields) . PHP_EOL;

        // If the domain dependency is set, a subquery is printed instead of the raw table name
        if ($this->domainDependencyColumn !== null) {
            $str_QueryString .= 'from (select * from ' . $this->referenceTable . ' where ' . $this->domainDependencyColumn . ' = ' . $this->domainID . ') ' . $this->referenceTable . PHP_EOL;
        } else {
            $str_QueryString .= 'from ' . $this->referenceTable . PHP_EOL;
        }


        // Create join part
        $str_JoinString = '';

        foreach ($arr_Joins as $arr_RefData) {
            if ($arr_RefData['customerColumnName'] !== null) {
                $str_JoinString .= $arr_RefData['join']['type'] . ' join ' . PHP_EOL;
                $str_JoinString .= '(select * from ' . $arr_RefData['table'] . ' where ' . $arr_RefData['customerColumnName'] . ' = ' . $this->domainID . ') ' . $arr_RefData['alias'] . PHP_EOL;
            } else {
                $str_JoinString .= $arr_RefData['join']['type'] . ' join ' . $arr_RefData['table'] . ' ' . $arr_RefData['alias'] . PHP_EOL;
            }

            $str_JoinString .= 'on ' . $this->referenceTable . '.' . $arr_RefData['join']['innerColumnName'] . ' = ' . $arr_RefData['alias'] . '.' . $arr_RefData['join']['outerColumnName'] . PHP_EOL;
        }

        $str_QueryString .= $str_JoinString;


        // Chains all the input where conditions
        $arr_WhereOutput = $this->parseWhereArray($arr_Wheres);

        $str_QueryString .= $arr_WhereOutput['query'];
        $arr_Parameters = $arr_WhereOutput['parameters'];


        // Execute the query and get raw data
        $arr_GetResult = $this->dbHandler->executePrepared($str_QueryString, $arr_Parameters);
        $arr_Dataset = $this->reformatResultset($arr_GetResult, '_entityMainID');


        return $arr_Dataset;

    }

    /** Parses Where array to compose a well formed Where condition
     *
     * @param array $arr_Wheres
     *
     * @return array
     */
    protected function parseWhereArray($arr_Wheres)
    {
        if ($arr_Wheres !== null) {
            $arr_WhereFields = array();
            $arr_Parameters = array();

            foreach ($arr_Wheres as $str_WhereColumn => $arr_WhereCondition) {
                $str_WhereCondition = $this->referenceTable . '.' . $str_WhereColumn . ' ' . $arr_WhereCondition['operator'] . ' ';

                // Currently the array arguments feature is used only in 'IN' conditions.
                // TODO: add support to more clauses that uses multiple arguments

                switch (strtolower($arr_WhereCondition['operator'])) {
                    case 'in':
                        $str_WhereCondition .= '(' . implode(', ',
                                                             array_fill(1, count($arr_WhereCondition['arguments']),
                                                                        '?')) . ')';
                        break;
                    default:
                        $str_WhereCondition .= implode(', ',
                                                       array_fill(1, count($arr_WhereCondition['arguments']), '?'));
                        break;
                }

                foreach ($arr_WhereCondition['arguments'] as $str_Argument) {
                    $arr_Parameters[] = array(
                        $str_Argument => $this->availableFields[$str_WhereColumn]['type'] == 'NUM' || $this->availableFields[$str_WhereColumn]['type'] == 'BOOL' ? 'i' : 's'
                    );
                }

                $arr_WhereFields[] = $str_WhereCondition;
            }

            $str_QueryString = ' where ' . implode(' and ', $arr_WhereFields);

            $str_QueryString .= ' and ' . $this->referenceTable . '.' . $this->domainDependencyColumn . ' = ' . $this->domainID;

        } else {
            $arr_Parameters = null;
            $str_QueryString = ' where ' . $this->referenceTable . '.' . $this->domainDependencyColumn . ' = ' . $this->domainID;
        }

        $arr_Output = array(
            'query' => $str_QueryString,
            'parameters' => $arr_Parameters
        );

        return ($arr_Output);
    }

    /** Reformats the raw dataset to remove main ID and use it as array key
     *
     * @param array  $arr_Resultset
     * @param string $str_MainIDKey
     * @param string $str_DomainDependencyColumn
     *
     * @return array
     */
    protected function reformatResultset($arr_Resultset, $str_MainIDKey = null, $str_DomainDependencyColumn = null)
    {

        $arr_Dataset = array();

        if (!is_null($arr_Resultset)) {

            if (is_null($str_MainIDKey)) {
                $str_MainIDKey = $this->primaryKey;
            }

            if (is_null($str_DomainDependencyColumn)) {
                $str_DomainDependencyColumn = $this->domainDependencyColumn;
            }

            foreach ($arr_Resultset as $arr_GetRow) {
                $str_MainID = $arr_GetRow[$str_MainIDKey];
                unset($arr_GetRow[$str_MainIDKey]);

                if (isset($arr_GetRow[$str_DomainDependencyColumn])) {
                    unset($arr_GetRow[$str_DomainDependencyColumn]);
                }

                $arr_Dataset[$str_MainID] = $arr_GetRow;
            }

        }

        return $arr_Dataset;

    }

    /**
     * Inserts new data.
     * Data rows derive from JSON format (converted in connector)
     *
     * @param array $arr_DataRows
     *
     * @throws Exception
     * @return boolean
     */
    public function insert($arr_DataRows)
    {

        // Add multitenancy enforcement to insert statement
        if (isset($arr_DataRows[$this->domainDependencyColumn])) {
            unset($arr_DataRows[$this->domainDependencyColumn]);
        }
        $arr_DataRows[$this->domainDependencyColumn] = $this->domainID;


        // First, check if the proposed datarows keys are contained in entity available fields
        $arr_DataRowsFields = array_keys($arr_DataRows);
        $arr_AvailableFields = array_keys($this->availableFields);

        if (sizeof(array_diff($arr_DataRowsFields, $arr_AvailableFields)) > 1) {
            throw new Exception('Invalid fields in insert statement');

        } else {
            // Compose the insert statement
            $str_Query = 'insert into ' . $this->referenceTable . PHP_EOL;
            $str_Query .= '(' . implode(', ', $arr_DataRowsFields) . ') ' . PHP_EOL;

            // Loop to insert prepared statement marks
            $arr_PreparedMarks = array();
            $arr_ParametersType = array();
            $arr_Parameters = array();

            foreach ($arr_DataRowsFields as $str_FieldName) {
                if (!is_null($arr_DataRows[$str_FieldName])) {
                    $arr_PreparedMarks[] = '?';
                    $arr_ParametersType[] = $this->availableFields[$str_FieldName]['type'] == 'NUM' || $this->availableFields[$str_FieldName]['type'] == 'BOOL' ? 'i' : 's';
                } else {
                    $arr_PreparedMarks[] = 'null';
                }
            }

            $str_Query .= 'values (' . implode(', ', $arr_PreparedMarks) . ')';

            $arr_ParametersValue = array_values(array_filter($arr_DataRows));

            for ($int_ParameterCounter = 0; $int_ParameterCounter < sizeof($arr_ParametersType); $int_ParameterCounter++) {
                $arr_Parameters[] = array($arr_ParametersValue[$int_ParameterCounter] => $arr_ParametersType[$int_ParameterCounter]);
            }


            // Starts transaction and insert data
            $this->dbHandler->beginTransaction();
            $this->dbHandler->executePrepared($str_Query, $arr_Parameters);
            $this->dbHandler->commit();

            return true;

        }

    }

    /** Updates existing data
     *
     * @param array $arr_Wheres
     * @param array $arr_DataRows
     *
     * @throws Exception
     * @return boolean
     */
    public function update($arr_Wheres, $arr_DataRows)
    {

        // If $arr_Wheres is not an array, the main ID is assumed to be passed instead
        if (!is_array($arr_Wheres) && $arr_Wheres !== null) {
            $arr_Wheres = array(
                $this->primaryKey => array(
                    'operator' => '=',
                    'arguments' => array(
                        $arr_Wheres
                    )
                ),
                $this->domainDependencyColumn => array(
                    'operator' => '=',
                    'arguments' => array(
                        $this->domainID
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
            $str_Query = 'update ' . $this->referenceTable . PHP_EOL;

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
                $arr_ParametersType[] = $this->availableFields[$str_FieldName]['type'] == 'NUM' || $this->availableFields[$str_FieldName]['type'] == 'BOOL' ? 'i' : 's';
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

            return true;
        }
    }

    /** Deletes existing data
     *
     * @param array $arr_Wheres
     *
     * @return boolean
     */
    public function delete($arr_Wheres)
    {

        // If $arr_Wheres is not an array, the main ID is assumed to be passed instead
        if (!is_array($arr_Wheres) && $arr_Wheres !== null) {
            $arr_Wheres = array(
                $this->primaryKey => array(
                    'operator' => '=',
                    'arguments' => array(
                        $arr_Wheres
                    )
                ),
                $this->domainDependencyColumn => array(
                    'operator' => '=',
                    'arguments' => array(
                        $this->domainID
                    )
                )
            );
        }

        // Start writing the query
        $str_Query = 'delete from ' . $this->referenceTable . PHP_EOL;

        // Create the 'where' part
        $arr_WhereOutput = $this->parseWhereArray($arr_Wheres);
        $str_Query .= $arr_WhereOutput['query'];
        $arr_Parameters = $arr_WhereOutput['parameters'];

        // Perform the delete
        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_Query, $arr_Parameters);
        $this->dbHandler->commit();

        return true;
    }

    /** Gets the referential values for the given column
     *
     * @param string $str_ColumnName
     *
     * @return mixed
     */
    public function getReferentialValuesFor($str_ColumnName)
    {

        if ($this->availableFields[$str_ColumnName]['referentialJoinType'] !== null && $this->availableFields[$str_ColumnName]['referentialTableName'] !== null && $this->availableFields[$str_ColumnName]['referentialCodeColumnName'] !== null && $this->availableFields[$str_ColumnName]['referentialValueColumnName'] !== null) {

            $str_Query = '
				select
					' . $this->availableFields[$str_ColumnName]['referentialCodeColumnName'] . ' as ID,
					' . $this->availableFields[$str_ColumnName]['referentialValueColumnName'] . ' as value
				from ' . $this->availableFields[$str_ColumnName]['referentialTableName'] . ' ';

            if ($this->availableFields[$str_ColumnName]['referentialCustomerDependencyColumnName'] !== null) {
                $str_Query .= 'where ' . $this->availableFields[$str_ColumnName]['referentialCustomerDependencyColumnName'] . ' = ' . $this->domainID;
            }

            $arr_Result = $this->dbHandler->executePrepared($str_Query, null);
            $arr_Output = array();

            foreach ($arr_Result as $str_Value) {
                $arr_Output[$str_Value['ID']] = $str_Value['value'];
            }

            return $arr_Output;

        } else {
            return false;
        }

    }

    /** Gets information about entity fields (name, format, referentials and so on)
     *
     * @return array
     */
    public function getFieldsData()
    {
        return $this->availableFields;
    }

    /** Gets the domain dependency column for the given entity
     *
     * @return string
     */
    public function getDomainDependencyColumn()
    {
        return $this->domainDependencyColumn;
    }

    /** Gets the label of the current entity
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /** Get the item label (i.e. the label given to each record of the current entity)
     *
     * @return string
     */
    public function getItemLabel()
    {
        return $this->itemLabel;
    }

    /** Gets the primary key field of the current entity
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

}

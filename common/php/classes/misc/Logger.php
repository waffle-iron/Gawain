<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_CLASSES_DIR . '/misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

class Logger
{

    // Inner options handler
    private $options;

    // DB handler
    private $dbHandler;

    // Log table name
    private $logTableName;

    // Global log level
    private $logLevel;

    // Internal log levels
    private $logLevelsList = array(
        0 => 'FATAL ERROR',
        10 => 'ERROR',
        20 => 'WARNING',
        30 => 'INFO',
        99 => 'DEBUG'
    );

    // Reference entity
    private $entity;

    // Reference module
    private $module;


    /** Constructor
     *
     * @param string $str_Entity
     * @param string $str_Module = NULL
     */
    public function __construct($str_Entity, $str_Module = null)
    {
        $this->options = new Options();
        $this->dbHandler = db_autodefine($this->options);

        $this->logTableName = $this->options->get('log_table');
        $this->logLevel = $this->options->get('default_log_level');
        $this->entity = $str_Entity;
        $this->module = $str_Module;
    }


    /** Manually sets and override default log level
     *
     * @param string $str_LogLevel
     * @throws Exception
     * @return boolean
     */
    public function setLogLevel($str_LogLevel)
    {

        if (!in_array($str_LogLevel, $this->logLevelsList)) {
            throw new Exception('Invalid log level');
        } else {
            $this->logLevel = $str_LogLevel;
            return true;
        }
    }


    /** Inserts a log entry
     *
     * @param string $str_LogLevel
     * @param string $str_Message
     * @param string $str_UserNick
     * @param string $str_Hostname
     * @return boolean
     */
    public function log($str_LogLevel, $str_Message, $str_UserNick = null, $str_Hostname = 'localhost')
    {

        // Check if proposed log level is acceptable, otherwise log entry is refused
        if (in_array($str_LogLevel, $this->logLevelsList)
            && array_search($str_LogLevel, $this->logLevelsList) <= array_search($this->logLevel, $this->logLevelsList)
        ) {
            $str_Timestamp = get_timestamp();
            $arr_InsertValues = array(
                array($str_Timestamp => 's'),
                array($str_LogLevel => 's'),
                array($str_Hostname => 's'),
                array($str_UserNick => 's'),
                array($this->entity => 's'),
                array($this->module => 's'),
                array($str_Message => 's')
            );

            $str_LogPrepared =
                'insert into ' . $this->logTableName .
                ' (
					logTimestamp,
					logLevel,
					hostname,
					userNick,
					entity,
					module,
					message
			) values (
					?,
					?,
					?,
					?,
					?,
					?,
					?
			)';

            $this->dbHandler->beginTransaction();
            $this->dbHandler->executePrepared($str_LogPrepared, $arr_InsertValues);
            $this->dbHandler->commit();

            return true;
        } else {
            return false;
        }


    }

}
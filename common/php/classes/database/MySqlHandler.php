<?php
/**
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Gawain\Classes\Database;

use Gawain\Abstracts\Database\DbHandler;

/** Manages MySQL connections using MySQL native driver
 *
 * Class MySqlHandler
 */
class MySqlHandler extends DbHandler
{

    /**
     * MySqlHandler constructor.
     */
    function __construct()
    {

        parent::__construct();

        try {

            $this->handler = new \mysqli($this->hostname, $this->username, $this->password, $this->schema);

            if ($this->handler->connect_error) {
                throw new \Exception('DB Connection Error');
            }
        } catch (\Exception $exception) {
            echo 'Cannot connect to selected DB: ' . $exception->getMessage() . "\n";
        }

        $this->handler->autocommit(false);

        // Additional lines to prevent wrong rendering of accented characters
        $this->handler->set_charset('utf8');
    }

    /** Execute prepared query and output resultset
     *
     * The input parameters will be formatted in the following way:
     * array([0] => array(variable1 => type1), [1] => array(variable2 => type2))
     *
     * @param string $str_Query
     * @param array  $arr_InputParameters
     *
     * @return object
     */
    public function executePrepared($str_Query, $arr_InputParameters)
    {

        /*
         The input parameters will be formatted in the following way:
         array([0] => array(variable1 => type1), [1] => array(variable2 => type2))
         */
        // Prepare the query
        $obj_Prepared = $this->handler->prepare($str_Query);

        if (!empty($arr_InputParameters)) {
            // Dynamic values binding
            $str_ParametersType = '';
            $arr_ParametersValue = array();

            $arr_InputParametersKey = array();
            $arr_InputParametersValue = array();

            foreach ($arr_InputParameters as $arr_InputParameter) {
                $arr_InputParametersKey[] = implode('', array_keys($arr_InputParameter));
                $arr_InputParametersValue[] = implode('', array_values($arr_InputParameter));
            }

            for ($int_ParametersCounter = 0; $int_ParametersCounter < sizeof($arr_InputParametersKey); $int_ParametersCounter++) {
                $arr_ParametersValue[] = &$arr_InputParametersKey[$int_ParametersCounter];
                $str_ParametersType .= $arr_InputParametersValue[$int_ParametersCounter];
            }

            $arr_BindArgument = array_merge(array($str_ParametersType), $arr_ParametersValue);

            call_user_func_array(array($obj_Prepared, 'bind_param'), $arr_BindArgument);
        }

        $obj_Prepared->execute();

        // Output
        $obj_Resultset = $obj_Prepared->get_result();

        if (is_object($obj_Resultset)) {
            $obj_Result = $obj_Resultset->fetch_all(MYSQLI_ASSOC);
            $obj_Prepared->free_result();
            $obj_Prepared->close();
        } else {
            $obj_Result = null;
        }

        return $obj_Result;
    }

    /** Starts a transaction
     *
     */
    public function beginTransaction()
    {
        $this->handler->query('begin transaction');
    }

    /** Commits a transaction
     *
     */
    public function commit()
    {
        $this->handler->query('commit');
    }

    /** Rollback a transaction
     *
     */
    public function rollback()
    {
        $this->handler->query('rollback');
    }

    /** Destructor
     *
     * Closes DB Connection
     */
    function __destruct()
    {
        $this->handler->close();
    }
}

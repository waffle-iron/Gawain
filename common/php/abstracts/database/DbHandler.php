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

namespace Gawain\Abstracts\Database;

require_once(PHP_CLASSES_DIR . 'misc/Options.php');

use Gawain\Classes\Misc\Options;

/**
 * Class DbHandler
 *
 * Generic class to handle DB connections
 */
abstract class DbHandler
{

    /**
     * @var string Hostname
     */
    protected $hostname;


    /**
     * @var string DB Username
     */
    protected $username;


    /**
     * @var string DB User Password
     */
    protected $password;


    /**
     * @var string DB Default schema
     */
    protected $schema;


    /**
     * @var \stdClass Connection Handler
     */
    protected $handler;


    /**
     * @var Options Options
     */
    protected $options;


    /**
     * DbHandler constructor.
     */
    protected function __construct()
    {
        $this->options = new Options();

        $this->hostname = $this->options->get('db_host');
        $this->username = $this->options->get('db_user');
        $this->password = $this->options->get('db_pwd');
        $this->schema = $this->options->get('db_schema');
    }


    /** Execute prepared query and output resultset
     *
     * @param $str_Query
     * @param $arr_InputParameters
     *
     * @return mixed
     */
    abstract public function executePrepared($str_Query, $arr_InputParameters);


    /** Starts a transaction
     *
     * @return mixed
     */
    abstract public function beginTransaction();


    /** Commits a transaction
     *
     * @return mixed
     */
    abstract public function commit();


    /** Rollbacks a transaction
     *
     * @return mixed
     */
    abstract public function rollback();


    /**
     * Class destructor
     */
    abstract protected function __destruct();

}

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

require_once(PHP_CLASSES_DIR . 'misc/Options.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

/**
 * Class GawainObject
 */
abstract class GawainObject
{
    /** Database Hanlder
     * @var dbHandler
     */
    protected $dbHandler;

    /** Options
     * @var Options
     */
    protected $options;

    /** Internal logger
     * @var Logger
     */
    protected $logger;

    /**
     * GawainObject constructor.
     */
    public function __construct()
    {
        $this->options = new Options();
        $this->dbHandler = db_autodefine($this->options);

        $this->logger = new Logger();
        $this->logger->log('DEBUG', 'Created logger object');
    }

    /** Magic method to be called at every method call
     *
     * @param $str_MethodName
     * @param $arr_Arguments
     *
     * @return mixed
     */
    protected function __call($str_MethodName, $arr_Arguments)
    {
        if (method_exists($this, $str_MethodName)) {
            $this->logger->log('DEBUG',
                               'Calling method ' . $str_MethodName . ' with parameters: ' . json_encode($arr_Arguments));
        } else {
            $this->logger->log('NOTICE', 'Calling non existing or dynamic method ' . $str_MethodName . ' with
            parameters: ' . json_encode($arr_Arguments));
        }

        return call_user_func_array(array($this, $str_MethodName), $arr_Arguments);
    }
}

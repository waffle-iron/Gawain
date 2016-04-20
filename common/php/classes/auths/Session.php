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
 * Class Session
 */
class Session
{

    /** Session ID, stored internally
     * @var null
     */
    private $sessionID;

    /** Internal domain ID
     * @var string
     */
    private $domainID;

    /** Internal DB handler
     * @var dbHandler
     */
    private $dbHandler;

    /** Internal Options
     * @var Options
     */
    private $options;

    /**
     * Session constructor.
     *
     * @param null $str_SessionID
     *
     * @throws Exception
     */
    public function __construct($str_SessionID = null)
    {
        $this->options = new Options();
        $this->dbHandler = db_autodefine($this->options);

        // Check if given session ID is valid
        if (!is_null($str_SessionID)) {
            if ($this->isValidSession($str_SessionID)) {
                $this->sessionID = $str_SessionID;
            } else {
                throw new Exception('Invalid session');
            }
        }
    }

    /** Checks if the session is valid
     *
     * @param $str_SessionID
     *
     * @return bool
     */
    public function isValidSession($str_SessionID)
    {
        $str_Query = '
				select
					count(*) as counter
				from sessions
				where sessionID = ?';

        $obj_Resultset = $this->dbHandler->executePrepared($str_Query, array(
            array($str_SessionID => 's')
        ));

        if ($obj_Resultset[0]['counter'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /** Creates a new session for the given user
     *
     * @param $str_UserNick
     * @param $str_HostName
     *
     * @return bool
     */
    public function createSession($str_UserNick, $str_HostName)
    {
        $this->sessionID = $this->generateSessionID();

        return $this->writeSession($this->sessionID, $str_UserNick, null, $str_HostName);
    }

    /** Generates a valid, hopefully unbreakable, session ID
     *
     * @return string
     */
    private function generateSessionID()
    {
        $str_SessionID = sha1(sha1(microtime() . date('z') . uniqid(null, true)));

        return $str_SessionID;
    }

    /** Writes the current session data into DB
     *
     * @param string  $str_SessionID
     * @param string  $str_UserNick
     * @param integer $int_CustomerID
     * @param string  $str_Host
     *
     * @return boolean
     */
    private function writeSession($str_SessionID, $str_UserNick, $int_CustomerID = null, $str_Host = 'localhost')
    {
        $str_InsertQuery = '
			insert into sessions (
				sessionID,
				userNick,
				customerID,
				sessionStartDate,
				sessionHostName
			) values (
				?,
				?,
				' . (is_null($int_CustomerID) ? 'NULL' : '?') . ',
				sysdate(),
				?
			)';

        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_InsertQuery, (is_null($int_CustomerID) ? array(
            array($str_SessionID => 's'),
            array($str_UserNick => 's'),
            array($str_Host => 's')
        ) : array(
            array($str_SessionID => 's'),
            array($str_UserNick => 's'),
            array($int_CustomerID => 'i'),
            array($str_Host => 's')
        )));
        $this->dbHandler->commit();

        return true;
    }

    /** Extends the session expiration by a given amount
     *  If no amount is given, the default one is used
     *
     * @param null $int_ExtendAmount
     */
    public function extendSession($int_ExtendAmount = null)
    {
        //TODO: add session expiration date in DB
    }

    /** Deletes a session
     *
     * @return bool
     */
    public function deleteSession()
    {
        $str_RemoveQuery = '
			delete from sessions
				where sessionID = ?';

        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_RemoveQuery, array(
            array($this->sessionID => 's')
        ));
        $this->dbHandler->commit();

        return true;
    }

    /** Sets the domain for the given session
     *
     * @param $int_DomainID
     *
     * @return bool
     */
    public function setDomain($int_DomainID)
    {
        $str_SetCustomerQuery = '
			update sessions
			set customerID = ?
				where sessionID = ?';

        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_SetCustomerQuery, array(
            array($int_DomainID => 'i'),
            array($this->sessionID => 's')
        ));
        $this->dbHandler->commit();

        return true;
    }

    /** Gets the current domain
     *
     * @return mixed
     * @throws Exception
     */
    public function getDomain()
    {
        if (is_null($this->domainID)) {
            $str_CustomerPrepQuery = 'select
				customerID
			from sessions
			where sessionID = ?';

            $arr_Result = $this->dbHandler->executePrepared($str_CustomerPrepQuery, array(
                array($this->sessionID => 's')
            ));

            if (count($arr_Result) == 1) {
                $this->domainID = $arr_Result[0]['customerID'];
            } else {
                throw new Exception('Non unique session');
            }
        }

        return $this->domainID;
    }

    /** Gets the user nickname associated to the given session
     * @return mixed
     * @throws Exception
     */
    public function getUserNick()
    {
        $str_UserQuery = '
			select
				sessions.userNick
			from sessions
			where sessions.sessionID = ?';

        $arr_Result = $this->dbHandler->executePrepared($str_UserQuery, array(
            array($this->sessionID => 's')
        ));

        if (count($arr_Result) == 1) {
            return $arr_Result[0]['userNick'];
        } else {
            throw new Exception('Non unique session');
        }
    }

}

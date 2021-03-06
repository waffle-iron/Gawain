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
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_FUNCTIONS_DIR . 'autodefiners.php');

/**
 * Class UserAuthManager
 */
class UserAuthManager
{

    // TODO: refactor the whole class to include entities (user, session, etc...)

    /** IP address
     * @var null|string
     */
    private $hostName;


    /** DB handler
     * @var dbHandler
     */
    private $dbHandler;


    /** Options
     * @var Options
     */
    private $options;


    /** Constructor
     *
     * @param string $str_Host
     */
    public function __construct($str_Host = null)
    {
        $this->hostName = $str_Host;
        $this->options = new Options();
        $this->dbHandler = db_autodefine($this->options);
    }


    /** Authenticates with the given password hash
     *
     * @param string $str_UserNick
     * @param string $str_PasswordHash
     *
     * @throws Exception
     * @return array
     */
    public function authenticate($str_UserNick, $str_PasswordHash)
    {
        $str_CheckQuery = '
			select
				userPassword,
				userIsActive
			from users
			where userNick = ?';

        $obj_Resultset = $this->dbHandler->executePrepared($str_CheckQuery, array(
            array($str_UserNick => 's')
        ));

        if (count($obj_Resultset) == 0) {
            throw new Exception('User does not exist');

        } elseif ($str_UserNick === null) {
            throw new Exception('User not defined');

        } elseif (count($obj_Resultset) > 1) {
            throw new Exception('Multiple user returned');

        } elseif ($obj_Resultset[0]['userIsActive'] = 0) {
            throw new Exception('User is not active');

        } elseif (strtoupper($obj_Resultset[0]['userPassword']) != strtoupper($str_PasswordHash)) {
            throw new Exception('Wrong username or password');

        } else {
            $str_SessionID = $this->generateSessionID();

            $this->writeSession($str_SessionID, $str_UserNick, null, $this->hostName);

            return array(
                'sessionID' => $str_SessionID,
                'enabledCustomers' => $this->getEnabledCustomers($str_UserNick, 'html')
            );
        }
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

    /** Get the list of enabled customers for the given user in the selected format
     * The format can be:
     *    - 'raw' to output the result as PHP array
     *    - 'json' to output the result as JSON string
     *    - 'html' to output the result as HTML select options
     *
     * @param string $str_UserNick
     * @param string $str_Format
     *
     * @return array
     */
    private function getEnabledCustomers($str_UserNick, $str_Format = 'html')
    {
        $str_EnabledCustomersQuery = '
			select
				enabled.authorizedCustomerID as ID,
				customers.customerName as Name
			from user_enabled_customers enabled
			inner join customers
				on enabled.authorizedCustomerID = customers.customerID
			where enabled.userNick = ?';

        $obj_Resultset = $this->dbHandler->executePrepared($str_EnabledCustomersQuery, array(
            array($str_UserNick => 's')
        ));

        $mix_Return = null;


        switch ($str_Format) {
            case 'json':
                $mix_Return = json_encode($obj_Resultset);
                break;

            case 'raw':
                $mix_Return = $obj_Resultset;
                break;

            case 'html':
                $str_Template = '<option value="%ID%">%NAME%</option>';
                $arr_Output = array();

                foreach ($obj_Resultset as $arr_Result) {
                    $str_Output = str_replace('%ID%', $arr_Result['ID'], $str_Template);
                    $str_Output = str_replace('%NAME%', $arr_Result['Name'], $str_Output);
                    $arr_Output[] = $str_Output;
                }

                $mix_Return = '<hr>' . '<form class="form-horizontal" id="gawain-domain-selection-form" action="/gawain/rest-api/authentication/login" method="POST">' . '<div class="form-group">' . '<label for="gawain-domain-selector" class="col-md-2 control-label">Domain</label>' . '<div class="col-md-10">' . '<select class="form-control" id="gawain-domain-selector" name="selectedCustomer">' . implode(PHP_EOL,
                                                                                                                                                                                                                                                                                                                                                                                                                      $arr_Output) . '</select>' . '</div>' . '</div>' . '<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary" id="gawain-domain-selection-button">Login</button>
									</div>
								</div>' . '</form>';
                break;
        }

        return $mix_Return;
    }

    /** Checks if the current user has permission for the current module
     *
     * @param string $str_Module
     * @param bool   $bool_SendHeader
     *
     * @return bool
     */
    public function checkPermissions($str_Module, $bool_SendHeader = false)
    {

        $bool_Result = true;

        if (isset($_COOKIE['GawainSessionID'])) {
            $str_SessionID = $_COOKIE['GawainSessionID'];

            // Checks if the session ID is valid
            if ($this->isAuthenticated($str_SessionID)) {

                // Checks if the user has the correct grants
                if (!$this->hasGrants($str_SessionID, $str_Module)) {

                    if ($bool_SendHeader) {
                        header('Location: ' . LOGOUT_LANDING_PAGE, true);
                        header('Gawain-Response: Unauthorized', 0, 401);
                        exit;
                    }
                    $bool_Result = false;
                }

            } else {
                if ($bool_SendHeader) {
                    header('Location: ' . LOGOUT_LANDING_PAGE, true);
                    header('Gawain-Response: Unauthorized', 0, 401);
                    exit;
                }
                $bool_Result = false;
            }
        } else {
            if ($bool_SendHeader) {
                header('Location: ' . LOGOUT_LANDING_PAGE, true);
                header('Gawain-Response: Unauthorized', 0, 401);
                exit;
            }
            $bool_Result = false;
        }

        return $bool_Result;

    }

    /** Checks if the given user is authenticated
     *
     * @param string $str_SessionID
     *
     * @return boolean
     */
    public function isAuthenticated($str_SessionID)
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

    /** Checks if the user related to the current Session ID has grants to perform an action
     *
     * @param string  $str_SessionID
     * @param string  $str_ModuleCode
     * @param integer $int_RequiredPermission
     *
     * @throws Exception
     * @return boolean
     */
    public function hasGrants($str_SessionID, $str_ModuleCode, $int_RequiredPermission = 0)
    {
        $str_Query = '
				select
					auth.writePermission
				from modules
				inner join modules_auths auth
					on modules.moduleCode = auth.moduleCode
				inner join user_groups groups
					on groups.groupCode = auth.groupCode
				inner join user_enabled_customers enabled
					on enabled.groupCode = auth.groupCode
					and enabled.authorizedCustomerID = auth.customerID
				inner join users
					on enabled.userNick = users.userNick
				inner join sessions
					on sessions.userNick = users.userNick
					and sessions.customerID = enabled.authorizedCustomerID
				where modules.moduleCode = ?
					and sessions.sessionID = ?';


        $arr_Resultset = $this->dbHandler->executePrepared($str_Query, array(
            array($str_ModuleCode => 's'),
            array($str_SessionID => 's')
        ));

        if ($arr_Resultset !== null && count($arr_Resultset) == 1) {
            if ($arr_Resultset[0]['writePermission'] >= $int_RequiredPermission) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception('Invalid request');
        }
    }

    /** Log the current user in and selects the current customer
     *
     * @param string  $str_SessionID
     * @param integer $int_SelectedCustomer
     *
     * @return boolean
     */
    public function login($str_SessionID, $int_SelectedCustomer)
    {
        $str_CustomerCheckQuery = '
			select
				sessions.sessionID,
				sessions.userNick
			from sessions
			inner join user_enabled_customers enabled
				on sessions.userNick = enabled.userNick
			where sessions.sessionID = ?
				and enabled.authorizedCustomerID = ?';

        $obj_Resultset = $this->dbHandler->executePrepared($str_CustomerCheckQuery, array(
            array($str_SessionID => 's'),
            array($int_SelectedCustomer => 'i')
        ));

        if (count($obj_Resultset) == 1) {
            $this->setSessionCustomer($str_SessionID, $int_SelectedCustomer);

            return true;

        } else {
            return false;
        }
    }

    /** Sets the customerID for the given session
     *
     * @param string  $str_SessionID
     * @param integer $int_CustomerID
     *
     * @return boolean
     */
    private function setSessionCustomer($str_SessionID, $int_CustomerID)
    {
        $str_SetCustomerQuery = '
			update sessions
			set customerID = ?
				where sessionID = ?';

        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_SetCustomerQuery, array(
            array($int_CustomerID => 'i'),
            array($str_SessionID => 's')
        ));
        $this->dbHandler->commit();

        return true;
    }

    /** Logs out from the current session
     *
     * @param string $str_SessionID
     *
     * @return boolean
     */
    public function logout($str_SessionID)
    {
        $this->removeSession($str_SessionID);

        return true;
    }

    /** Removes the given session from DB
     *
     * @param string $str_SessionID
     *
     * @return boolean
     */
    private function removeSession($str_SessionID)
    {
        $str_RemoveQuery = '
			delete from sessions
				where sessionID = ?';

        $this->dbHandler->beginTransaction();
        $this->dbHandler->executePrepared($str_RemoveQuery, array(
            array($str_SessionID => 's')
        ));
        $this->dbHandler->commit();

        return true;
    }

    /** Geta the current user nick logged with the given SessionID
     *
     * @param string $str_SessionID
     *
     * @return string
     * @throws Exception
     */
    public function getCurrentUserNick($str_SessionID)
    {
        $str_UserQuery = '
			select
				sessions.userNick
			from sessions
			where sessions.sessionID = ?';

        $obj_Resultset = $this->dbHandler->executePrepared($str_UserQuery, array(
            array($str_SessionID => 's')
        ));

        if (count($obj_Resultset) == 1) {
            return $obj_Resultset[0]['userNick'];
        } else {
            throw new Exception('Non-unique session');
        }

    }

}

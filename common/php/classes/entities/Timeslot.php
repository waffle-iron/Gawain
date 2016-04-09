<?php

require_once(PHP_ABSTRACTS_DIR . 'entities/Entity.php');
require_once(PHP_FUNCTIONS_DIR . 'string_functions.php');
require_once(PHP_CLASSES_DIR . 'entities/Activity.php');
require_once(PHP_CLASSES_DIR . 'auths/UserAuthManager.php');



/**
 * Class Timeslot
 */
class Timeslot extends Entity
{

    /** Internal Auth Manager
     *
     * @var UserAuthManager
     */
    private $authManager;


    /** Child constructor
     *
     * @param string $str_SessionID
     */
    public function __construct($str_SessionID)
    {

        // Sets entity reference code
        $this->type = 'timeslot';

        // Creates the User Auth Manager
        $this->authManager = new UserAuthManager();

        // Call parent constructor
        parent::__construct($str_SessionID);
    }


    /** Groups extracted timeslots by date
     *
     * @param array $arr_Resultset
     * @param bool  $bool_InversedOrder
     *
     * @return array
     */
    public static function groupTimeslotsByDate($arr_Resultset, $bool_InversedOrder = true)
    {

        $arr_DateGroupedTimeslots = array();

        foreach ($arr_Resultset as $int_TimeslotID => $arr_Timeslot) {
            $arr_DateGroupedTimeslots[$arr_Timeslot['timeslotReferenceDate']][$int_TimeslotID] = $arr_Timeslot;
        }

        if ($bool_InversedOrder) {
            krsort($arr_DateGroupedTimeslots);
        } else {
            ksort($arr_DateGroupedTimeslots);
        }


        return $arr_DateGroupedTimeslots;

    }


    /** Groups extracted timeslots by Activity and Task
     *
     * @param $arr_Resultset
     *
     * @return array
     */
    public static function groupTimeslotsByActivity($arr_Resultset)
    {

        $arr_ActivityGroupedTimeslots = array();

        foreach ($arr_Resultset as $int_TimeslotID => $arr_Timeslot) {
            if (!isset($arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['total'])) {
                $arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['total'] = 0;
            }

            if (!isset($arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['details'][$arr_Timeslot['timeslotTaskID']])) {
                $arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['details'][$arr_Timeslot['timeslotTaskID']] =
                    0;
            }

            $arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['total'] += $arr_Timeslot['timeslotDuration'];
            $arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotActivityID']]['details'][$arr_Timeslot['timeslotTaskID']] += $arr_Timeslot['timeslotDuration'];
        }

        return $arr_ActivityGroupedTimeslots;

    }


    /** Groups extracted timeslots by Activity and Task
     *
     * @param $arr_Resultset
     *
     * @return array
     */
    public static function groupTimeslotsByUser($arr_Resultset)
    {

        $arr_UserGroupedTimeslots = array();

        foreach ($arr_Resultset as $int_TimeslotID => $arr_Timeslot) {
            if (!isset($arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['total'])) {
                $arr_UserGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['total'] = 0;
            }

            if (!isset($arr_ActivityGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['details'][$arr_Timeslot['timeslotTaskID']])) {
                $arr_UserGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['details'][$arr_Timeslot['timeslotTaskID']] =
                    0;
            }

            $arr_UserGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['total'] += $arr_Timeslot['timeslotDuration'];
            $arr_UserGroupedTimeslots[$arr_Timeslot['timeslotUserNick']]['details'][$arr_Timeslot['timeslotTaskID']] += $arr_Timeslot['timeslotDuration'];
        }

        return $arr_UserGroupedTimeslots;

    }


    /** Helper method that retrieves all the entries linked to current user
     *
     * @param string $mix_Limits
     * @param null   $int_ActivityID
     * @param null   $int_TaskID
     *
     * @return array
     * @throws Exception
     */
    public function getCurrentUserEntries($mix_Limits = 'this_month', $int_ActivityID = null, $int_TaskID = null)
    {

        // First get current user nick
        $str_CurrentUser = $this->authManager->getCurrentUserNick($this->sessionID);

        return $this->getUsersEntries(array($str_CurrentUser), $mix_Limits, $int_ActivityID, $int_TaskID);

    }


    /** Helper method that retrieves all the entries for the given users. If no user is specified, all user entries
     * are retrieved.
     *
     * @param array  $arr_Users
     * @param string $mix_Limits
     * @param null   $int_ActivityID
     * @param null   $int_TaskID
     *
     * @return array
     * @throws Exception
     */
    public function getUsersEntries($arr_Users, $mix_Limits = 'this_month', $int_ActivityID = null, $int_TaskID = null)
    {

        $arr_Wheres = array();

        // If no user is specified, all users are retrieved
        if (!is_null($arr_Users)) {
            $arr_Wheres['timeslotUserNick'] = array(
                'operator'  => 'in',
                'arguments' => $arr_Users
            );
        }

        if (!is_array($mix_Limits)) {

            $date_Today = new DateTime();

            // If the limit parameter is a string, interpret the string and add condition
            switch ($mix_Limits) {
                case 'this_day':
                    $str_Today = $date_Today->format('Y-m-d');
                    $arr_Wheres['timeslotReferenceDate'] = array(
                        'operator'  => '=',
                        'arguments' => array(
                            $str_Today
                        )
                    );
                    break;

                case 'this_week':
                    $str_Limit = strftime('%Y-%m-%d', strtotime('this week', time()));
                    $arr_Wheres['timeslotReferenceDate'] = array(
                        'operator'  => '>=',
                        'arguments' => array(
                            $str_Limit
                        )
                    );
                    break;

                case 'this_month':
                    $str_Limit = $date_Today->format('Y-m-01');
                    $arr_Wheres['timeslotReferenceDate'] = array(
                        'operator'  => '>=',
                        'arguments' => array(
                            $str_Limit
                        )
                    );
                    break;

                case 'this_year':
                    $str_Limit = $date_Today->format('Y-01-01');
                    $arr_Wheres['timeslotReferenceDate'] = array(
                        'operator'  => '>=',
                        'arguments' => array(
                            $str_Limit
                        )
                    );
                    break;

                case 'all':
                    break;

                default:
                    $str_Limit = $date_Today->format('Y-m-01');
                    $arr_Wheres['timeslotReferenceDate'] = array(
                        'operator'  => '>=',
                        'arguments' => array(
                            $str_Limit
                        )
                    );
                    break;
            }

        } else {

            // If the limit parameter is an array, get the 'from' and 'to' elements
            if (isset($mix_Limits['from']) && !is_null($mix_Limits['from'])) {
                $arr_Wheres['timeslotReferenceDate'] = array(
                    'operator'  => '>=',
                    'arguments' => array(
                        $mix_Limits['from']
                    )
                );
            }

            if (isset($mix_Limits['to']) && !is_null($mix_Limits['to'])) {
                $arr_Wheres['timeslotReferenceDate'] = array(
                    'operator'  => '<=',
                    'arguments' => array(
                        $mix_Limits['to']
                    )
                );
            }

        }


        // Add condition on activity ID and task ID, if present
        if (!is_null($int_ActivityID)) {
            $arr_Wheres['timeslotActivityID'] = array(
                'operator'  => '=',
                'arguments' => array(
                    $int_ActivityID
                )
            );
        }

        if (!is_null($int_TaskID)) {
            $arr_Wheres['timeslotTaskID'] = array(
                'operator'  => '=',
                'arguments' => array(
                    $int_TaskID
                )
            );
        }


        // Execute the query and get entries
        return $this->read($arr_Wheres);

    }

}

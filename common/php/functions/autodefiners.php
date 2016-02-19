<?php

require_once(__DIR__ . '/../constants/global_defines.php');


/** Automatically selects the right DB handler from options
 *
 * @param Options $obj_OptionHandler
 *
 * @return dbHandler
 */
function db_autodefine($obj_OptionHandler)
{

    $str_DbType = $obj_OptionHandler->get('db_type');
    $obj_Return = null;

    switch ($str_DbType) {
        case 'MySQL':
            require_once(PHP_CLASSES_DIR . 'database/MySqlHandler.php');
            $obj_Return = new MySqlHandler;
            break;

        default:
            $obj_Return = null;
            break;
    }

    return $obj_Return;
}
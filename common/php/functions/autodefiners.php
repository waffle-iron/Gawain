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

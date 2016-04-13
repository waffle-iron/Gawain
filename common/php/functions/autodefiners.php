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

namespace Gawain\Functions\Autodefiners;

use Gawain\Classes\Database\MySqlHandler;
use Gawain\Functions\StringFunctions;

/** Automatically selects the right DB handler from options
 *
 * @param \Gawain\Classes\Misc\Options $obj_OptionHandler
 *
 * @return \Gawain\Abstracts\Database\DbHandler
 */
function db_autodefine($obj_OptionHandler)
{

    $str_DbType = $obj_OptionHandler->get('db_type');
    $obj_Return = null;

    switch ($str_DbType) {
        case 'MySQL':
            require_once(PHP_CLASSES_DIR . 'database/MySqlHandler.php');
            $obj_Return = new MySqlHandler();
            break;

        default:
            $obj_Return = null;
            break;
    }

    return $obj_Return;
}

/** PSR-0 class loader for Gawain
 *  Automatically parses PHP files in common/php dir and loads the requested class on demand
 *
 * @param $str_ClassName
 *
 * @throws \Exception
 */
function class_loader($str_ClassName)
{
    $obj_Rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(PHP_DIR));

    $arr_Files = array();

    foreach ($obj_Rii as $obj_File) {
        if (!$obj_File->isDir()) {
            $arr_PathInfo = pathinfo($obj_File->getPathname());

            if ($arr_PathInfo['extension'] == 'php') {
                $arr_Files[$arr_PathInfo['filename']] = $obj_File->getPathname();
            }
        }
    }

    if (in_array($str_ClassName, array_keys($arr_Files))) {
        require $arr_Files[$str_ClassName];
    } else {
        throw new \Exception('CLass does not exist');
    }
}

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

/** Generates a random string based on microtime hash
 *
 * @return string
 */
function generate_random_string()
{
    return rtrim(base64_encode(md5(microtime())), '=') . rand();
}


/** Generates a unique timestamp based on current microtime
 *
 * @return string
 */
function get_timestamp()
{

    $dbl_UnixTimestamp = microtime(true);
    $dbl_Timestamp = floor($dbl_UnixTimestamp);

    $dbl_Milliseconds = round(($dbl_UnixTimestamp - $dbl_Timestamp) * 1000000);

    $str_Timestamp = date(preg_replace('`(?<!\\\\)u`', str_pad($dbl_Milliseconds, 6, '0'), 'Y-m-d H:i:s.u'),
                          $dbl_Timestamp);

    return $str_Timestamp;
}


/** Converts a given array into XML string
 *
 * @param array  $arr_Array    The input array
 * @param string $str_StartTag string The root XML element
 *
 * @return string
 */
function array2xml($arr_Array, $str_StartTag)
{

    $obj_XML = new SimpleXMLElement('<?xml version="1.0"?><' . $str_StartTag . '></' . $str_StartTag . '>');

    // Recursive internal function, see below
    _array_to_xml($arr_Array, $obj_XML);

    // Outputs the XML string
    return preg_replace('/[\n\r]/', '', $obj_XML->asXML());

}


/** Recursive function to convert array into XML. Internal use only!!
 *
 * @param array            $arr_Data
 * @param SimpleXMLElement $xml_Data
 */
function _array_to_xml($arr_Data, &$xml_Data)
{

    foreach ($arr_Data as $str_Key => $mix_Value) {

        if (is_array($mix_Value)) {

            $arr_ValueKeys = array_keys($mix_Value);
            if (is_numeric($arr_ValueKeys[0])) {

                foreach ($mix_Value as $mix_ValueInner) {
                    $xml_Subnode = $xml_Data->addChild($str_Key);
                    _array_to_xml($mix_ValueInner, $xml_Subnode);
                }

            } else {
                $xml_Subnode = $xml_Data->addChild($str_Key);
                _array_to_xml($mix_Value, $xml_Subnode);
            }

        } else {

            $xml_Data->addChild($str_Key, htmlspecialchars($mix_Value));

        }

    }

}

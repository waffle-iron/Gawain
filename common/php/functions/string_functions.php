<?php

/** Generates a random string based on microtime hash
 * 
 * @return string
 */
function generate_random_string() {
	return rtrim(base64_encode(md5(microtime())), '=');
}




/** Generates a unique timestamp based on current microtime
 * 
 * @return string
 */
function get_timestamp() {
	
	$dbl_UnixTimestamp = microtime(TRUE);
	$dbl_Timestamp = floor($dbl_UnixTimestamp);
	
	$dbl_Milliseconds = round(($dbl_UnixTimestamp - $dbl_Timestamp) * 1000000);
	
	$str_Timestamp = date(preg_replace('`(?<!\\\\)u`', str_pad($dbl_Milliseconds, 6, '0'), 'Y-m-d H:i:s.u'), $dbl_Timestamp);

	return $str_Timestamp;
}


/** Converts a given array into XML string
 *
 * @param array $arr_Array The input array
 * @param string $str_StartTag string The root XML element
 * @return string
 */
function array2xml($arr_Array, $str_StartTag) {

	$obj_XML = new SimpleXMLElement('<?xml version="1.0"?><' . $str_StartTag . '></' . $str_StartTag . '>');

	// Recursive internal function, see below
	_array_to_xml($arr_Array, $obj_XML);

	// Outputs the XML string
	return str_replace(PHP_EOL, '', $obj_XML->asXML());

}



/** Recursive function to convert array into XML. Internal use only!!
 *
 * @param array $arr_Data
 * @param SimpleXMLElement $xml_Data
 */
function _array_to_xml($arr_Data, &$xml_Data) {

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
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
 * @return string
 */
function array2xml($arr_Array) {

	$obj_XML = new SimpleXMLElement('<?xml version="1.0"?>');

	// Recursive internal function, see below
	array_to_xml($arr_Array, $obj_XML);

	// Outputs the XML string
	return $obj_XML->asXML();


	// Function defination to convert array to xml
	function array_to_xml($data, &$xml_data) {

		foreach( $data as $key => $value ) {

			if( is_array($value) ) {

				if( is_numeric($key) ){
					$key = 'item'.$key; //dealing with <0/>..<n/> issues
				}

				$subnode = $xml_data->addChild($key);
				array_to_xml($value, $subnode);

			} else {
				$xml_data->addChild("$key",htmlspecialchars("$value"));
			}

		}

	}

}
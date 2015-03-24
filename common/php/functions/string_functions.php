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
	
	$str_Timestamp = date(preg_replace('`(?<!\\\\)u`', $dbl_Milliseconds, 'Y-m-d H:i:s.u'), $dbl_Timestamp);

	return $str_Timestamp;
}

?>
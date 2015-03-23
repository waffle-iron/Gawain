<?php

/** Generates a random string based on microtime hash
 * 
 * @return string
 */
function generate_random_string() {
	return rtrim(base64_encode(md5(microtime())), '=');
}




function get_timestamp() {
	$obj_DateTime = new DateTime();
	$str_Timestamp = $obj_DateTime->format('Y-m-d H:i:s.u');
	return $str_Timestamp;
}

?>
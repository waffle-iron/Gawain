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




/** Parses Where array to compose a well formed Where condition
 * 
 * @param array $arr_Wheres
 * @param array $arr_EntityFieldsData
 * @param string $str_TableName
 * 
 * @return array 
 */
function parse_where_array($arr_Wheres, $arr_EntityFieldsData, $str_TableName) {
	if ($arr_Wheres !== NULL) {
		$arr_WhereFields = array();
		$arr_Parameters = array();
			
		foreach ($arr_Wheres as $str_WhereColumn => $arr_WhereCondition) {
			$str_WhereCondition = $str_TableName . '.' . 
				$str_WhereColumn . ' ' . $arr_WhereCondition['operator'] . ' ';
	
			// Currently the array arguments feature is used only in 'IN' conditions.
			// TODO: add support to more clauses that uses multiple arguments
	
			switch (strtolower($arr_WhereCondition['operator'])) {
				case 'in':
					$str_WhereCondition .= '(' . implode(', ', array_fill(1, count($arr_WhereCondition['arguments']), '?')) . ')';
					break;
				default:
					$str_WhereCondition .= implode(', ', array_fill(1, count($arr_WhereCondition['arguments']), '?'));
					break;
			}
	
			foreach ($arr_WhereCondition['arguments'] as $str_Argument) {
				$arr_Parameters[] = array($str_Argument => $arr_EntityFieldsData[$str_WhereColumn]['fieldType'] == 'NUM' ? 'i' : 's');
			}
	
			$arr_WhereFields[] = $str_WhereCondition;
		}
			
		$str_QueryString = ' where ' . implode(' and ', $arr_WhereFields);
	} else {
		$arr_Parameters = NULL;
		$str_QueryString = NULL;
	}
	
	$arr_Output = array(
			'query'			=>	$str_QueryString,
			'parameters'	=>	$arr_Parameters
	);
	
	return($arr_Output);
}
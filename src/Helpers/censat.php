<?php

if (! function_exists('censat')) {
	function censat($options=false){
		return new \Ajtarragona\Censat\Models\CensatClient($options);
	}
}

if (! function_exists('isJson')) {
	function isJson($string) {
	 	try{
			$ret=json_decode($string);
			if(!is_array($ret) && !is_object($ret)) return false; //es un tipo simple
			 
			return (json_last_error() == JSON_ERROR_NONE);
		}catch(Exception $e){
			return false;
		}
	}
}


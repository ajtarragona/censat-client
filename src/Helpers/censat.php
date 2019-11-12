<?php

if (! function_exists('censat')) {
	function censat($options=false){
		return new \Ajtarragona\Censat\Models\CensatClient($options);
	}
}

if (! function_exists('isJson')) {
	function isJson($string) {
	 	try{
	 		json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}catch(Exception $e){
			return false;
		}
	}
}

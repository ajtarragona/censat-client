<?php


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

if (! function_exists('to_object')) {
	function to_object($array, $firstlevel=true) {
        if($firstlevel){
            $tmp=json_decode(json_encode($array), FALSE);
            foreach($array as $key=>$value){
                $tmp->{$key} =  $value;
            }
            return $tmp;

        }else{
            return json_decode(json_encode($array), FALSE);
        }
		
	}
}



if (! function_exists('to_array')) {
	function to_array($object) {
	 	return json_decode(json_encode($object), true);
	}
}

if (! function_exists('to_array_first')) {
	function to_array_first($object) {
        $ret=[];
        foreach(array_keys(get_object_vars($object)) as $var){
            $ret[$var]=$object->{$var};
        }
        return $ret;
	}
}


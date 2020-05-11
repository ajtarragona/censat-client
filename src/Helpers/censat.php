<?php

if (! function_exists('censat')) {
	function censat($options=false){
		return new \Ajtarragona\Censat\Models\CensatClient($options);
	}
}

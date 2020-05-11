<?php

return [
	
	'debug' => env('CENSAT_DEBUG',false),
	"api_url" => env('CENSAT_API_URL','https://temisto.ajtarragona.es/censat/api/v2/') ,
	"api_user" => env('CENSAT_API_USER','*') ,
	"api_password" => env('CENSAT_API_PASSWORD','*') ,
	"api_token" => env('CENSAT_API_TOKEN',null) , //si ponen token en el env, se usará este. Si no, se hara login cada vez.
	
	
];


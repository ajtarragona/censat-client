<?php

return [
	
	'censat' => [
		'driver' => 'mysql',
		'host' => env('CENSAT_DB_HOST', '*'),
		'port' => env('CENSAT_DB_PORT', '*'),
		'database' => env('CENSAT_DB_DATABASE', '*'),
		'username' => env('CENSAT_DB_USERNAME', '*'),
		'password' => env('CENSAT_DB_PASSWORD', ''),
		'unix_socket' => env('CENSAT_DB_SOCKET', ''),
		'charset' => 'utf8mb4',
		'collation' => 'utf8mb4_unicode_ci',
		'prefix' => '',
		'strict' => true,
		'engine' => null,
	]
	
];


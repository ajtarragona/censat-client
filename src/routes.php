<?php

Route::group(['prefix' => 'ajtarragona/censat','middleware' => ['web']	], function () {
	
	Route::get('/', 'Ajtarragona\Censat\Controllers\CensatTestController@test')->name('censat.test');
	Route::get('/imatge/{id}', 'Ajtarragona\Censat\Controllers\CensatImageController@show')->name('censat.image.show');
	Route::get('/map/{name}/{location}', 'Ajtarragona\Censat\Controllers\CensatMapController@show')->name('censat.map.show');
	
	
});


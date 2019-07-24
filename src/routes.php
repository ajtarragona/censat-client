<?php

Route::group(['prefix' => 'ajtarragona/censat','middleware' => ['web']	], function () {
	
	Route::get('/', 'Ajtarragona\Censat\Controllers\CensatTestController@test')->name('censat.test');

	
});


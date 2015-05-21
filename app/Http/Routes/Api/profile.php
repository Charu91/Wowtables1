<?php
Route::get('api/profile/{token}', 'Api\ProfileController@show');
Route::put('api/profile','Api\ProfileController@update');

Route::post('api/profile/preferred_area','Api\ProfileController@setPreferredAreas');
Route::get('api/profile/preferred_area/{token}','Api\ProfileController@getPreferredArea');
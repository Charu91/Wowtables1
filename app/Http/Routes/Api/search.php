<?php
Route::post('/search/vendors', 'Api\SearchController@find');
Route::get('/search/experience', 'Api\SearchController@searchExperience');

Route::get('api/search/restaurants/{matchString}', 'Api\SearchController@getMatchingRestaurantsName');
Route::get('api/search/detail/{type}/{id}', 'Api\SearchController@getResourceDetail');

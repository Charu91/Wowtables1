<?php
Route::post('/search/vendors', 'Api\SearchController@find');
Route::get('/search/experience', 'Api\SearchController@searchExperience');

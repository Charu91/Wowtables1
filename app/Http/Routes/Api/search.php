<?php
Route::post('/search/vendors', 'Api\SearchController@find');
Route::post('/search/experience', 'Api\SearchController@searchExperience');

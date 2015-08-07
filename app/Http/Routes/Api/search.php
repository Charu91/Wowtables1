<?php
Route::get('api/search/restaurants/{matchString}', 
									array(
										'uses' => 'Api\SearchController@getMatchingRestaurantsName',
										'as' => '',
										'middleware' => 'wow.api',
										'where' => array(),
										'domain' => env('WEBSITE_URL'),
									));
									
Route::get('api/search/detail/{type}/{id}', 
									array(
										'uses' => 'Api\SearchController@getResourceDetail',
										'as' => '',
										'middleware' => 'wow.api',
										'where' => array(),
										'domain' => env('WEBSITE_URL'),
										)
									);

Route::get('search/experience', 
								array(
									'uses' => 'Api\SearchController@searchExperience',
									'as' => '',
									'middleware' => 'wow.api',
									'where' => array(),
									'domain' => env('WEBSITE_URL'),
								));

Route::post('api/nearby', 
								array(
									'uses' => 'Api\SearchController@getNearbyResource',
									'as' => '',
									'middleware' => 'wow.api',
									'where' => array(),
									'domain' => env('WEBSITE_URL'),
								));
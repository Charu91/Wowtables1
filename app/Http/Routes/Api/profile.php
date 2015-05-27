<?php
Route::get('api/profile/{token}',
			array(
				'uses' => 'Api\ProfileController@show',
				'as' => '',
				'middleware' => 'wow.api',
				'where' => array(),
				'domain' => env('WEBSITE_URL'),
			));

Route::put('api/profile',
			array(
				'uses' => 'Api\ProfileController@update',
				'as' => '',
				'middleware' => 'wow.api',
				'where' => array(),
				'domain' => env('WEBSITE_URL'),
				));

Route::post('api/profile/preferred_area',
			array(
				'uses' => 'Api\ProfileController@setPreferredAreas',
				'as' => '',
				'middleware' => 'wow.api',
				'where' => array(),
				'domain' => env('WEBSITE_URL'),
			));
			
Route::get('api/profile/preferred_area/{token}',
			array(
				'uses' => 'Api\ProfileController@getPreferredArea',
				'as' => '',
				'middleware' => 'wow.api',
				'where' => array(),
				'domain' => env('WEBSITE_URL'),
			));
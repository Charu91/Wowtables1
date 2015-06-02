<?php

//Route::resource('alacarte', 'Api\ALaCarteController');

Route::get('alacarte/{id}',
				array(
					'uses' => 'Api\ALaCarteController@show',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));

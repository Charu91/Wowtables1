<?php

Route::resource('alacarte', 'Api\ALaCarteController');

Route::get('api/alacarte/{id}',['uses' => 'Api\ALaCarteController@show',
								'as' => '',
								'middleware' => 'wow.api',
								'where' => [],
    							'domain' => env('WEBSITE_URL'),
    							]);

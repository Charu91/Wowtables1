<?php

Route::get('api/collection',
							array(
						 				'uses' => 'Api\CollectionController@index',
										'as' => '',
										'middleware' => 'wow.api',
										'where' => array(),
										'domain' => env('WEBSITE_URL'),
								  ));

Route::get('api/collection/{cityID}/{tagID}',
											array(
								 					'uses' => 'Api\CollectionController@show',
													'as' => '',
													'middleware' => 'wow.api',
													'where' => array(),
													'domain' => env('WEBSITE_URL'),
												  ));
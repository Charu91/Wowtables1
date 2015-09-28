<?php
/**
 * Route definition for reservation
 */
 Route::get('api/reservation/location/{type}/{id}',
 				array(
					'uses' => 'Api\ReservationController@getLocation',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
 
 //Route::get('api/reservation/party_size/{type}/{id}','Api\ReservationController@getPartySize');
 
 Route::get('api/reservation/schedule/{type}/{typeID}/{typeLocationID?}/{day?}',
 				array(
					'uses' => 'Api\ReservationController@getSchedule',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
 
 Route::post('api/reservation/reserve',
 				array(
 					'uses' => 'Api\ReservationController@reserveTable',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
 
 Route::put('api/reservation/cancel',
 				array(
 					'uses' => 'Api\ReservationController@cancelReservation',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
 				
 
 Route::put('api/reservation/change',
 				array(
 					'uses' => 'Api\ReservationController@changeReservation',
					'as' => '',
					/*'middleware' => 'wow.api',*/
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
 
 Route::get('api/reservation/my_reservation/{access_token?}',
 				array(
 					'uses' => 'Api\ReservationController@reservationRecord',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
					'domain' => env('WEBSITE_URL'),
				));
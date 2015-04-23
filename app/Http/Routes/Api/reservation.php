<?php
/**
 * Route definition for reservation
 */
 Route::get('api/reservation/location/{type}/{id}','Api\ReservationController@getLocation');
 
 Route::get('api/reservation/party_size/{type}/{id}','Api\ReservationController@getPartySize');
 
 Route::get('api/reservation/schedule/{type}/{id}/{day?}','Api\ReservationController@getSchedule');
 
 Route::post('api/reservation/reserve','Api\ReservationController@reserveTable');

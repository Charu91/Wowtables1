<?php
/**
 * Route definition for reservation
 */
 Route::get('api/reservation/location/{type}/{id}','Api\ReservationController@getLocation');
 
 //Route::get('api/reservation/party_size/{type}/{id}','Api\ReservationController@getPartySize');
 
 Route::get('api/reservation/schedule/{type}/{typeID}/{typeLocationID?}/{day?}','Api\ReservationController@getSchedule');
 
 Route::post('api/reservation/reserve','Api\ReservationController@reserveTable');
 
 Route::put('api/reservation/cancel','Api\ReservationController@cancelReservation');
 
 Route::put('api/reservation/change','Api\ReservationController@changeReservation');
 
 Route::get('api/reservation/my_reservation/{access_token}','Api\ReservationController@reservationRecord');
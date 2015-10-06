<?php
//Get Reservations based on status
Route::get('conciergeapi/reservation/{statuses}/{location}', 'ConciergeApi\ReservationController@index');
//Get a particular reservation for edit
Route::get('conciergeapi/reservation/{id}/edit', 'ConciergeApi\ReservationController@edit');
//Get a particular reservation for display
Route::get('conciergeapi/reservation/{id}', 'ConciergeApi\ReservationController@show');
//Update a particular reservation
Route::put('conciergeapi/reservation/{id}', 'ConciergeApi\ReservationController@update');
//Send a push notification with reservation details
Route::post('conciergeapi/reservation/{id}/notification', 'ConciergeApi\ReservationController@push');

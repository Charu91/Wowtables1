<?php
//Get Reservations based on status
Route::get('conciergeapi/reservation/{statuses}/{location}', [
    'uses' => 'ConciergeApi\ReservationController@index',
    'middleware' => 'wow.api'
]);
//Get a particular reservation for edit
Route::get('conciergeapi/reservation/{id}/edit', [
    'uses' => 'ConciergeApi\ReservationController@edit',
    'middleware' => 'wow.api'
]);
//Get a particular reservation for display
Route::get('conciergeapi/reservation/{id}', [
    'uses' => 'ConciergeApi\ReservationController@show',
    'middleware' => 'wow.api'
]);
//Update a particular reservation
Route::put('conciergeapi/reservation/{id}', [
    'uses' => 'ConciergeApi\ReservationController@update',
    'middleware' => 'wow.api'
]);
//Send a push notification with reservation details
Route::post('conciergeapi/reservation/{id}/notification', [
    'uses' => 'ConciergeApi\ReservationController@push',
    'middleware' => 'wow.api'
]);

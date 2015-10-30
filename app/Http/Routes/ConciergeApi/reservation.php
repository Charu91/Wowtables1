<?php
//Get Reservations based on status
Route::get('conciergeapi/reservation/{statuses}/{location}', [
    'uses' => 'ConciergeAPI\ReservationController@index'//,
    //'middleware' => 'concierge.api'
]);
//Get a particular reservation for edit
Route::get('conciergeapi/reservation/{id}/edit', [
    'uses' => 'ConciergeAPI\ReservationController@edit',
    'middleware' => 'concierge.api'
]);
//Get a particular reservation for display
Route::get('conciergeapi/reservation/{id}', [
    'uses' => 'ConciergeAPI\ReservationController@show',
    'middleware' => 'concierge.api'
]);
//Update a particular reservation
Route::put('conciergeapi/reservation/{id}', [
    'uses' => 'ConciergeAPI\ReservationController@update',
    'middleware' => 'concierge.api'
]);

//Update a particular reservation
Route::get('conciergeapi/reservation/experience/{id}', [
    'uses' => 'ConciergeAPI\ReservationController@getExperienceDetails'//,
    //'middleware' => 'concierge.api'
]);


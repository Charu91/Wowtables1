<?php

Route::get('/admin/adminreservations', [
    'uses' => 'AdminReservationsController@index',
    'as' => 'AdminReservationsHome',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/checkUser', [
    'uses' => 'AdminReservationsController@checkUser',
    'as' => 'AdminReservationsCheckUser',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/addmember', [
    'uses' => 'AdminReservationsController@addMember',
    'as' => 'AdminReservationsAddMember',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/mod_reservs', [
    'uses' => 'AdminReservationsController@myReservationDetails',
    'as' => 'AdminReservationsMyReservationDetails',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/getRelatedResults', [
    'uses' => 'AdminReservationsController@getRelatedResults',
    'as' => 'AdminReservationsGetRelatedResults',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/admin/adminreservations/restaurant_search/{term}', [
    'uses' => 'AdminReservationsController@restaurantSearch',
    'as' => 'AdminReservationsRestaurantSearch',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
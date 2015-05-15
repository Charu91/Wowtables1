<?php

Route::get('admin/restaurants/locations/', [
    'uses' => 'AdminRestaurantLocationsController@index',
    'as' => 'AdminRestaurantLocations',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/available_time_slots', [
    'uses' => 'AdminRestaurantLocationsController@available_time_slots',
    'as' => 'AdminRestaurantLocationsAvailableTimeSlots',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/create', [
    'uses' => 'AdminRestaurantLocationsController@create',
    'as' => 'AdminRestaurantLocationsCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/restaurants/locations/', [
    'uses' => 'AdminRestaurantLocationsController@store',
    'as' => 'AdminRestaurantLocationsStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@show',
    'as' => 'AdminRestaurantLocationsShow',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/{id}/edit', [
    'uses' => 'AdminRestaurantLocationsController@edit',
    'as' => 'AdminRestaurantLocationsEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@update',
    'as' => 'AdminRestaurantLocationsUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@destroy',
    'as' => 'AdminRestaurantLocationsDelete',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/restaurants/locations/getCity/{name}',[
    'uses' => 'AdminRestaurantLocationsController@getCityName',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);
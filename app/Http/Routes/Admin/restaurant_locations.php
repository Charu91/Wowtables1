<?php



Route::get('admin/restaurants/locations/', [
    'uses' => 'AdminRestaurantLocationsController@index',
    'as' => 'AdminGetRestaurants',
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
    'as' => 'AdminRestaurantCreate',
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
    'as' => 'AdminRestaurantShow',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/edit/{id}', [
    'uses' => 'AdminRestaurantLocationsController@edit',
    'as' => 'AdminRestaurantEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@update',
    'as' => 'AdminRestaurantUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@destroy',
    'as' => 'AdminRestaurantsDelete',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);
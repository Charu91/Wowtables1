<?php



Route::get('admin/restaurants', [
    'uses' => 'AdminRestaurantsController@index',
    'as' => 'AdminGetRestaurants',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/create', [
    'uses' => 'AdminRestaurantsController@create',
    'as' => 'AdminRestaurantCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/restaurants', [
    'uses' => 'AdminRestaurantsController@store',
    'as' => 'AdminRestaurantStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@show',
    'as' => 'AdminRestaurantShow',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/{id}/edit', [
    'uses' => 'AdminRestaurantsController@edit',
    'as' => 'AdminRestaurantEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@update',
    'as' => 'AdminRestaurantUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@destroy',
    'as' => 'AdminRestaurantsDelete',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/restaurant/attributes','VendorAttributesController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
<?php

Route::get('restaurants', [
    'uses' => 'Api\RestaurantsController@index',
    'as' => 'RestaurantsListings',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL')
]);
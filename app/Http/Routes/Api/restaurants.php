<?php

Route::get('restaurants', [
    'uses' => 'Api\RestaurantsController@index',
    'as' => 'RestaurantsListings',
    'middleware' => 'wow.api',
    'where' => [],
    'domain' => env('WEBSITE_URL')
]);
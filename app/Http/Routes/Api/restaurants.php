<?php

Route::get('restaurants', [
    'uses' => 'Api\RestaurantsController@index',
    'as' => 'RestaurantsListings',
    'middleware' => 'wow.api',
    'where' => [],
    'domain' => env('WEBSITE_URL')
]);

Route::get('api/bookmark/{type}/{id}', [
    'uses' => 'Api\RestaurantsController@bookmark',
    'as' => 'Bookmark',
    'middleware' => 'wow.api',
    'domain' => env('WEBSITE_URL')
]);

Route::get('api/bookmarklist', [
    'uses' => 'Api\RestaurantsController@bookmarklist',
    'as' => 'BookmarkList',
    /*'middleware' => 'wow.api',*/
    'domain' => env('WEBSITE_URL')
]);
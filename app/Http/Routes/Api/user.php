<?php

Route::post('login', [
    'uses' => 'Api\UserController@login',
    'as' => 'MobileUserLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL'),
]);

Route::post('register', [
    'uses' => 'Api\UserController@register',
    'as' => 'MobileUserRegister',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL'),
]);

Route::post('fb_login', [
    'uses' => 'Api\UserController@fb_login',
    'as' => 'MobileUserFBLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL'),
]);

Route::put('set_phone_location', [
    'uses' => 'Api\UserController@set_location_id_phone',
    'as' => 'MobileUserSetLocationPhone',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL'),
]);
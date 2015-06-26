<?php

Route::post('api/login', [
    'uses' => 'Api\UserController@login',
    'as' => 'MobileUserLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('api/register', [
    'uses' => 'Api\UserController@register',
    'as' => 'MobileUserRegister',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('api/fb_login', [
    'uses' => 'Api\UserController@fb_login',
    'as' => 'MobileUserFBLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('api/set_phone_location', [
    'uses' => 'Api\UserController@set_location_id_phone',
    'as' => 'MobileUserSetLocationPhone',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('api/version', 'Api\CheckVersionController@checkVersion');
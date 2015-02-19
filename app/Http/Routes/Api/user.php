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

Route::post('unlink', [
    'uses' => 'Api\UserController@unlink',
    'as' => 'MobileUserUnlink',
    'middleware' => ['mobile.access'],
    'where' => [],
    'domain' => env('API_URL')
]);
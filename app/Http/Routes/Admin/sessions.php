<?php


Route::get('admin/login', [
    'uses' => 'AdminController@loginView',
    'as' => 'AdminLoginView',
    'middleware' => ['redirect.admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/login', [
    'uses' => 'AdminController@login',
    'as' => 'AdminLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/logout', [
    'uses' => 'AdminController@logout',
    'as' => 'AdminLogout',
    'middleware' => [],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);
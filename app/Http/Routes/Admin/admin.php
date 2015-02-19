<?php

Route::get('admin', [
    'uses' => 'AdminController@index',
    'as' => 'AdminHome',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/dashboard', [
    'uses' => 'AdminController@dashboard',
    'as' => 'AdminDashboard',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

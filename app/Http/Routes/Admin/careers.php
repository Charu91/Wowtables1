<?php

/*Route::get('admin/careers/list', [
    'uses' => 'CareerController@index',
    'as' => '',
    'middleware' => ['admin.auth'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/careers/create', [
    'uses' => 'CareerController@create',
    'as' => '',
    'middleware' => ['admin.auth'],
    'domain' => env('WEBSITE_URL'),
]);*/

Route::resource('admin/careers','CareerController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

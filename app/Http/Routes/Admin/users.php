<?php

Route::get('admin/users', [
    'uses' => 'AdminUsersController@index',
    'as' => 'AdminUsers',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/create', [
    'uses' => 'AdminUsersController@create',
    'as' => 'AdminUserCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/users', [
    'uses' => 'AdminUsersController@store',
    'as' => 'AdminUserStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/{id}', [
    'uses' => 'AdminUsersController@show',
    'as' => 'AdminUsersShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/{id}/edit', [
    'uses' => 'AdminUsersController@edit',
    'as' => 'AdminUserEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/users/{id}', [
    'uses' => 'AdminUsersController@update',
    'as' => 'AdminUserUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/users/{id}', [
    'uses' => 'AdminUsersController@destroy',
    'as' => 'AdminUserDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/user/attributes','UserAttributesController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


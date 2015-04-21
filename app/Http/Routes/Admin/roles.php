<?php


Route::get('admin/roles',[
    'uses' => 'AdminRolesController@index',
    'as' => 'AdminRoles',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/create', [
    'uses' => 'AdminRolesController@create',
    'as' => 'AdminRolesCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/roles', [
    'uses' => 'AdminRolesController@store',
    'as' => 'AdminRolesStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/{id}', [
    'uses' => 'AdminRolesController@show',
    'as' => 'AdminRolesShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/edit/{id}', [
    'uses' => 'AdminRolesController@edit',
    'as' => 'AdminRolesEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/roles/{id}', [
    'uses' => 'AdminRolesController@update',
    'as' => 'AdminRolesUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/roles/{id}', [
    'uses' => 'AdminRolesController@destroy',
    'as' => 'AdminUserDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

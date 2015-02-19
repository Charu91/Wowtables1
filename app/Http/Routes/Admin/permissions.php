<?php

Route::get('admin/permissions',[
'uses' => 'AdminPermissionsController@index',
'as' => 'AdminPermissions',
'middleware' => [],
'where' => [],
'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/permissions/create', [
'uses' => 'AdminPermissionsController@create',
'as' => 'AdminPermissionsCreate',
'middleware' => [],
'where' => [],
'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/permissions', [
'uses' => 'AdminPermissionsController@store',
'as' => 'AdminPermissionsStore',
'middleware' => [],
'where' => [],
'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/permissions/{id}', [
'uses' => 'AdminPermissionsController@show',
'as' => 'AdminPermissionsShow',
'middleware' => [],
'where' => [],
'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/permissions/{id}', [
'uses' => 'AdminPermissionsController@destroy',
'as' => 'AdminPermissionsDelete',
'middleware' => [],
'where' => [],
'domain' => env('WEBSITE_URL'),
]);

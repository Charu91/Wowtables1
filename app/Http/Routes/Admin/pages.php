<?php


Route::get('admin/pages',[
    'uses' => 'AdminPagesController@index',
    'as' => 'AdminPages',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/pages/create', [
    'uses' => 'AdminPagesController@create',
    'as' => 'AdminPagesCreate',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/pages', [
    'uses' => 'AdminPagesController@store',
    'as' => 'AdminPagesStore',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/pages/{id}/edit', [
    'uses' => 'AdminPagesController@edit',
    'as' => 'AdminPagesEdit',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/pages/{id}', [
    'uses' => 'AdminPagesController@update',
    'as' => 'AdminPagesUpdate',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/pages/{id}', [
    'uses' => 'AdminPagesController@destroy',
    'as' => 'AdminPagesDelete',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/pages/{id}/preview', [
    'uses' => 'AdminPagesController@preview',
    'as' => 'AdminPagesPreview',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
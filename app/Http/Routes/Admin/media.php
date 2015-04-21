<?php


Route::get('admin/media', [
    'uses' => 'AdminMediaController@index',
    'as' => 'AdminMedia',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/modal', [
    'uses' => 'AdminMediaController@modal',
    'as' => 'AdminModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/', [
    'uses' => 'AdminMediaController@store',
    'as' => 'AdminMediaStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/edit/{id}', [
    'uses' => 'AdminMediaController@edit',
    'as' => 'AdminMediaEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/media/{id}', [
    'uses' => 'AdminMediaController@update',
    'as' => 'AdminMediaUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/media/{id}', [
    'uses' => 'AdminMediaController@destroy',
    'as' => 'AdminMediaDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/search', [
    'uses' => 'AdminMediaController@search',
    'as' => 'AdminMediaSearch',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/fetch', [
    'uses' => 'AdminMediaController@fetch',
    'as' => 'AdminMediaFetch',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


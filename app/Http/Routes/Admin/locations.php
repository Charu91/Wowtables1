<?php


Route::get('admin/locations', [
    'uses' => 'AdminLocationsController@index',
    'as' => 'AdminLocationsIndex',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/create', [
    'uses' => 'AdminLocationsController@create',
    'as' => 'AdminLocationCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/', [
    'uses' => 'AdminLocationsController@index',
    'as' => 'AdminLocationIndex',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/locations/', [
    'uses' => 'AdminLocationsController@store',
    'as' => 'AdminLocationStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/locationsupdate/', [
    'uses' => 'AdminLocationsController@updateSave',
    'as' => 'AdminLocationUpdateStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@edit',
    'as' => 'AdminLocationEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@update',
    'as' => 'AdminLocationUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/settings/locations/update/{id}', [
    'uses' => 'AdminLocationsController@locationUpdate',
    'as' => 'AdminLocationUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/settings/locations/remove/{id}', [
    'uses' => 'AdminLocationsController@locationRemove',
    'as' => 'AdminLocationRemove',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@delete',
    'as' => 'AdminLocationDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/slug', [
    'uses' => 'AdminLocationsController@slug',
    'as' => 'AdminLocationSlug',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/selectParents', [
    'uses' => 'AdminLocationsController@selectParents',
    'as' => 'AdminLocationsSelectParents',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
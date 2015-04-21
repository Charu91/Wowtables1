<?php


Route::get('admin/events', [
    'uses' => 'AdminEventsController@index',
    'as' => 'AdminEvents',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/create', [
    'uses' => 'AdminEventsController@create',
    'as' => 'AdminEventsCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/events', [
    'uses' => 'AdminEventsController@store',
    'as' => 'AdminEventsStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/{id}', [
    'uses' => 'AdminEventsController@show',
    'as' => 'AdminEventsShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/edit/{id}', [
    'uses' => 'AdminEventsController@edit',
    'as' => 'AdminEventsEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/events/{id}', [
    'uses' => 'AdminEventsController@update',
    'as' => 'AdminEventsUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/events/{id}', [
    'uses' => 'AdminEventsController@destroy',
    'as' => 'AdminEventsDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

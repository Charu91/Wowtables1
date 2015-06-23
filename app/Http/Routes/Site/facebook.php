<?php
Route::get('/users/facebook', [
    'uses' => 'Site\HomePageController@fbLogin',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/users/fbCallback', [
    'uses' => 'Site\HomePageController@fbCallback',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('fbAddCity/{cityName}',[
    'uses' => 'Site\HomePageController@fbAddCity',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
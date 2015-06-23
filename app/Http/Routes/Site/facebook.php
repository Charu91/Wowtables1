<?php
Route::get('/users/facebook', [
    'uses' => 'Site\HomePageController@fbLogin',
    'as' => '',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/users/fbCallback', [
    'uses' => 'Site\HomePageController@fbCallback',
    'as' => '',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('fbAddCity/{cityName}',[
    'uses' => 'Site\HomePageController@fbAddCity',
    'as' => '',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('userCity',[
    'uses' => 'Site\HomePageController@fbGetCityURL',
    'as' => '',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
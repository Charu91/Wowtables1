<?php


Route::get('/', [
    'uses' => 'Site\StaticPagesController@home',
    'as' => 'SiteHomePage',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/home', [
    'uses' => 'Site\StaticPagesController@loggedInHome',
    'as' => 'SiteHomePageLoggedIn',
    'middleware' => ['auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/pages/{slug}', [
    'uses' => 'Site\StaticPagesController@show',
    'as' => '',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/test', [
    'uses' => 'TestController@index',
    'as' => 'TestingPage',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/test/queue', [
    'uses' => 'TestController@queue',
    'as' => 'TestingPage',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
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

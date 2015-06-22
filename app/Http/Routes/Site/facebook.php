<?php
Route::get('/users/facebook', [
    'uses' => 'Site\HomePageController@fbLogin',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
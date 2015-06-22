<?php
Route::post('/users/facebook', [
    'uses' => 'Site\HomePageController@fbLogin',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
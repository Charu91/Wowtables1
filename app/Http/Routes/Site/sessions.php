<?php


Route::get('/login', [
    'uses' => 'Site\SessionsController@loginView',
    'as' => 'login_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/login', [
    'uses' => 'Site\SessionsController@login',
    'as' => 'login_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/register', [
    'uses' => 'Site\RegistrationsController@registerView',
    'as' => 'register_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/register', [
    'uses' => 'Site\RegistrationsController@register',
    'as' => 'register_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/logout', [
    'uses' => 'Site\SessionsController@logout',
    'as' => 'logout_path',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);
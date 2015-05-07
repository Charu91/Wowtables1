<?php
Route::get('/', [
    'uses' => 'Site\HomePageController@home',
    'as' => 'SiteHomePage',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/exp/lists/{city?}',[
    'uses' => 'site\ExperienceController@lists',
    'as' => '',
    'domain' => env('WEBSITE_URL'),
]);



Route::get('/set_reservation_location', [
    'uses' => 'Site\HomePageController@set_reservation_location',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::post('/users/login', [
    'uses' => 'Site\HomePageController@login',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/users/checkemail', [
    'uses' => 'Site\HomePageController@checkemail',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/users/register', [
    'uses' => 'Site\HomePageController@register',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/logout', [
    'uses' => 'Site\SessionsController@logout',
    'as' => 'logout_path',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/home', [
    'uses' => 'Site\StaticPagesController@loggedInHome',
    'as' => 'SiteHomePageLoggedIn',
    'middleware' => ['auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/{city}/',[
    'uses' => 'Site\ExperienceController@lists',
    'as' => 'experience.lists',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/{city}/experiences/{experience}/',[
    'uses' => 'Site\ExperienceController@details',
    'as' => 'experience.details',
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/{city}/alacarte/',[
    'uses' => 'Site\AlacarteController@lists',
    'as' => 'alacarte.lists',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/{city}/alacarte/{alacarteexperience}',[
    'uses' => 'Site\AlacarteController@details',
    'as' => 'alacarte.lists',
    'domain' => env('WEBSITE_URL'),
]);
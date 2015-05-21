<?php
Route::get('/', [
    'uses' => 'Site\HomePageController@home',
    'as' => 'SiteHomePage',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/exp/lists/{city?}',[
    'uses' => 'Site\ExperienceController@lists',
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

Route::post('/users/check_user', [
    'uses' => 'Site\HomePageController@check_user',
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

Route::post('/custom_search/sorting',[
    'uses' => 'Site\ExperienceController@sorting',
    'as' => 'experience.sorting',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/custom_search/search_filter',[
    'uses' => 'Site\ExperienceController@search_filter',
    'as' => 'experience.search_filter',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/custom_search/new_custom_search',[
    'uses' => 'Site\ExperienceController@new_custom_search',
    'as' => 'experience.new_custom_search',
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

Route::post('/alacarte_custom_search/search_filter',[
    'uses' => 'Site\AlacarteController@search_filter',
    'as' => 'alacarte.search_filter',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/alacarte_custom_search/new_custom_search',[
    'uses' => 'Site\AlacarteController@new_custom_search',
    'as' => 'alacarte.new_custom_search',
    'domain' => env('WEBSITE_URL'),
]);

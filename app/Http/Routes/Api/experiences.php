<?php

Route::get('experiences', [
    'uses' => 'Api\ExperiencesController@index',
    'as' => 'ApiExperiencesListings',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL')
]);

Route::get('experiences/{id}', [
    'uses' => 'Api\ExperiencesController@show',
    'as' => 'ApiExperienceDetails',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('API_URL')
]);

Route::resource('experience','Api\ExperiencesController@show');

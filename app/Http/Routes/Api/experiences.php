<?php

Route::get('experiences', [
    'uses' => 'Api\ExperiencesController@index',
    'as' => 'ApiExperiencesListings',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL')
]);
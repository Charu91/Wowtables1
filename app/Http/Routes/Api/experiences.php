<?php
/*

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

*/

Route::get('experience/{id}', array(
    'uses' => 'Api\ExperiencesController@show',
    'as' => 'ApiExperienceDetails',
    'middleware' => 'wow.api',
    'domain' => env('WEBSITE_URL')
   ));

//Route::resource('experience','Api\ExperiencesController@show');

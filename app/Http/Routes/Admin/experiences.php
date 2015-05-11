<?php


Route::get('admin/experiences', [
    'uses' => 'AdminExperiencesController@index',
    'as' => 'AdminExperiences',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/create', [
    'uses' => 'AdminExperiencesController@create',
    'as' => 'AdminExperienceCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/experiences/', [
    'uses' => 'AdminExperiencesController@store',
    'as' => 'AdminExperienceStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@show',
    'as' => 'AdminExperienceShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/{id}/edit', [
    'uses' => 'AdminExperiencesController@edit',
    'as' => 'AdminExperienceEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/locations/{id}/edit', [
    'uses' => 'AdminExperienceLocationsController@edit',
    'as' => 'AdminExperienceLocationsEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@update',
    'as' => 'AdminExperienceUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/experiences/locations/{id}', [
    'uses' => 'AdminExperienceLocationsController@update',
    'as' => 'AdminExperienceLocationsUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@destroy',
    'as' => 'AdminExperienceDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/experience/variants','AdminExperienceVariantsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/experience/complex','AdminComplexExperiencesController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/experience/locations','AdminExperienceLocationsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::post('admin/experience/locations/getVendorLocationsDetails',[
    'uses' => 'AdminExperienceLocationsController@getVendorLocationsDetails',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/experiences/deactive_Addon/{id}',[
    'uses' => 'AdminExperiencesController@deactive_Addon',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);



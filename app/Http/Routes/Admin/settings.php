<?php


Route::get('admin/settings/general', [
    'uses' => 'AdminSettingsController@general',
    'as' => 'adminSettingsGeneral',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/settings/locations', [
    'uses' => 'AdminSettingsController@locations',
    'as' => 'adminSettingsLocations',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

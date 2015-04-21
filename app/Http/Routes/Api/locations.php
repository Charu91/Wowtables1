<?php

Route::get('/locations/cities', [
    'uses' => 'Api\LocationsController@cities',
    'as' => 'MobileLocationsCities',
    'middleware' => [],
    'where' => [],
    'domain' => env('API_URL'),
]);
<?php

Route::get('/locations/cities', [
    'uses' => 'Api\LocationsController@cities',
    'as' => 'MobileLocationsCities',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('api/area/{cityID}','Api\LocationApiController@showCityAreas');
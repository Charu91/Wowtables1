<?php

Route::resource('admin/promotions/flags','AdminFlagsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/promotions/collections','AdminCollectionsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
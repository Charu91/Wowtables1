<?php

Route::resource('admin/promotions/flags','AdminFlagsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
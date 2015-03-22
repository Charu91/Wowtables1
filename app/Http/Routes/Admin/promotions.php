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


Route::resource('admin/promotions/listpage_sidebar','AdminListpageSidebarController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::resource('admin/promotions/email_footer_promotions','AdminEmailFooterPromotionsController',[
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
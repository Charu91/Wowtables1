<?php

Route::get('/admin/adminreservations', [
    'uses' => 'AdminReservationsController@index',
    'as' => 'AdminReservationsHome',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
Route::get('/admin/admingiftcards', [
    'uses' => 'AdminGiftCardController@index',
    'as' => 'AdminGIftCardHome',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/admingiftcards/checkGiftCard', [
    'uses' => 'AdminGiftCardController@checkGiftCard',
    'as' => 'AdminGiftCard',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/admingiftcards/addGiftCard', [
    'uses' => 'AdminGiftCardController@addGiftCard',
    'as' => 'AdminGiftCardAddGiftCard',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/admingiftcards/detailGiftCard', [
    'uses' => 'AdminGiftCardController@detailGiftCard',
    'as' => 'AdminGiftCardDetailGiftCard',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/checkUser', [
    'uses' => 'AdminReservationsController@checkUser',
    'as' => 'AdminReservationsCheckUser',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);



Route::post('/admin/adminreservations/addmember', [
    'uses' => 'AdminReservationsController@addMember',
    'as' => 'AdminReservationsAddMember',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/mod_reservs', [
    'uses' => 'AdminReservationsController@myReservationDetails',
    'as' => 'AdminReservationsMyReservationDetails',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/getRelatedResults', [
    'uses' => 'AdminReservationsController@getRelatedResults',
    'as' => 'AdminReservationsGetRelatedResults',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/getExp_info', [
    'uses' => 'AdminReservationsController@getExp_info',
    'as' => 'AdminReservationsGetExpInfo',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/getAla_info', [
    'uses' => 'AdminReservationsController@getAla_info',
    'as' => 'AdminReservationsGetAlaInfo',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/experience_checkout', [
    'uses' => 'AdminReservationsController@experienceCheckout',
    'as' => 'AdminReservationsExperienceCheckout',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/admin/adminreservations/ac_checkout', [
    'uses' => 'AdminReservationsController@alacarteCheckout',
    'as' => 'AdminReservationsAlacarteCheckout',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/admin/adminreservations/restaurant_search/{term}', [
    'uses' => 'AdminReservationsController@restaurantSearch',
    'as' => 'AdminReservationsRestaurantSearch',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
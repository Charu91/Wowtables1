<?php

Route::get('admin/bookings', [
    'uses' => 'ReservationController@unconfirmed',
    'as' => 'BookingList',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/{id}/edit', [
    'uses' => 'ReservationController@edit',
    'as' => 'BookingEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);



Route::put('admin/bookings/attributes/{id}', [
    'uses' => 'ReservationController@update',
    'as' => 'ReservationAttributesUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/cancel/{id}/', [
    'uses' => 'ReservationController@cancelReservationRestaurant',
    'as' => 'ReservationCancel',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/change/{id}/', [
    'uses' => 'ReservationController@changeReservationRestaurant',
    'as' => 'ReservationChange',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/restaurant/{id}/', [
    'uses' => 'ReservationController@sendReservationRestaurant',
    'as' => 'ReservationSend',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/sendconfirmation/{id}/', [
    'uses' => 'ReservationController@sendCustomerConfirmation',
    'as' => 'SendConfirmation',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/bookings/changestatus', [
    'uses' => 'ReservationController@changeStatus',
    'as' => 'ChangeStatus',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/bookings/order_completed/{id}/{status}', [
    'uses' => 'ReservationController@orderCompleted',
    'as' => 'OrderCompleted',
    'middleware' => [],
    'where' => ['id' => '\d+','status' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/bookings/bookingcancel/{id}/{reservtype}/{statusid}', [
    'uses' => 'ReservationController@changeStatusBookingCancelled',
    'as' => 'BookingCancelled',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/bookings/pricing', [
    'uses' => 'ReservationController@updateBilling',
    'as' => 'UpdatePricing',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/unconfirmed', [
    'uses' => 'ReservationController@unconfirmed',
    'as' => 'Unconfirmed',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/missing', [
    'uses' => 'ReservationController@missing',
    'as' => 'Missing',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/all', [
    'uses' => 'ReservationController@all',
    'as' => 'All',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/bookings/today', [
    'uses' => 'ReservationController@today',
    'as' => 'Today',
    'domain' => env('WEBSITE_URL'),
]);



/*Route::post('admin/bookings/admincomments', [
    'uses' => 'ReservationController@addAdminComments',
    'as' => 'AdminComments',
    'middleware' => [],
    'domain' => env('WEBSITE_URL'),
]);*/
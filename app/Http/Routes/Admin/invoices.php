<?php

Route::get('admin/invoices', [
    'uses' => 'AdminInvoicesController@index',
    'as' => 'InvoicesList',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/create/invoices', [
    'uses' => 'AdminInvoicesController@createInvoices',
    'as' => 'CreateInvoices',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/generate/invoices', [
    'uses' => 'AdminInvoicesController@generateInvoices',
    'as' => 'GenerateInvoices',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/invoice/vendor/pdf', [
    'uses' => 'AdminInvoicesController@generateVendorPdf',
    'as' => 'GenerateVendorPdf',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
Route::post('admin/invoice/vendor/location/pdf', [
    'uses' => 'AdminInvoicesController@generatePdf',
    'as' => 'GenerateVendorLocationPdf',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/invoice/pdfview', [
    'uses' => 'AdminInvoicesController@viewPdf',
    'as' => 'ViewPdf',
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

/*Route::get('admin/bookings/{id}/edit', [
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

Route::get('admin/bookings/changestatus/{id}/{status}', [
    'uses' => 'ReservationController@changeStatus',
    'as' => 'ChangeStatus',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);*/
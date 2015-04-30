<?php

Route::delete('device/unlink', [
    'uses' => 'Api\UserDevicesController@unlink',
    'as' => 'MobileUserDeviceUnlink',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL')
]);

Route::put('device/notification', [
    'uses' => 'Api\UserDevicesController@notification',
    'as' => 'MobileUserDeviceAddNotification',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL')
]);
<?php

Route::post('conciergeapi/login','App\Http\Controllers\ConciergeApi\UserController@login');

Route::put('conciergeapi/addNotificationId','ConciergeApi\UserController@addNotificationId');

Route::post('conciergeapi/version', 'ConciergeApi\CheckVersionController@checkVersion');

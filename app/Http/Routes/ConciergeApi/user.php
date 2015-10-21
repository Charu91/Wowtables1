<?php

Route::post('conciergeapi/login','ConciergeAPI\UserController@login');

Route::post('conciergeapi/logout','ConciergeAPI\UserController@logout');

Route::put('conciergeapi/addNotificationId','ConciergeAPI\UserController@addNotificationId');

Route::post('conciergeapi/version', 'ConciergeAPI\MiscController@checkVersion');

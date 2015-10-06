<?php

Route::post('conciergeapi/login','ConciergeApi\UserController@login');

Route::put('conciergeapi/addNotificationId','ConciergeApi\UserController@addNotificationId');

Route::post('conciergeapi/version', 'ConciergeApi\CheckVersionController@checkVersion');

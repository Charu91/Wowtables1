<?php

Route::post('conciergeapi/login','ConciergeAPI\UserController@login');

Route::put('conciergeapi/addNotificationId','ConciergeAPI\UserController@addNotificationId');

Route::post('conciergeapi/version', 'ConciergeAPI\CheckVersionController@checkVersion');

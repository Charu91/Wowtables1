<?php
Route::post('resetPassword', 'Api\PasswordController@createPassword');

Route::get('resetPassword/{token}', 'Api\PasswordController@verifyResetToken');

Route::put('resetPassword', 'Api\PasswordController@updatePassword');
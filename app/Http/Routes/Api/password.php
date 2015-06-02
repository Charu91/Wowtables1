<?php
Route::post('forgotPassword', 'Api\ResetPasswordController@forgotPasswordRequest');

Route::get('forgotPassword/{token}', 'Api\ResetPasswordController@verifyResetToken');

Route::put('forgotPassword', 'Api\ResetPasswordController@newPassword');
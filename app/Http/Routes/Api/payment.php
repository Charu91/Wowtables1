<?php

//Route::post('getpayuhash', 'Api\PaymentController@getMobileHash');

Route::post('api/get_payu_hash',
				array(
					'uses' => 'Api\PaymentController@getMobileHash',
					'as' => '',
					'middleware' => 'wow.api',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));

Route::post('api/save_transaction',
				array(
					'uses' => 'Api\PaymentController@savePaymentTransaction',
					'as' => '',
					'middleware' => '',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));
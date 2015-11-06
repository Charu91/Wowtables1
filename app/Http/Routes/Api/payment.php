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
					//'middleware' => '',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));

Route::post('api/payu_success',
				array(
					'uses' => 'Api\PaymentController@getSuccessResponse',
					'as' => '',
					//'middleware' => '',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));

Route::post('api/payu_failure',
				array(
					'uses' => 'Api\PaymentController@getFailureResponse',
					'as' => '',
					//'middleware' => '',
					'where' => array(),
    				'domain' => env('WEBSITE_URL'),
    			));

Route::get('api/payuapi',
	array(
		'uses' => 'Api\PaymentController@payuApiResponse',
		'as' => '',
		//'middleware' => '',
		'where' => array(),
		'domain' => env('WEBSITE_URL'),
));
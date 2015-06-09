<?php
Route::get('/', [
    'uses' => 'Site\HomePageController@home',
    'as' => 'SiteHomePage',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/registration', [
    'uses' => 'Site\HomePageController@home',
    'as' => 'SiteRegistration',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/exp/lists/{city?}',[
    'uses' => 'Site\ExperienceController@lists',
    'as' => '',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/set_reservation_location', [
    'uses' => 'Site\HomePageController@set_reservation_location',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::post('/users/login', [
    'uses' => 'Site\HomePageController@login',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/users/checkemail', [
    'uses' => 'Site\HomePageController@checkemail',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('users/check_user', [
    'uses' => 'Site\HomePageController@check_user',
    'as' => 'usercheck',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/users/register', [
    'uses' => 'Site\HomePageController@register',
    'as' => '',
    'middleware' => ['guest'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/logout', [
    'uses' => 'Site\SessionsController@logout',
    'as' => 'logout_path',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/home', [
    'uses' => 'Site\StaticPagesController@loggedInHome',
    'as' => 'SiteHomePageLoggedIn',
    'middleware' => ['auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('pages/{pages}',[
    'uses' => 'Site\StaticPagesController@pages',
    'as' => 'aboutus',
    'domain' => env('WEBSITE_URL'),
])->where(['pages' => '.*']);

Route::get('/{city}/',[
    'uses' => 'Site\ExperienceController@lists',
    'as' => 'experience.lists',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/collection/{collectionName}',[
    'uses' => 'Site\ExperienceController@collection',
    'as' => 'websiteCollection',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/custom_search/sorting',[
    'uses' => 'Site\ExperienceController@sorting',
    'as' => 'experience.sorting',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/custom_search/search_filter',[
    'uses' => 'Site\ExperienceController@search_filter',
    'as' => 'experience.search_filter',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/custom_search/new_custom_search',[
    'uses' => 'Site\ExperienceController@new_custom_search',
    'as' => 'experience.new_custom_search',
    'domain' => env('WEBSITE_URL'),
]);



Route::get('/{city}/experiences/{experience}/',[
    'uses' => 'Site\ExperienceController@details',
    'as' => 'experience.details',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/experiences/thankyou/{orderid}',[
    'uses' => 'Site\ExperienceController@thankyou',
    //'where' => '*',
    'as' => 'experienceThankyou',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/alacarte/thankyou/{orderid}',[
    'uses' => 'Site\AlacarteController@thankyou',
    //'where' => '*',
    'as' => 'alacarteThankyou',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('orders/expcheckout',[
    'uses' => 'Site\ExperienceController@exporder',
    'as' => 'experience.expcheckout',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('orders/check_exporder_exists',[
    'uses' => 'Site\ExperienceController@exporderexists',
    'as' => 'experience.exporderexists',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('users/forgot_password',[
    'uses' => 'Site\HomePageController@forgot_password',
    'as' => 'websiteForgotPassword',
    'domain' => env('WEBSITE_URL'),
]);


Route::post('orders/restaurant_checkout',[
    'uses' => 'Site\AlacarteController@alaorder',
    'as' => 'alacarte.alacheckout',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('orders/check_alaorder_exists',[
    'uses' => 'Site\AlacarteController@alaorderexists',
    'as' => 'alacarte.alaordmumbaierexists',
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/{city}/alacarte/',[
    'uses' => 'Site\AlacarteController@lists',
    'as' => 'alacarte.lists',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/{city}/alacarte/{alacarteexperience}',[
    'uses' => 'Site\AlacarteController@details',
    'as' => 'alacarte.lists',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('/alacarte_custom_search/search_filter',[
    'uses' => 'Site\AlacarteController@search_filter',
    'as' => 'alacarte.search_filter',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/alacarte_custom_search/new_custom_search',[
    'uses' => 'Site\AlacarteController@new_custom_search',
    'as' => 'alacarte.new_custom_search',
    'domain' => env('WEBSITE_URL'),
]);

/*Route::get('/pages/about-us',[
    'uses' => 'Site\ExperienceController@aboutUs',
    'as' => 'websiteCollection',
    'domain' => env('WEBSITE_URL'),
]);*/

Route::get('forgotPassword/{token}/{userid}',[
    'uses' => 'Site\HomePageController@verifyResetToken',
    'as' => 'websiteVerifyPasswordToken',
    'domain' => env('WEBSITE_URL'),
]);

Route::post('users/save_changed_pass',[
    'uses' => 'Site\HomePageController@newPassword',
    'as' => 'websiteSetNewPassword',
    'domain' => env('WEBSITE_URL'),
]);

Route::get('/login', [
    'uses' => 'Site\SessionsController@loginView',
    'as' => 'login_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/login', [
    'uses' => 'Site\SessionsController@login',
    'as' => 'login_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/register', [
    'uses' => 'Site\RegistrationsController@registerView',
    'as' => 'register_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/register', [
    'uses' => 'Site\RegistrationsController@register',
    'as' => 'register_path',
    'middleware' => ['guest'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/users/myreservations', [
    'uses' => 'Site\RegistrationsController@reservationRecord',
    'as' => 'reservationRecord',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/orders/cancel_reservation', [
    'uses' => 'Site\RegistrationsController@reservationCancel',
    'as' => 'reservationCancel',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('/users/get_reservetion/{id}', [
    'uses' => 'Site\RegistrationsController@changeReserve',
    'as' => 'changeReserve',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/orders/edit_reservetion', [
    'uses' => 'Site\RegistrationsController@updateReservetion',
    'as' => 'updateReservetion',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/users/timedataload', [
    'uses' => 'Site\RegistrationsController@timedataload',
    'as' => 'timedataload',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::post('/users/party_sizeajax', [
    'uses' => 'Site\RegistrationsController@partysizeajax',
    'as' => 'partysizeajax',
    'middleware' => ['auth'],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);





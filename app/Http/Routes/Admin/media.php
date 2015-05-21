<?php


Route::get('admin/media', [
    'uses' => 'AdminMediaController@index',
    'as' => 'AdminMedia',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/modal', [
    'uses' => 'AdminMediaController@modal',
    'as' => 'AdminModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


Route::get('/admin/media/web_collection_modal', [
    'uses' => 'AdminMediaController@webCollectionModal',
    'as' => 'AdminWebCollectionModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

/*Route::get('admin/media/modal', [
    'uses' => 'AdminMediaController@modal',
    'as' => 'AdminModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);*/

Route::get('admin/media/listing_modal', [
    'uses' => 'AdminMediaController@listing_modal',
    'as' => 'AdminListingModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/sidebar_modal', [
    'uses' => 'AdminMediaController@sidebar_modal',
    'as' => 'AdminSidebarModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/web_collection_modal', [
    'uses' => 'AdminMediaController@web_collection_modal',
    'as' => 'AdminWebsiteCollectionModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/gallery_modal', [
    'uses' => 'AdminMediaController@gallery_modal',
    'as' => 'AdminListingGalleryModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/mobile_modal', [
    'uses' => 'AdminMediaController@mobile_modal',
    'as' => 'AdminListingMobileModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/mobile_exp_modal', [
    'uses' => 'AdminMediaController@mobile_exp_modal',
    'as' => 'AdminListingMobileExpModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/web_collection_modal', [
    'uses' => 'AdminMediaController@web_collection_modal',
    'as' => 'AdminWebCollectionModal',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/store', [
    'uses' => 'AdminMediaController@store',
    'as' => 'AdminMediaStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/listing_media', [
    'uses' => 'AdminMediaController@listingStore',
    'as' => 'AdminMediaGalleryStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/sidebars_media', [
    'uses' => 'AdminMediaController@sidebarStore',
    'as' => 'AdminMediaSidebarStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/web_collection_media', [
    'uses' => 'AdminMediaController@webCollectionStore',
    'as' => 'AdminMediaGalleryStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/gallery_media', [
    'uses' => 'AdminMediaController@galleryStore',
    'as' => 'AdminMediaGalleryStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/mobile_media', [
    'uses' => 'AdminMediaController@mobileStore',
    'as' => 'AdminMediaMobileStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/media/mobile_exp_media', [
    'uses' => 'AdminMediaController@mobileExpStore',
    'as' => 'AdminMediaExpMobileStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/edit/{id}', [
    'uses' => 'AdminMediaController@edit',
    'as' => 'AdminMediaEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/media/{id}', [
    'uses' => 'AdminMediaController@update',
    'as' => 'AdminMediaUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/media/{id}', [
    'uses' => 'AdminMediaController@destroy',
    'as' => 'AdminMediaDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/search', [
    'uses' => 'AdminMediaController@search',
    'as' => 'AdminMediaSearch',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/media/fetch', [
    'uses' => 'AdminMediaController@fetch',
    'as' => 'AdminMediaFetch',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);


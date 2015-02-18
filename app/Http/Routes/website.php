<?php

Route::get('admin', [
    'uses' => 'AdminController@index',
    'as' => 'AdminHome',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/dashboard', [
    'uses' => 'AdminController@dashboard',
    'as' => 'AdminDashboard',
    'middleware' => ['admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/login', [
    'uses' => 'AdminController@loginView',
    'as' => 'AdminLoginView',
    'middleware' => ['redirect.admin.auth'],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/login', [
    'uses' => 'AdminController@login',
    'as' => 'AdminLogin',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/logout', [
    'uses' => 'AdminController@logout',
    'as' => 'AdminLogout',
    'middleware' => [],
    'where' =>[],
    'domain' => env('WEBSITE_URL')
]);

Route::get('admin/users', [
    'uses' => 'AdminUsersController@index',
    'as' => 'AdminUsers',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/create', [
    'uses' => 'AdminUsersController@create',
    'as' => 'AdminUserCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/users', [
    'uses' => 'AdminUsersController@store',
    'as' => 'AdminUserStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/{id}', [
    'uses' => 'AdminUsersController@show',
    'as' => 'AdminUsersShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/users/edit/{id}', [
    'uses' => 'AdminUsersController@edit',
    'as' => 'AdminUserEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/users/{id}', [
    'uses' => 'AdminUsersController@update',
    'as' => 'AdminUserUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/users/{id}', [
    'uses' => 'AdminUsersController@destroy',
    'as' => 'AdminUserDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles',[
    'uses' => 'AdminRolesController@index',
    'as' => 'AdminRoles',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/create', [
    'uses' => 'AdminRolesController@create',
    'as' => 'AdminRolesCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/roles', [
    'uses' => 'AdminRolesController@store',
    'as' => 'AdminRolesStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/{id}', [
    'uses' => 'AdminRolesController@show',
    'as' => 'AdminRolesShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/roles/edit/{id}', [
    'uses' => 'AdminRolesController@edit',
    'as' => 'AdminRolesEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/roles/{id}', [
    'uses' => 'AdminRolesController@update',
    'as' => 'AdminRolesUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/roles/{id}', [
    'uses' => 'AdminRolesController@destroy',
    'as' => 'AdminUserDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/permissions',[
    'uses' => 'AdminPermissionsController@index',
    'as' => 'AdminPermissions',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/permissions/create', [
    'uses' => 'AdminPermissionsController@create',
    'as' => 'AdminPermissionsCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/permissions', [
    'uses' => 'AdminPermissionsController@store',
    'as' => 'AdminPermissionsStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/permissions/{id}', [
    'uses' => 'AdminPermissionsController@show',
    'as' => 'AdminPermissionsShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/permissions/{id}', [
    'uses' => 'AdminPermissionsController@destroy',
    'as' => 'AdminPermissionsDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

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

Route::post('admin/media/', [
    'uses' => 'AdminMediaController@store',
    'as' => 'AdminMediaStore',
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

Route::get('admin/events', [
    'uses' => 'AdminEventsController@index',
    'as' => 'AdminEvents',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/create', [
    'uses' => 'AdminEventsController@create',
    'as' => 'AdminEventsCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/events', [
    'uses' => 'AdminEventsController@store',
    'as' => 'AdminEventsStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/{id}', [
    'uses' => 'AdminEventsController@show',
    'as' => 'AdminEventsShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/events/edit/{id}', [
    'uses' => 'AdminEventsController@edit',
    'as' => 'AdminEventsEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/events/{id}', [
    'uses' => 'AdminEventsController@update',
    'as' => 'AdminEventsUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/events/{id}', [
    'uses' => 'AdminEventsController@destroy',
    'as' => 'AdminEventsDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences', [
    'uses' => 'AdminExperiencesController@index',
    'as' => 'AdminExperiences',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/create', [
    'uses' => 'AdminExperiencesController@create',
    'as' => 'AdminExperienceCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/experiences/', [
    'uses' => 'AdminExperiencesController@store',
    'as' => 'AdminExperienceStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@show',
    'as' => 'AdminExperienceShow',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/experiences/edit/{id}', [
    'uses' => 'AdminExperiencesController@edit',
    'as' => 'AdminExperinceEdit',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@update',
    'as' => 'AdminExperienceUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/experiences/{id}', [
    'uses' => 'AdminExperiencesController@destroy',
    'as' => 'AdminExperienceDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants', [
    'uses' => 'AdminRestaurantsController@index',
    'as' => 'AdminGetRestaurants',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/create', [
    'uses' => 'AdminRestaurantsController@create',
    'as' => 'AdminRestaurantCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/restaurants', [
    'uses' => 'AdminRestaurantsController@store',
    'as' => 'AdminRestaurantStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@show',
    'as' => 'AdminRestaurantShow',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/edit/{id}', [
    'uses' => 'AdminRestaurantsController@edit',
    'as' => 'AdminRestaurantEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@update',
    'as' => 'AdminRestaurantUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/restaurants/{id}', [
    'uses' => 'AdminRestaurantsController@destroy',
    'as' => 'AdminRestaurantsDelete',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/', [
    'uses' => 'AdminRestaurantLocationsController@index',
    'as' => 'AdminGetRestaurants',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/available_time_slots', [
    'uses' => 'AdminRestaurantLocationsController@available_time_slots',
    'as' => 'AdminRestaurantLocationsAvailableTimeSlots',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/create', [
    'uses' => 'AdminRestaurantLocationsController@create',
    'as' => 'AdminRestaurantCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/restaurants/locations/', [
    'uses' => 'AdminRestaurantLocationsController@store',
    'as' => 'AdminRestaurantLocationsStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@show',
    'as' => 'AdminRestaurantShow',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/restaurants/locations/edit/{id}', [
    'uses' => 'AdminRestaurantLocationsController@edit',
    'as' => 'AdminRestaurantEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@update',
    'as' => 'AdminRestaurantUpdate',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/restaurants/locations/{id}', [
    'uses' => 'AdminRestaurantLocationsController@destroy',
    'as' => 'AdminRestaurantsDelete',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/settings/general', [
    'uses' => 'AdminSettingsController@general',
    'as' => 'adminSettingsGeneral',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/settings/locations', [
    'uses' => 'AdminSettingsController@locations',
    'as' => 'adminSettingsLocations',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations', [
    'uses' => 'AdminLocationsController@index',
    'as' => 'AdminLocationsIndex',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/create', [
    'uses' => 'AdminLocationsController@create',
    'as' => 'AdminLocationCreate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/', [
    'uses' => 'AdminLocationsController@index',
    'as' => 'AdminLocationIndex',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::post('admin/locations/', [
    'uses' => 'AdminLocationsController@store',
    'as' => 'AdminLocationStore',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@edit',
    'as' => 'AdminLocationEdit',
    'middleware' => [],
    'where' => ['id' => '\d+'],
    'domain' => env('WEBSITE_URL'),
]);

Route::put('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@update',
    'as' => 'AdminLocationUpdate',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::delete('admin/locations/{id}', [
    'uses' => 'AdminLocationsController@delete',
    'as' => 'AdminLocationDelete',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/slug', [
    'uses' => 'AdminLocationsController@slug',
    'as' => 'AdminLocationSlug',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);

Route::get('admin/locations/selectParents', [
    'uses' => 'AdminLocationsController@selectParents',
    'as' => 'AdminLocationsSelectParents',
    'middleware' => [],
    'where' => [],
    'domain' => env('WEBSITE_URL'),
]);
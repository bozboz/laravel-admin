<?php

/*
|--------------------------------------------------------------------------
| Admin Package Routes
|--------------------------------------------------------------------------
*/

Route::group(array('middleware' => ['web'], 'namespace' => 'Bozboz\Admin\Http\Controllers', 'prefix' => 'admin'), function() {

	Route::group(array('middleware' => 'auth'), function() {
		Route::resource('users', 'UserAdminController', array('except' => array('show')));
		Route::resource('roles', 'UserRoleAdminController', array('except' => array('show')));
		Route::resource('media', 'MediaLibraryAdminController');

		Route::resource('permissions', 'PermissionAdminController', array('except' => array('show')));

		Route::get('/', 'AdminController@getIndex');
		Route::get('logout', 'AdminController@getLogout');

		Route::post('sort', 'SortController@sort');
	});

	Route::group(array('middleware' => 'guest'), function() {
		Route::get('login', 'AdminController@getLogin');
		Route::post('login', 'AdminController@postLogin');
	});

	Route::controller('password', 'RemindersController');

	Route::post('login-as/{user}', [
		'uses' => 'UserAdminController@loginAs',
	]);

	Route::post('previous-user', [
		'uses' => 'UserAdminController@previousUser',
		'as' => 'admin.previous-user'
	]);
});

Route::get('admin/versions', function() {
	$json = json_decode(file_get_contents(base_path('composer.lock')));
	$packages = collect($json->packages)->filter(function($package) {
		return strpos($package->name, 'bozboz') === 0;
	});
	return view('admin::versions', compact('packages'));
});
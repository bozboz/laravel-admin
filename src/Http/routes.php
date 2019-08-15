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

		Route::get('files/folder/options', 'FileFolderController@getDropdownOptions');
		Route::post('files/folder/add', 'FileFolderController@store');
		Route::post('files/folder/edit/{id}', 'FileFolderController@edit');
		Route::post('files/folder/delete/{id}', 'FileFolderController@destroy');

		Route::get('files.js', 'FileController@js');

		Route::get('/files', 'FileController@main');

		Route::get('files/tags', 'FileController@getTags');

		Route::get('files/upload', 'FileController@uploader');
		Route::post('files/upload', 'FileController@store');

		Route::get('files/{type}/{id?}', 'FileController@index');
		Route::post('files/add', 'FileController@store');
		Route::post('files/edit/{id}', 'FileController@edit');
		Route::post('files/delete/{id}', 'FileController@destroy');
	});

	Route::group(array('middleware' => 'guest'), function() {
		Route::get('login', 'AdminController@getLogin');
		Route::post('login', 'AdminController@postLogin');
	});

	Route::controller('password', 'RemindersController');

	Route::post('users/send-reset/{user}', [
		'uses' => 'UserAdminController@sendReset'
	]);
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
	$packages = collect($json->packages);
	if ( ! request()->has('all')) {
		$packages = $packages->filter(function($package) {
			return strpos($package->name, 'bozboz') === 0;
		});
	}

	if (request()->has('json')) {
		return $packages;
	}

	return view('admin::versions', compact('packages'));
});
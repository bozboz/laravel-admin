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

		Route::resource('permissions', 'PermissionAdminController', array('except' => array('show')));

		Route::get('/', 'AdminController@getIndex');
		Route::get('logout', 'AdminController@getLogout');

		Route::post('sort', 'SortController@sort');

		Route::get('media--{version}.js', 'FileController@js');
		Route::get('tinymce/skin/{file}', function($file) {
			if (!file_exists(base_path('vendor/bozboz/admin/resources/js/tinymce/skin/'.$file))) {
				return abort(404);
			}
			return response(file_get_contents(base_path('vendor/bozboz/admin/resources/js/tinymce/skin/'.$file)))
				->header('Content-Type', 'text/css');
		})->where('file', '(.+)?');

		Route::get('media', 'FileController@main')->name('admin.media.index');
		Route::get('media/upload', 'FileController@uploader');

		Route::get('media/folder/options', 'FileFolderController@getDropdownOptions');
		Route::post('media/folder/add', 'FileFolderController@store');
		Route::post('media/folder/update/{id}', 'FileFolderController@update');
		Route::post('media/folder/delete/{id}', 'FileFolderController@destroy');

		Route::get('media/tags', 'FileController@getTags');

		Route::post('media/upload', 'FileController@store');
		Route::put('media/upload', 'FileController@store');

		Route::get('media/{type}/{id?}', 'FileController@index');
		Route::post('media/add', 'FileController@store');
		Route::post('media/update/{id}', 'FileController@update');
		Route::post('media/delete/{id}', 'FileController@destroy');
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

Route::get('media/image/{mode}/{size}/{filename}', 'Bozboz\Admin\Http\Controllers\FileController@resize')
	->name('admin.image.resize')
	->where('size', '\d+x?\d*');
<?php

/*
|--------------------------------------------------------------------------
| Admin Package Routes
|--------------------------------------------------------------------------
*/

Route::group(array('namespace' => 'Bozboz\Admin\Http\Controllers', 'prefix' => 'admin'), function() {

	Route::group(array('before' => 'auth'), function() {
		Route::resource('pages', 'PageAdminController', array('except' => array('show')));
		Route::resource('users', 'UserAdminController', array('except' => array('show')));
		Route::resource('media', 'MediaLibraryAdminController');

		Route::get('/', 'AdminController@getIndex');
		Route::get('logout', 'AdminController@getLogout');

		Route::post('sort', 'SortController@sort');
	});

	Route::group(array('before' => 'guest'), function() {
		Route::get('login', 'AdminController@getLogin');
		Route::post('login', 'AdminController@postLogin');
	});

	Route::controller('password', 'RemindersController');

});

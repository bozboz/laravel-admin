<?php

/*
|--------------------------------------------------------------------------
| Admin Package Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::group(array('namespace' => 'Bozboz\Admin\Controllers'), function() {

	Route::group(array('prefix' => 'admin'), function() {

		View::composer('admin::partials.nav', 'Bozboz\Admin\Composers\Nav');

		Route::group(array('before' => 'auth'), function() {
			Route::resource('pages', 'PageAdminController');
			Route::resource('users', 'UserAdminController');

			Route::get('/', 'AdminController@getIndex');
			Route::get('logout', 'AdminController@getLogout');
		});

		Route::group(array('before' => 'guest'), function() {
			Route::get('login', 'AdminController@getLogin');
			Route::post('login', 'AdminController@postLogin');
		});

	});

	Route::any('{slug}', 'PageController@show')->where('slug', '.*');

});

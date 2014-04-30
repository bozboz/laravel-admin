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
			Route::resource('pages', 'PageAdminController', array('except' => array('show')));
			Route::post('pages/reorder', 'PageAdminController@postReorder');
			Route::resource('users', 'UserAdminController', array('except' => array('show')));

			Route::get('/', 'AdminController@getIndex');
			Route::get('logout', 'AdminController@getLogout');
		});

		Route::group(array('before' => 'guest'), function() {
			Route::get('login', 'AdminController@getLogin');
			Route::post('login', 'AdminController@postLogin');
		});

	});

});

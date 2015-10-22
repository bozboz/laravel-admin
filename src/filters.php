<?php

Route::filter('auth', function()
{
	if (Auth::check() && ! Gate::allows('login')) {
		Auth::logout();
	}

	if (Auth::guest()) {
		return Redirect::guest('admin/login');
	}
});

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('admin');
});

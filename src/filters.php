<?php

Route::filter('auth', function()
{
	if (Auth::check() && !Auth::user()->is_admin) {
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

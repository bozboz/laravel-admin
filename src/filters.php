<?php

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('admin/login');
});

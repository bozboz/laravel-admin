<?php

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('admin/login');
});

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('admin');
});

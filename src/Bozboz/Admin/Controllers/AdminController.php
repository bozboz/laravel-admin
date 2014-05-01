<?php namespace Bozboz\Admin\Controllers;

use Redirect, Auth, View, Input;

class AdminController extends \BaseController
{
	public function getIndex()
	{
		return View::make('admin::index');
	}

	public function getLogin()
	{
		return View::make('admin::login');
	}

	public function postLogin()
	{
		$input = Input::only('email', 'password');
		if(Auth::attempt($input)) {
			return Redirect::intended('admin');
		} else {
			return Redirect::back()->withInput()->with('error', true);
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('admin')->with('message', 'Logged out');
	}
}

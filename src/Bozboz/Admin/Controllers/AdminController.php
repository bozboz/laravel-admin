<?php namespace Bozboz\Admin\Controllers;

use Redirect, Auth, View;

class AdminController extends \BaseController
{
	public function getIndex()
	{
		return View::make('admin::index');
	}

	public function getLogin()
	{
		View::addNamespace('admin', app_path() . '/Admin/views');
		return View::make('admin::login');
	}

	public function postLogin()
	{
		if(Auth::attempt(\Input::only('email','password')))
		{
			return Redirect::to('admin');
		} 
		return Redirect::back()->withInput()->with('message','Login Failed');
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('admin')->with('message', 'Logged out');
	}
}
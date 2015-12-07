<?php namespace Bozboz\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Redirect, Auth, View, Input;
use Bozboz\Permissions\Facades\Gate;

class AdminController extends Controller
{
	public function getIndex()
	{
		return View::make('admin::index', [
			'user' => Auth::user()
		]);
	}

	public function getLogin()
	{
		return View::make('admin::login');
	}

	public function postLogin()
	{
		$input = Input::only('email', 'password');

		if (Auth::attempt($input) && Gate::allows('admin_login')) {
			return Redirect::intended('admin');
		} else {
			Auth::logout();
			return Redirect::back()->withInput()->with('error', true);
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('admin')->with('message', 'Logged out');
	}
}

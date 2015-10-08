<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Models\User;
use Illuminate\Routing\Controller;
use Redirect, Auth, View, Input;

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
		$input['is_admin'] = true;

		if (Auth::attempt($input)) {
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

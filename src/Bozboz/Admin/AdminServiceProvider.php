<?php namespace Bozboz\Admin;

use Illuminate\Support\ServiceProvider;
use View;
use Event;

class AdminServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot()
	{
		$this->package('bozboz/admin');

		require __DIR__ . '/../../routes.php';
		require __DIR__ . '/../../filters.php';
		require __DIR__ . '/../../errors.php';

		$permissions = $this->app['permission.handler'];

		require __DIR__ . '/../../permissions.php';

		$this->app['events']->subscribe(new Subscribers\PageEventHandler);
	}
}

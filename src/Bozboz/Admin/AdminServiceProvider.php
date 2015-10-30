<?php namespace Bozboz\Admin;

use Bozboz\Permissions\PermissionServiceProvider;

class AdminServiceProvider extends PermissionServiceProvider
{
	public function register()
	{
		// Call the PermissionServiceProvider's register method
		parent::register();
	}

	public function boot()
	{
		// Call the PermissionServiceProvider's boot method
		parent::boot();

		$this->package('bozboz/admin');

		require __DIR__ . '/../../routes.php';
		require __DIR__ . '/../../filters.php';
		require __DIR__ . '/../../errors.php';

		$permissions = $this->app['permission.handler'];

		require __DIR__ . '/../../permissions.php';

		$this->app['events']->subscribe(new Subscribers\PageEventHandler);
	}
}

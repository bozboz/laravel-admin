<?php

namespace Bozboz\Admin\Providers;

use Bozboz\Permissions\Providers\PermissionServiceProvider;

class AdminServiceProvider extends PermissionServiceProvider
{
	public function register()
	{
		// Register middlewares
		$this->app['router']->middleware('auth', \Bozboz\Admin\Http\Middleware\AuthMiddleware::class);
		$this->app['router']->middleware('guest', \Bozboz\Admin\Http\Middleware\GuestMiddleware::class);

		$this->app->bind('Bozboz\Admin\Users\UserInterface', function($app) {
			return $app['auth.driver']->user();
		});

		// Call the PermissionServiceProvider's register method
		parent::register();
	}

	public function boot()
	{
		// Call the PermissionServiceProvider's boot method
		parent::boot();

		$packageRoot = __DIR__ . '/../..';

		$this->loadViewsFrom($packageRoot . '/resources/views', 'admin');

		$this->publishes([
			$packageRoot . '/database/migrations' => database_path('migrations')
		], 'migrations');

		$this->publishes([
			$packageRoot . '/config/admin.php' => config_path('admin.php')
		], 'config');

		if (! $this->app->routesAreCached()) {
			require $packageRoot . '/src/Http/routes.php';
		}

		$permissions = $this->app['permission.handler'];

		require __DIR__ . '/../permissions.php';

		$this->app['view']->composer(
			'admin::partials.nav',
			'Bozboz\Admin\Base\Composers\Nav'
		);
	}
}

<?php

namespace Bozboz\Admin\Providers;

use Bozboz\Admin\Subscribers\PageEventHandler;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot()
	{
		$packageRoot = __DIR__ . '/../../';

		$this->loadViewsFrom($packageRoot . '/resources/views', 'admin');

		$this->publishes([
			$packageRoot . '/public' => public_path('vendor/admin'),
		], 'public');

		$this->publishes([
			$packageRoot . '/database/migrations' => database_path('migrations')
		], 'migrations');

		$this->publishes([
			$packageRoot . '/config/admin.php' => config_path('admin.php')
		], 'config');

		if (! $this->app->routesAreCached()) {
			require $packageRoot . '/src/Http/routes.php';
			require $packageRoot . '/src/Http/filters.php';
		}

		$this->app['events']->subscribe(new PageEventHandler);

		$this->app['view']->composer(
			'admin::partials.nav',
			'Bozboz\Admin\Composers\Nav'
		);
	}
}

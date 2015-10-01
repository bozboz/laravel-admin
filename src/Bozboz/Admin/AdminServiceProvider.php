<?php namespace Bozboz\Admin;

use Illuminate\Support\ServiceProvider;
use View;
use Event;

class AdminServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerEvents();
	}

	public function boot()
	{
		$packageRoot = __DIR__ . '/../../..';

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
	}

	protected function registerEvents()
	{
		Event::subscribe(new Subscribers\PageEventHandler);
	}
}

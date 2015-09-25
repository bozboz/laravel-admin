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
		$packageRoot = __DIR__ . '/../..';

		$this->loadViewsFrom($packageRoot . '/views', 'admin');

		$this->publishes([
			$packageRoot . '/../public' => public_path('vendor/admin'),
		], 'public');

		$this->publishes([
			$packageRoot . '/migrations/' => database_path('migrations')
		], 'migrations');

		if (! $this->app->routesAreCached()) {
			require $packageRoot . '/routes.php';
			require $packageRoot . '/filters.php';
		}
	}

	protected function registerEvents()
	{
		Event::subscribe(new Subscribers\PageEventHandler);
	}
}

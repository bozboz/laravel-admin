<?php namespace Bozboz\Admin;

use Illuminate\Support\ServiceProvider;
use View;
use Event;

class AdminServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerEvents();
		$this->app->bind('field.mapper', 'Bozboz\Admin\FieldMapping\Mapper');
	}

	public function boot()
	{
		$this->package('bozboz/admin');
		require __DIR__ . '/../../routes.php';
		require __DIR__ . '/../../filters.php';
	}

	protected function registerEvents()
	{
		Event::subscribe(new Subscribers\PageEventHandler);
		Event::subscribe(new Subscribers\UserEventHandler);
	}
}

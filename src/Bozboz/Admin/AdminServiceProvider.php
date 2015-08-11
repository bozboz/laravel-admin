<?php namespace Bozboz\Admin;

use Illuminate\Support\ServiceProvider;
use View;
use Event;

class AdminServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerEvents();

        $this->app->alias(
            'Bozboz\Admin\Models\Base',
            'Bozboz\Admin\Models\BaseInterface'
        );
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
	}
}

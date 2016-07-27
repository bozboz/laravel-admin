<?php

namespace Bozboz\Admin\Providers;

use Bozboz\Admin\Reports\ActionFactory;
use Bozboz\Admin\Reports\Actions\Action;
use Bozboz\Admin\Reports\Actions\DropdownAction;
use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Button;
use Bozboz\Admin\Reports\Actions\Presenters\Form;
use Bozboz\Admin\Reports\Actions\Presenters\Link;
use Bozboz\Permissions\Providers\PermissionServiceProvider;

class AdminServiceProvider extends PermissionServiceProvider
{
	public function register()
	{
		// Register middlewares
		$this->app['router']->middleware('auth', \Bozboz\Admin\Http\Middleware\AuthMiddleware::class);
		$this->app['router']->middleware('guest', \Bozboz\Admin\Http\Middleware\GuestMiddleware::class);

		$this->app->singleton('admin.actions', function($app) {
			return new ActionFactory;
		});

		$this->app->bind('Bozboz\Admin\Users\UserInterface', function($app) {
			if ($this->app->runningInConsole()) {
				return new \Bozboz\Admin\Users\User;
			} else {
				return $app['auth.driver']->user();
			}
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

		$this->registerActions($this->app['admin.actions']);
	}

	protected function registerActions($actions)
	{
		$actions->register('create', function($action, $permission, $label = 'New') {
			return new Action(
				new Link($action, $label, 'fa fa-plus', [
					'class' => 'btn-success btn-create pull-right',
				]),
				new IsValid($permission)
			);
		});

		$actions->register('edit', function($action, $permission) {
			return new Action(
				new Link($action, 'Edit', 'fa fa-pencil', [
					'class' => 'btn-info btn-edit'
				]),
				new IsValid($permission)
			);
		});

		$actions->register('destroy', function($action, $permission) {
			return new Action(
				new Form($action, 'Delete', 'fa fa-trash', [
					'class' => 'btn-danger btn-sm btn-destroy',
					'data-warn' => 'Are you sure you want to delete?'
				], [
					'method' => 'DELETE'
				]),
				new IsValid($permission)
			);
		});

		$actions->register('submit', function($label, $icon = null, $attributes = []) {
			return new Action(
				new Button($label, $icon, $attributes + [
					'type' => 'submit',
					'class' => 'btn-success space-left pull-right',
				])
			);
		});

		$actions->register('dropdown', function($items, $label, $icon = null, $attributes = [], $dropdownAttributes = []) {
			return new DropdownAction($items, $label, $icon, $attributes, $dropdownAttributes);
		});

		$actions->register('custom', function($presenter, $permission) {
			return new Action($presenter, $permission);
		});
	}
}

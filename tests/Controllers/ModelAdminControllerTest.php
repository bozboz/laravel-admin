<?php namespace Bozboz\Admin\Tests\Controllers;

use Mockery, Auth, Form;
use Bozboz\Admin\Models\User;
use Illuminate\Support\Collection;
use Bozboz\Admin\Tests\TestCase;

class ModelAdminControllerTest extends TestCase
{
	private $pageMock;
	private $pageClass = 'Bozboz\Admin\Models\Page';

	public function setUp()
	{
		parent::setUp();
		$this->logUserIn();
		$this->registerPageMock();
	}

	private function logUserIn()
	{
		$user = User::find(1);
		Auth::login($user);
	}

	private function registerPageMock()
	{
		$this->pageMock = Mockery::mock($this->pageClass);
	}

	public function tearDown()
	{
		parent::tearDown();
		Mockery::close();
		Auth::logOut();
	}

	public function testViewHasReport()
	{
		$this->call('GET', 'admin/pages');
		$this->assertResponseOK();
		$this->assertViewHas('report');
	}

	public function testCreateMethod()
	{
		Form::shouldReceive('model')->once()->andReturn('open form')
		    ->shouldReceive('close')->once()->andReturn('form close');

		$field = Mockery::mock('\Bozboz\Admin\Fields\Field');
		$field->shouldReceive('getLabel')->once();
		$field->shouldReceive('getInput')->once();
		$field->shouldReceive('getErrors')->times(2);

		$decoratorClass = 'Bozboz\\Admin\\Decorators\\PageAdminDecorator';
		$decoratorMock = Mockery::mock($decoratorClass . '[getFields]', array($this->pageMock));
		$decoratorMock->shouldReceive('getFields')->andReturn(array($field));

		$this->app->instance($decoratorClass, $decoratorMock);

		$this->call('GET', 'admin/pages/create');
		$this->assertResponseOK();
		$this->assertViewHas('fields', array($field));
		$this->assertViewHas('model', $this->pageMock);
	}
}

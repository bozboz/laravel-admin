<?php namespace Bozboz\Admin\Tests\Controllers;

use Mockery, Auth;
use Bozboz\Admin\Models\User;
use Illuminate\Support\Collection;

class ModelAdminControllerTest extends \TestCase
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
		$this->app->instance($this->pageClass, $this->pageMock);
	}

	public function tearDown()
	{
		parent::tearDown();
		Mockery::close();
		Auth::logOut();
	}

	public function testInstancesArePassedToViewInIndexMethod()
	{
		$arrayAccessMock = Mockery::mock('ArrayAccess');

		$this->pageMock->shouldReceive('all')->once()->andReturn($arrayAccessMock);

		$this->call('GET', 'admin/pages');
		$this->assertResponseOK();
		$this->assertViewHas('instances', $arrayAccessMock);
	}

	public function testGetColumnsForInstances()
	{
		$pageInstanceMock = Mockery::mock($this->pageClass);
		$pageInstanceMock
			->shouldReceive('getAttribute')->times(3)->with('id')->andReturn(1)
			->shouldReceive('getAttribute')->once()->with('slug')->andReturn('test-slug')
			->shouldReceive('getAttribute')->once()->with('title')->andReturn('Test Title');

		$collection = new Collection(array($pageInstanceMock));

		$this->pageMock->shouldReceive('all')->once()->andReturn($collection);

		$this->call('GET', 'admin/pages');
		$this->assertResponseOK();
		$this->assertViewHas('instances', $collection);
		$this->assertViewHas('modelName', 'Page');

		$this->assertViewHas('columns', array(array(
			'id' => 1,
			'Front End URL' => '<a href="http://localhost/test-slug">http://localhost/test-slug</a>'
		)));
	}
}

<?php namespace Bozboz\Admin\Tests\Decorators;

use Mockery;
use Event;
use Bozboz\Admin\Tests\TestCase;
use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Illuminate\Support\Collection;


class ModelAdminDecoratorTest extends TestCase
{
	private $decorator;
	private $model;

	public function testGetModel()
	{
		$this->assertEquals($this->getMockedModel(), $this->getMockedDecorator()->getModel());
	}

	public function testBuildFields()
	{
		$decorator = $this->getMockedDecorator();
		$fieldsData = ['foo' => 'bar'];
		$decorator->shouldReceive('getFields')->once()->andReturn($fieldsData);
		Event::shouldReceive('fire')->once()->with('admin.fields.built', Mockery::type('array'));
		$this->assertEquals($decorator->buildFields(), $fieldsData);
	}

	private function getMockedDecorator($model = null)
	{
		if (is_null($model)) {
			$model = $this->getMockedModel();
		}

		if (empty($this->decorator)) {
			$this->decorator = Mockery::mock('Bozboz\Admin\Decorators\ModelAdminDecorator[]', [$model]);
		}

		return $this->decorator;
	}

	private function getMockedModel()
	{
		if (empty($this->model)) {
			$this->model = Mockery::mock('Bozboz\Admin\Models\Base');
		}

		return $this->model;
	}
}

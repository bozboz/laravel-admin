<?php namespace Bozboz\Admin\Tests\Decorators;

use Mockery;
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
		$fields = new \Illuminate\Support\Fluent($fieldsData);
		$decorator->shouldReceive('getFields')->once()->andReturn($fields);

		$this->assertEquals($decorator->buildFields(), ['attributes' => $fieldsData]);
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

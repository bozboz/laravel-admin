<?php namespace Bozboz\Admin\Controllers;

use View, Input, Redirect;
use BaseController;
use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Reports\Report;

abstract class ModelAdminController extends BaseController
{
	protected $decorator;
	protected $editView = 'admin::edit';
	protected $createView = 'admin::create';

	public function __construct(ModelAdminDecorator $decorator)
	{
		$this->beforeFilter('auth');
		$this->decorator = $decorator;
	}

	public function index()
	{
		$report = new Report($this->decorator);
		return $report->render(array('controller' => get_class($this)));
	}

	public function create()
	{
		return View::make($this->createView, array(
			'model' => $this->decorator->getModel(),
			'modelName' => class_basename(get_class($this->decorator->getModel())),
			'fields' => $this->decorator->buildFields(),
			'method' => 'POST',
			'action' => get_class($this) . '@store',
			'listingAction' => get_class($this) . '@index'
		));
	}

	public function store()
	{
		$modelInstance = $this->decorator->getModel()->newInstance();
		$validation = $modelInstance->getValidator();
		$input = Input::all();

		if ($validation->passesStore($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$this->decorator->updateSyncRelations($modelInstance, $input);
			$response = Redirect::action(get_class($this) . '@index');
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function edit($id)
	{
		$instance = $this->decorator->getModel()->find($id);
		$this->decorator->injectSyncRelations($instance);

		return View::make($this->editView, array(
			'model' => $instance,
			'modelName' => class_basename(get_class($this->decorator->getModel())),
			'fields' => $this->decorator->buildFields($instance),
			'action' => array(get_class($this) . '@update', $instance->id),
			'listingAction' => get_class($this) . '@index',
			'method' => 'PUT'
		));
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->getModel()->find($id);
		$validation = $this->decorator->getModel()->getValidator();
		$validation->updateUniques($modelInstance->getKey());
		$input = Input::all();

		if ($validation->passesEdit($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$this->decorator->updateSyncRelations($modelInstance, $input);
			$response = Redirect::action(get_class($this) . '@index');
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function destroy($id)
	{
		$instance = $this->decorator->getModel()->find($id);
		$instance->delete();

		return Redirect::action(get_class($this) . '@index');
	}

}

<?php namespace Bozboz\Admin\Controllers;

use View;
use Input;
use BaseController;
use Redirect;
use Route;
use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Facades\FieldMapper as FieldMapper;

abstract class ModelAdminController extends \BaseController
{
	protected $decorator;
	protected $listingView = 'admin::overview';
	protected $formView = 'admin::form';

	public function __construct(ModelAdminDecorator $decorator)
	{
		$this->decorator = $decorator;
	}

	/** 
	 * Temporary method that resolves the route based inspecting current route
	 * name and action.
	 */
	protected function getResourceRouteName($action = null)
	{
		$parts = explode('@', Route::currentRouteAction());
		$actionName = array_pop($parts);
		$currentRouteName = Route::currentRouteName();
		return str_replace($actionName, '', $currentRouteName) . $action;
	}

	protected function getColumnsForInstances(\ArrayAccess $instances)
	{
		$columns = array();
		foreach($instances as $instance) {
			$row = $this->decorator->getColumns($instance);
			$editRoute = $this->getResourceRouteName('edit');
			$label = $this->decorator->getLabel($instance);
			$row['Edit'] = link_to_route($editRoute, $label, array($instance->id));
			$columns[] = $row;
		}
		return $columns;
	}

	public function index()
	{
		$instances = $this->decorator->getListingModels();

		return View::make($this->listingView, array(
			'instances' => $instances,
			'prefix' => $this->getResourceRouteName(),
			'modelName' => class_basename(get_class($this->decorator->getModel())),
			'columns' => $this->getColumnsForInstances($instances)
		));
	}

	public function create()
	{
		return View::make($this->formView, array(
			'model' => $this->decorator->getModel(),
			'fields' => FieldMapper::getFields($this->decorator->getFields()),
			'route' => $this->getResourceRouteName('store'),
			'method' => 'POST'
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
			$response = Redirect::route($this->getResourceRouteName('index'));
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function edit($id)
	{
		$instance = $this->decorator->getModel()->find($id);

		return View::make($this->formView, array(
			'model' => $instance,
			'fields' => FieldMapper::getFields($this->decorator->getFields()),
			'route' => array($this->getResourceRouteName('update'), $instance->id),
			'method' => 'PUT'
		));
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->getModel()->find($id);
		$validation = $this->decorator->getModel()->getValidator();
		$validation->updateUniques($modelInstance->getId());
		$input = Input::all();

		if ($validation->passesEdit($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$response = Redirect::route($this->getResourceRouteName('index'));
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function destroy($id)
	{
		$instance = $this->decorator->getModel()->find($id);
		$instance->delete();

		return Redirect::route($this->getResourceRouteName('index'));
	}

}
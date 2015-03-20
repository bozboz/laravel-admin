<?php namespace Bozboz\Admin\Controllers;

use View, Input, Redirect, Session;
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
		$fields = $this->decorator->buildFields();

		return View::make($this->createView, array(
			'model' => $this->decorator->getModel(),
			'modelName' => $this->decorator->getHeading(),
			'fields' => $fields,
			'method' => 'POST',
			'action' => get_class($this) . '@store',
			'listingAction' => get_class($this) . '@index',
			'javascript' => $this->consolidateJavascript($fields)
		));
	}

	public function store()
	{
		$input = Input::all();
		$modelInstance = $this->decorator->newModelInstance($input);
		$validation = $modelInstance->getValidator();
		$input = $this->decorator->sanitiseInput($input);

		if ($validation->passesStore($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$this->decorator->updateSyncRelations($modelInstance, $input);
			$response = $this->getStoreResponse($modelInstance);
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function edit($id)
	{
		$instance = $this->decorator->findInstance($id);
		$this->decorator->injectSyncRelations($instance);
		$fields = $this->decorator->buildFields($instance);

		return View::make($this->editView, array(
			'model' => $instance,
			'modelName' => $this->decorator->getHeading(),
			'fields' => $fields,
			'action' => array(get_class($this) . '@update', $instance->id),
			'listingAction' => get_class($this) . '@index',
			'method' => 'PUT',
			'javascript' => $this->consolidateJavascript($fields)
		));
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->findInstance($id);
		$validation = $modelInstance->getValidator();
		$validation->updateUniques($modelInstance->getKey());
		$input = $this->decorator->sanitiseInput(Input::all());

		if ($validation->passesEdit($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$this->decorator->updateSyncRelations($modelInstance, $input);
			$response = $this->getUpdateResponse($modelInstance);
			Session::flash('model.updated', sprintf('Successfully updated "%s"', $this->decorator->getLabel($modelInstance)));
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function destroy($id)
	{
		$instance = $this->decorator->findInstance($id);

		$instance->delete();

		return $this->getSuccessResponse($instance);
	}

	protected function consolidateJavascript($fields)
	{
		$javascript = '';
		foreach ($fields as $field) {
			$fieldJavascript = $field->getJavascript();
			if (!is_null($fieldJavascript)) {
				$javascript .= '<script type="text/javascript">' . $fieldJavascript . '</script>' . PHP_EOL;
			}
		}

		return $javascript;
	}

	protected function getStoreResponse($instance)
	{
		return $this->getSuccessResponse($instance);
	}

	protected function getUpdateResponse($instance)
	{
		return $this->getSuccessResponse($instance);
	}

	/**
	 * The Response after a successful create/edit/delete action.
	 */
	protected function getSuccessResponse($instance)
	{
		return Redirect::action(get_class($this) . '@index');
	}
}

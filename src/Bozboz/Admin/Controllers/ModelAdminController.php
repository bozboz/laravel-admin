<?php namespace Bozboz\Admin\Controllers;

use Input, Redirect, URL, View;
use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Reports\Report;
use Illuminate\Routing\Controller;

abstract class ModelAdminController extends Controller
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
	    $instance = $this->decorator->newModelInstance();
	    return $this->renderCreateFormFor($instance);
	}

	protected function renderCreateFormFor($instance)
	{
	    $fields = $this->decorator->buildFields($instance);

	    return View::make($this->createView, array(
	        'model' => $instance,
	        'modelName' => $this->decorator->getHeading(),
	        'fields' => $fields,
	        'method' => 'POST',
	        'action' => get_class($this) . '@store',
	        'listingUrl' => $this->getListingUrl($instance),
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
			$this->decorator->updateRelations($modelInstance, $input);
			$response = $this->reEdit($modelInstance) ?: $this->getStoreResponse($modelInstance);
			$response->with('model.created', sprintf(
				'Successfully created "%s"',
				$this->decorator->getLabel($modelInstance)
			));
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function edit($id)
	{
		$instance = $this->decorator->findInstanceOrFail($id);
		$this->decorator->injectRelations($instance);
		$fields = $this->decorator->buildFields($instance);

		return View::make($this->editView, array(
			'model' => $instance,
			'modelName' => $this->decorator->getHeading(),
			'fields' => $fields,
			'action' => array(get_class($this) . '@update', $instance->id),
			'listingUrl' => $this->getListingUrl($instance),
			'method' => 'PUT',
			'javascript' => $this->consolidateJavascript($fields)
		));
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->findInstanceOrFail($id);
		$validation = $modelInstance->getValidator();
		$validation->updateUniques($modelInstance->getKey());
		$input = $this->decorator->sanitiseInput(Input::all());

		if ($validation->passesUpdate($input)) {
			$modelInstance->fill($input);
			$modelInstance->save();
			$this->decorator->updateRelations($modelInstance, $input);
			$response = $this->reEdit($modelInstance) ?: $this->getUpdateResponse($modelInstance);
			$response->with('model.updated', sprintf(
				'Successfully updated "%s"',
				$this->decorator->getLabel($modelInstance)
			));
		} else {
			$response = Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		return $response;
	}

	public function destroy($id)
	{
		$instance = $this->decorator->findInstanceOrFail($id);

		$instance->delete();

		return Redirect::back()->with('model.deleted', sprintf(
			'Successfully deleted "%s"',
			$this->decorator->getLabel($instance)
		));
	}

	protected function reEdit($instance)
	{
		if (Input::has('after_save') && Input::get('after_save') === 'continue') {
			return Redirect::action(get_class($this) . '@edit', $instance->getKey());
		}
	}

	protected function consolidateJavascript($fields)
	{
		$javascript = [];
		foreach ($fields as $field) {
			$javascript[] = $field->getJavascript();
		}

		return '<script>' . implode(PHP_EOL, array_filter($javascript)) . '</script>' . PHP_EOL;
	}

	/**
	 * The response after successfully storing an instance
	 */
	protected function getStoreResponse($instance)
	{
		return $this->getSuccessResponse($instance);
	}

	/**
	 * The response after successfully updating an instance
	 */
	protected function getUpdateResponse($instance)
	{
		return $this->getSuccessResponse($instance);
	}

	/**
	 * The generic response after a successful store/update action.
	 */
	protected function getSuccessResponse($instance)
	{
		return Redirect::action(get_class($this) . '@index');
	}

	protected function getListingUrl($instance)
	{
		return URL::action(get_class($this) . '@index');
	}
}

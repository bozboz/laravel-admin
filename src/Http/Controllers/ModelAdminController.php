<?php namespace Bozboz\Admin\Http\Controllers;

use App, Input, Redirect, URL, View;
use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Reports\Report;
use Bozboz\Permissions\RuleStack;
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
		if ( ! $this->canView()) App::abort(403);

		$report = $this->getListingReport();
		$params = $this->getReportParams();

		return $report->render($params);
	}

	/**
	 * Get an instance of a report to display the model listing
	 *
	 * @return Bozboz\Admin\Reports\Report
	 */
	protected function getListingReport()
	{
		return new Report($this->decorator);
	}

	/**
	 * Return an array of params the report requires to render
	 *
	 * @return array
	 */
	protected function getReportParams()
	{
		return [
			'createAction' => $this->getActionName('create'),
			'editAction' => $this->getActionName('edit'),
			'destroyAction' => $this->getActionName('destroy'),
			'canCreate' => [$this, 'canCreate'],
			'canEdit' => [$this, 'canEdit'],
			'canDelete' => [$this, 'canDestroy'],
		];
	}

	public function create()
	{
		if ( ! $this->canCreate()) App::abort(403);

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
	        'action' => $this->getActionName('store'),
	        'listingUrl' => $this->getListingUrl($instance),
	        'javascript' => $this->consolidateJavascript($fields)
	    ));
	}

	public function store()
	{
		$input = Input::except('after_save');
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
		if ( ! $this->canEdit((int)$id)) App::abort(403);

		$instance = $this->decorator->findInstance($id);
		$this->decorator->injectRelations($instance);
		$fields = $this->decorator->buildFields($instance);

		return View::make($this->editView, array(
			'model' => $instance,
			'modelName' => $this->decorator->getHeading(),
			'fields' => $fields,
			'action' => array($this->getActionName('update'), $instance->id),
			'listingUrl' => $this->getListingUrl($instance),
			'method' => 'PUT',
			'javascript' => $this->consolidateJavascript($fields)
		));
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->findInstance($id);
		$validation = $modelInstance->getValidator();
		$validation->updateUniques($modelInstance->getKey());
		$input = $this->decorator->sanitiseInput(Input::except('after_save'));

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
		if ( ! $this->canDestroy((int)$id)) App::abort(403);

		$instance = $this->decorator->findInstance($id);

		$instance->delete();

		return Redirect::back()->with('model.deleted', sprintf(
			'Successfully deleted "%s"',
			$this->decorator->getLabel($instance)
		));
	}

	protected function reEdit($instance)
	{
		if (Input::has('after_save') && Input::get('after_save') === 'continue') {
			$reportParams = $this->getReportParams();
			if (array_key_exists('editAction', $reportParams)) {
				return Redirect::action($reportParams['editAction'], $instance->getKey());
			}
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
		return Redirect::action($this->getActionName('index'));
	}

	protected function getListingUrl($instance)
	{
		return URL::action($this->getActionName('index'));
	}

	protected function getActionName($action)
	{
		return '\\' . get_class($this) . '@' . $action;
	}

	public function canView()
	{
		return $this->isAllowed('view');
	}

	public function canCreate()
	{
		return $this->isAllowed('create');
	}

	public function canEdit($id)
	{
		return $this->isAllowed('edit', $id);
	}

	public function canDestroy($id)
	{
		return $this->isAllowed('delete', $id);
	}

	private function isAllowed($action, $id = null)
	{
		$stack = new RuleStack;

		$stack->add($action . '_anything');

		$this->{$action . 'Permissions'}($stack, $id);

		return $stack->isAllowed();
	}

	protected function viewPermissions($stack) { }

	protected function createPermissions($stack) { }

	protected function editPermissions($stack, $id) { }

	protected function deletePermissions($stack, $id) { }
}

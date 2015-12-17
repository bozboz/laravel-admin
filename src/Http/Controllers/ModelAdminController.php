<?php namespace Bozboz\Admin\Http\Controllers;

use App, Input, Redirect, URL, View;
use Bozboz\Admin\Base\ModelAdminDecorator;
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
			'createParams' => [],
			'editAction' => $this->getActionName('edit'),
			'destroyAction' => $this->getActionName('destroy'),
			'canCreate' => [$this, 'canCreate'],
			'canEdit' => [$this, 'canEdit'],
			'canDelete' => [$this, 'canDestroy'],
		];
	}

	public function create()
	{
		$instance = $this->decorator->newModelInstance();

		if ( ! $this->canCreate($instance)) App::abort(403);

		return $this->renderFormFor($instance, $this->createView, 'POST', 'store');
	}

	public function store()
	{
		$input = Input::except('after_save');
		$modelInstance = $this->decorator->newModelInstance($input);
		$input[$modelInstance->getKeyName()] = 'NULL';
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
		$instance = $this->decorator->findInstance($id);

		$this->decorator->injectRelations($instance);

		if ( ! $this->canEdit($instance)) App::abort(403);

		return $this->renderFormFor($instance, $this->editView, 'PUT', 'update');
	}

	public function update($id)
	{
		$modelInstance = $this->decorator->findInstance($id);
		$validation = $modelInstance->getValidator();
		$input = $this->decorator->sanitiseInput(Input::except('after_save'));
		$input[$modelInstance->getKeyName()] = $modelInstance->getKey();

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
		$instance = $this->decorator->findInstance($id);

		if ( ! $this->canDestroy($instance)) App::abort(403);

		$instance->delete();

		return Redirect::back()->with('model.deleted', sprintf(
			'Successfully deleted "%s"',
			$this->decorator->getLabel($instance)
		));
	}

	protected function renderFormFor($instance, $view, $method, $action)
	{
		$fields = $this->decorator->buildFields($instance);

		return View::make($view, array(
			'model' => $instance,
			'modelName' => $this->decorator->getHeading(),
			'fields' => $fields,
			'method' => $method,
			'action' => array($this->getActionName('update'), $instance->id),
			'listingUrl' => $this->getListingUrl($instance),
			'javascript' => $this->consolidateJavascript($fields)
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

	public function canCreate($instance = null)
	{
		return $this->isAllowed('create', $instance);
	}

	public function canEdit($instance)
	{
		return $this->isAllowed('edit', $instance);
	}

	public function canDestroy($instance)
	{
		return $this->isAllowed('delete', $instance);
	}

	private function isAllowed($action, $instance = null)
	{
		$stack = new RuleStack;

		$stack->add($action . '_anything');

		$this->{$action . 'Permissions'}($stack, $instance);

		return $stack->isAllowed();
	}

	protected function viewPermissions($stack) { }

	protected function createPermissions($stack, $instance) { }

	protected function editPermissions($stack, $instance) { }

	protected function deletePermissions($stack, $instance) { }
}

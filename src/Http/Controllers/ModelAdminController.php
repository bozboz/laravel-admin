<?php namespace Bozboz\Admin\Http\Controllers;

use App, Input, Redirect, URL, View;
use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Reports\Actions\CreateAction;
use Bozboz\Admin\Reports\Actions\DestroyAction;
use Bozboz\Admin\Reports\Actions\EditAction;
use Bozboz\Admin\Reports\Report;
use Bozboz\Permissions\RuleStack;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

abstract class ModelAdminController extends Controller
{
	protected $decorator;
	protected $editView = 'admin::edit';
	protected $createView = 'admin::create';

	public function __construct(ModelAdminDecorator $decorator)
	{
		$this->middleware('auth');
		$this->decorator = $decorator;
	}

	public function index()
	{
		if ( ! $this->canView()) App::abort(403);

		$report = $this->getListingReport();

		$report->setReportActions($this->getReportActions());
		$report->setRowActions($this->getRowActions());

		return $report->render(
			$this->getReportParams()
		);
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
	 * @deprecated Return an array of params the report requires to render
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

	/**
	 * Return an array of actions the report can perform
	 *
	 * @return array
	 */
	protected function getReportActions()
	{
		return [
			'create' => new CreateAction(
				$this->getActionName('create'),
				[$this, 'canCreate']
			)
		];
	}

	/**
	 * Return an array of actions each row can perform
	 *
	 * @return array
	 */
	protected function getRowActions()
	{
		return [
			'edit' => new EditAction(
				$this->getActionName('edit'),
				[$this, 'canEdit']
			),
			'destroy' => new DestroyAction(
				$this->getActionName('destroy'),
				[$this, 'canDestroy']
			)
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

		if ($validation->failsStore($input)) {
			return Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		$this->saveInTransaction($modelInstance, $input);

		$response = $this->reEdit($modelInstance) ?: $this->getStoreResponse($modelInstance);
		$response->with('model.created', sprintf(
			'Successfully created "%s"',
			$this->decorator->getLabel($modelInstance)
		));

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

		if ($validation->failsUpdate($input)) {
			return Redirect::back()->withErrors($validation->getErrors())->withInput();
		}

		$this->saveInTransaction($modelInstance, $input);

		$response = $this->reEdit($modelInstance) ?: $this->getUpdateResponse($modelInstance);
		$response->with('model.updated', sprintf(
			'Successfully updated "%s"',
			$this->decorator->getLabel($modelInstance)
		));

		return $response;
	}

	protected function saveInTransaction($modelInstance, $input)
	{
		DB::transaction(function() use ($modelInstance, $input) {
			$this->save($modelInstance, $input);
		});
	}

	protected function save($modelInstance, $input)
	{
		$modelInstance->fill($input);
		$modelInstance->save();
		$this->decorator->updateRelations($modelInstance, $input);
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
			'action' => [$this->getActionName($action), $instance->id],
			'listingUrl' => $this->getListingUrl($instance),
			'javascript' => $this->consolidateJavascript($fields)
		));
	}

	protected function reEdit($instance)
	{
		if (Input::has('after_save') && Input::get('after_save') === 'continue') {
			$actions = $this->getRowActions();
			if (array_key_exists('edit', $actions)) {
				return Redirect::to($actions['edit']->getUrl($instance->getKey()));
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

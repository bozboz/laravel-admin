<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Base\ModelAdminDecorator;
use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Link;
use Bozboz\Admin\Reports\PaginatedReport;
use Bozboz\Admin\Reports\Report;
use Bozboz\Permissions\RuleStack;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use URL;

abstract class ModelAdminController extends Controller
{
	protected $decorator;
	protected $actions;
	protected $useActions = false;
	protected $editView = 'admin::edit';
	protected $createView = 'admin::create';

	public function __construct(ModelAdminDecorator $decorator)
	{
		$this->middleware('auth');
		$this->decorator = $decorator;
		$this->actions = app('admin.actions');
	}

	public function index()
	{
		if ( ! $this->canView()) App::abort(403);

		$report = $this->getListingReport();

		$report->injectValues(Input::all());

		if ( ! $this->useActions) return $report->render($this->getReportParams());

		$report->setReportActions($this->getReportActions());
		$report->setRowActions($this->getRowActions());

		return $report->render();
	}

	/**
	 * Get an instance of a report to display the model listing
	 *
	 * @return Bozboz\Admin\Reports\Report
	 */
	protected function getListingReport()
	{
		if ($this->decorator->isSortable()) {
			return new Report($this->decorator);
		} else {
			return new PaginatedReport($this->decorator, Input::get('per-page'));
		}
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
			$this->actions->create(
				$this->getActionName('create'),
				[$this, 'canCreate'],
				'New ' . $this->decorator->getHeading()
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
			$this->actions->edit(
				$this->getEditAction(),
				[$this, 'canEdit']
			),
			$this->actions->destroy(
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
			'modelName' => $this->decorator->getHeadingForInstance($instance),
			'fields' => $fields,
			'method' => $method,
			'action' => [$this->getActionName($action), $instance->id],
			'actions' => collect($this->getFormActions($instance)),
		));
	}

	protected function getFormActions($instance)
	{
		return [
			$this->actions->submit('Save and Exit', 'fa fa-save', [
				'name' => 'after_save',
				'value' => 'exit',
			]),
			$this->actions->submit('Save', 'fa fa-save', [
				'name' => 'after_save',
				'value' => 'continue',
			]),
			$this->actions->custom(
				new Link($this->getListingAction($instance), 'Back to listing', 'fa fa-list-alt', [
					'class' => 'btn-default pull-right',
				]),
				new IsValid([$this, 'canView'])
			),
		];
	}

	protected function reEdit($instance)
	{
		if (Input::has('after_save') && Input::get('after_save') === 'continue') {
			$actions = $this->getRowActions();
			return Redirect::action($this->getEditAction(), $instance->id);
		}
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

	/**
	 * Return the listing URL for the resource. Use alternative
	 * `getListingAction` method if needed.
	 *
	 * @deprecated
	 *
	 * @param  Bozboz\Admin\Base\Model  $instance
	 * @return string
	 */
	protected function getListingUrl($instance)
	{
		return action($this->getListingAction($instance));
	}

	protected function getListingAction($instance)
	{
		return $this->getActionName('index');
	}

	protected function getEditAction()
	{
		return $this->getActionName('edit');
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

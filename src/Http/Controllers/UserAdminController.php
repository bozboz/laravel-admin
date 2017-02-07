<?php

namespace Bozboz\Admin\Http\Controllers;

use Auth;
use Bozboz\Admin\Http\Controllers\RemindersController;
use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Form;
use Bozboz\Admin\Users\User;
use Bozboz\Admin\Users\UserAdminDecorator;
use Bozboz\Permissions\Facades\Gate;
use Bozboz\Permissions\RuleStack;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Input;
use Session;

class UserAdminController extends ModelAdminController
{
	protected $useActions = true;

	public function __construct(UserAdminDecorator $user)
	{
		parent::__construct($user);
	}

	public function loginAs(Store $session, $id)
	{
		if ( ! $this->canLoginAs()) return abort('403');

		$session->put('previous_user', Auth::user()->id);

		$user = Auth::user()->find($id);
		Auth::login($user);

		return redirect('admin');
	}

	public function sendReset(RemindersController $remind, User $user)
	{
		if ( ! $this->canRemind()) return abort('403');

		Input::merge(['email' => $user->email]);
		return $remind->postRemind();
	}

	public function previousUser(Store $session)
	{
		if ( ! $session->has('previous_user')) return abort('403');

		$user = Auth::user()->find($session->pull('previous_user'));

		Auth::login($user);

		return redirect()->route('admin.users.index');
	}

	public function getRowActions()
	{
		return array_merge([
			$this->actions->custom(
				new Form($this->getActionName('loginAs'), 'Login As', 'fa fa-sign-in', [
					'class' => 'btn-primary btn-sm'
				]),
				new IsValid([$this, 'canLoginAs'])
			),
			$this->actions->custom(
				new Form($this->getActionName('sendReset'), 'Send Reset', 'fa fa-lock', [
					'class' => 'btn-default btn-sm'
				]),
				new IsValid([$this, 'canRemind'])
			),
		], parent::getRowActions());
	}

	public function canLoginAs()
	{
		return Gate::allows('login_as');
	}

	public function canRemind()
	{
		return $this->canView();
	}

	public function viewPermissions($stack)
	{
		$stack->add('view_users');
	}

	public function createPermissions($stack, $instance)
	{
		$stack->add('create_user', $instance);
	}

	public function editPermissions($stack, $instance)
	{
		$stack->add('edit_user', $instance);
		$stack->add('edit_user_for_role', $instance->role);
		$stack->add('edit_profile', $instance);
	}

	public function deletePermissions($stack, $instance)
	{
		$stack->add('delete_user', $instance);
		$stack->add('delete_user_for_role', $instance->role);
	}
}

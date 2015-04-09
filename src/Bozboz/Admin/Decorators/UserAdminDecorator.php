<?php namespace Bozboz\Admin\Decorators;

use Illuminate\Database\Eloquent\Builder;

use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Fields\EmailField;
use Bozboz\Admin\Fields\HiddenField;
use Bozboz\Admin\Fields\PasswordField;
use Bozboz\Admin\Models\User;

class UserAdminDecorator extends ModelAdminDecorator
{
	public function __construct(User $user)
	{
		parent::__construct($user);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id,
			'email' => $instance->email
		);
	}

	public function modifyListingQuery(Builder $query)
	{
		$query->where('is_admin', true)->latest();
	}

	public function getLabel($instance)
	{
		return $instance->first_name . ' ' . $instance->last_name;
	}

	public function getFields($instance)
	{
		return array_filter([
			new TextField('first_name'),
			new TextField('last_name'),
			new EmailField('email'),
			$instance->exists ? null : new PasswordField('password'),
			new HiddenField('is_admin', true)
		]);
	}
}

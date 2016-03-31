<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Admin\Services\Validators\UserValidator;
use Bozboz\Permissions\UserInterface as Permissions;
use Bozboz\Permissions\EloquentPermissionsTrait;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    CanResetPasswordContract,
                                    Permissions
{
    use Authenticatable, CanResetPassword, EloquentPermissionsTrait;

	/**
	 * The Model validator
	 *
	 * @var UserValidator
	 */
	protected $validator;

	/**
	 * Blacklisted mass assignment attributes
	 */
	protected $guarded = array('id');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * {@inheritdoc}
	 */
	public function getValidator()
	{
		if (empty($this->validator)) {
			$this->validator = new UserValidator();
		}

		return $this->validator;
	}

	public function setPasswordAttribute($value)
	{
		if (!empty($value)) {
			$this->attributes['password'] = Hash::make($value);
		}
	}
}

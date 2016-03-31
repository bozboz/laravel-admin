<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Admin\Services\Validators\UserValidator;
use Bozboz\Permissions\Permission;
use Bozboz\Permissions\UserInterface as Permissions;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    CanResetPasswordContract,
                                    Permissions
{
    use Authenticatable, CanResetPassword;

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

	public function getPermissions()
	{
		return $this->permissions;
	}

	public function permissions()
	{
		return $this->hasMany(Permission::class, 'user_id');
	}

	public function grantPermission($action, $param = null)
	{
		$attributes = compact('action', 'param');

		if ($this->permissions()->where($attributes)->count() === 0) {
			$this->permissions()->create($attributes);
		}
	}

	public function grantWildcard()
	{
		$this->grantPermission(Permission::WILDCARD);
	}

	public function revokePermission($action, $param = null)
	{
		$this->permissions()->whereAction($action)->whereParam($param)->delete();
	}

	public function scopeHasPermission($builder, $action)
	{
		$builder->whereHas('permissions', function($q) use ($action) {
			$q->where(function($q) use ($action) {
				$q->where('action', $action)
				  ->orWhere('action', Permission::WILDCARD);
			});
		});
	}

	public function scopeDoesntHavePermission($builder, $action)
	{
		$builder->whereDoesntHave('permissions', function($q) use ($action) {
			$q->where(function($q) use ($action) {
				$q->where('action', $action)
				  ->orWhere('action', Permission::WILDCARD);
			});
		});
	}
}

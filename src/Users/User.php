<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Permissions\UserInterface as HasPermissions;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    CanResetPasswordContract,
                                    HasPermissions,
                                    UserInterface
{
    use Authenticatable, CanResetPassword, Validates, HashesPassword;

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

	public function getPermissions()
	{
		return $this->role ? $this->role->permissions : collect();
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function scopeHasPermission($builder, $action)
	{
		$builder->whereHas('role', function($query) use ($action) {
			$query->hasPermission($action);
		});
	}

	public function scopeDoesntHavePermission($builder, $action)
	{
		$builder->whereHas('role', function($query) use ($action) {
			$query->doesnthavePermission($action);
		});
	}
}

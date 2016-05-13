<?php

namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Permissions\EloquentPermissionsTrait;
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
    use Authenticatable, CanResetPassword, EloquentPermissionsTrait, Validates,
        HashesPassword;

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
}

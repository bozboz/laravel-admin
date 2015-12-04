<?php namespace Bozboz\Admin\Users;

use Bozboz\Admin\Base\Model;
use Bozboz\Admin\Services\Validators\UserValidator;
use Bozboz\Permissions\Permission;
use Bozboz\Permissions\UserInterface as Permissions;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    Permissions
{
    use Authenticatable, Authorizable, CanResetPassword;

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

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function setPasswordAttribute($value)
	{
		if (!empty($value)) {
			$this->attributes['password'] = Hash::make($value);
		}
	}

	public function getRememberToken()
	{
	return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
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
}

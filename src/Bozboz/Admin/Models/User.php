<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\UserValidator;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Base implements AuthenticatableContract,
                                   AuthorizableContract,
                                   CanResetPasswordContract
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

}

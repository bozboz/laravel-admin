<?php namespace Bozboz\Admin\Models;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Hash;
use Bozboz\Admin\Services\Validators\UserValidator;
/*

##########

Add Migration to remove "username" field - Sam (2014-02-19)

##########*/

class User extends Base implements UserInterface, RemindableInterface
{
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

	/*
	 * NOTE:
	 * This will hash before validation occurs, rendering validation pointless.
	 * Need to solve.
	 */
	public function setPasswordAttribute($value)
	{
		if (!empty($value)) {
			$this->attributes['password'] = Hash::make($value);
		}
	}
}

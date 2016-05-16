<?php

namespace Bozboz\Admin\Users;

use Illuminate\Support\Facades\Hash;

trait HashesPassword
{
	/**
	 * If a password is set, automatically hash it
	 */
	public function setPasswordAttribute($value)
	{
		if (!empty($value)) {
			$this->attributes['password'] = Hash::make($value);
		}
	}
}

<?php namespace Bozboz\Admin\Models;

use Eloquent;

abstract class Base extends Eloquent
{
	public function getId()
	{
		$primaryKey = $this->getKeyName();
		return $this->$primaryKey;
	}
}
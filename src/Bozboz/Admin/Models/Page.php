<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\PageValidator;

class Page extends Base
{
	protected $fillable = array('title', 'slug', 'description');

	public function getValidator()
	{
		return new PageValidator;
	}
}
<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\PageValidator;
use Bozboz\Admin\Models\Sortable;

class Page extends Base implements Sortable
{
	protected $fillable = array('title', 'slug', 'description', 'template');

	public function getValidator()
	{
		return new PageValidator;
	}

	public function sortBy()
	{
		return 'sorting';
	}
}

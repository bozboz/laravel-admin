<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\PageValidator;
use Bozboz\Admin\Models\Sortable;

class Page extends Base implements Sortable
{
	protected $fillable = array('title', 'slug', 'description', 'template', 'parent_id');

	public function getValidator()
	{
		return new PageValidator;
	}

	public function sortBy()
	{
		return 'sorting';
	}

	public function children()
	{
		return $this->hasMany(get_class($this), 'parent_id');
	}

	public function parent()
	{
		return $this->belongsTo(get_class($this));
	}

	public function scopeTopLevel()
	{
		return $this->where('parent_id', 0);
	}
}

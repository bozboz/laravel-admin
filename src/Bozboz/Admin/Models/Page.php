<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\PageValidator;
use Bozboz\Admin\Models\Sortable;
use Bozboz\Admin\Traits\DynamicSlugTrait;

class Page extends Base implements Sortable
{
	use DynamicSlugTrait;

	protected $guarded = array('id', 'media', 'files');

	protected function getSlugSourceField()
	{
		return 'title';
	}

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

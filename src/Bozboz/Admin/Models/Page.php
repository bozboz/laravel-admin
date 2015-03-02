<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Services\Validators\PageValidator;
use Bozboz\Admin\Models\Sortable;
use Bozboz\Admin\Traits\DynamicSlugTrait;
use Bozboz\MediaLibrary\Models\Media;

class Page extends Base implements Sortable
{
	use DynamicSlugTrait;

	protected $fillable = [
		'title',
		'slug',
		'description',
		'redirect_to_id',
		'html_title',
		'meta_description',
		'sorting',
		'template',
		'parent_id',
		'show_in_main_menu',
		'show_in_footer_menu',
		'status'
	];

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

	public function media()
	{
		return Media::forModel($this);
	}
}

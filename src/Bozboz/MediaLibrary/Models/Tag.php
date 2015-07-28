<?php

namespace Bozboz\MediaLibrary\Models;

use Bozboz\Admin\Models\Base;

class Tag extends Base
{
	protected $table = 'media_tags';

	protected $fillable = ['name'];

	public function media()
	{
		return $this->belongsToMany('Bozboz\MediaLibrary\Models\Media', 'media_mm_tags');
	}

	public function getValidator()
	{

	}
}

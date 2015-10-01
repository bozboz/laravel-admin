<?php

namespace Bozboz\Admin\Models;

class Tag extends Base
{
	protected $table = 'media_tags';

	protected $fillable = ['name'];

	public function media()
	{
		return $this->belongsToMany('Bozboz\Admin\Models\Media', 'media_mm_tags');
	}

	public function getValidator()
	{

	}
}

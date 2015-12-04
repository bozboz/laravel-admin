<?php

namespace Bozboz\Admin\Media;

use Bozboz\Admin\Base\Model;

class Tag extends Model
{
	protected $table = 'media_tags';

	protected $fillable = ['name'];

	public function media()
	{
		return $this->belongsToMany(Media::class, 'media_mm_tags');
	}

	public function getValidator()
	{

	}
}

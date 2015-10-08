<?php namespace Bozboz\Admin\Traits;

use Bozboz\Admin\Models\Media;

Trait MediableTrait
{
	public function media()
	{
		return Media::forModel($this);
	}
}

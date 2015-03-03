<?php namespace Bozboz\MediaLibrary\Models;

Trait MediableTrait
{
	public function media()
	{
		return Media::forModel($this);
	}
}

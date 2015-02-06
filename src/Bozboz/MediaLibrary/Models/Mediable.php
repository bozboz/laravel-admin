<?php namespace Bozboz\MediaLibrary\Models;

Trait Mediable
{
	public function media()
	{
		return Media::forModel($this);
	}
}
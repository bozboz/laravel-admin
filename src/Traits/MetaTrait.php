<?php namespace Bozboz\Admin\Traits;

trait MetaTrait
{
	public function getTitle()
	{
		return $this->meta_title;
	}

	public function getDescription()
	{
		return $this->meta_description;
	}
}

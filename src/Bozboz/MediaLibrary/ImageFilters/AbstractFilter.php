<?php namespace Bozboz\MediaLibrary\ImageFilters;

abstract class AbstractFilter
{
	abstract public function recipe();
	
	public function __toString()
	{
		return $this->recipe();
	}
}

<?php namespace Bozboz\MediaLibrary\ImageFilters;

class Resize extends AbstractFilter
{
	private $width;
	private $height;

	public function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}

	public function recipe()
	{
		return sprintf('1/%d/%d',
			$this->width,
			$this->height
		);
	}
}

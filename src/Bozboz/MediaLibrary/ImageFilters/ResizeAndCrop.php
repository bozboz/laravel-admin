<?php namespace Bozboz\MediaLibrary\ImageFilters;

class ResizeAndCrop extends AbstractFilter
{
	private $width;
	private $height;
	private $gravity;

	public function __construct($width, $height, $gravity = 5)
	{
		$this->width = $width;
		$this->height = $height;
		$this->gravity = $gravity;
	}

	public function recipe()
	{
		return sprintf('2/%d/%d/%d',
			$this->width,
			$this->height,
			$this->gravity
		);
	}
}

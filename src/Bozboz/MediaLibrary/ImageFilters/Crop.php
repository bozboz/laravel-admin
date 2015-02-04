<?php namespace Bozboz\MediaLibrary\ImageFilters;

class Crop extends AbstractFilter
{
	private $width;
	private $height;
	private $gravity;
	private $colour;

	public function __construct($width, $height, $gravity = 5, $colour = null)
	{
		$this->width = $width;
		$this->height = $height;
		$this->gravity = $gravity;
		$this->colour = $colour;
	}

	public function recipe()
	{
		$recipe = sprintf('3/%d/%d/%d',
			$this->width,
			$this->height,
			$this->gravity,
			$this->colour
		);

		if ($this->colour) {
			$recipe .= sprintf('/[%s]', $this->colour);
		}
		
		return $recipe;
	}
}

<?php namespace Bozboz\Admin\Traits;

use Str;
use Exception;

trait DynamicSlugTrait
{
	/**
	 * @return string Attribute from which to derive the slug.
	 */
	abstract protected function getSlugSourceField();

	/**
	 * @return string Attribute being used as the slug.
	 */
	protected function getSlugField()
	{
		return 'slug';
	}

	/**
	 * Dynamically set the value of the model's slug.
	 */
	public function save(array $options = [])
	{
		if (is_null($this->created_at)) {
			$slugField = $this->getSlugField();
			$slugSourceField = $this->getSlugSourceField();
			$this->$slugField = Str::slug($this->$slugSourceField);
		}

		parent::save($options);
	}
}

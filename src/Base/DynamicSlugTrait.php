<?php

namespace Bozboz\Admin\Base;

use Str;

trait DynamicSlugTrait
{
	/**
	 * Attribute to generate the slug from
	 *
	 * @return string
	 */
	abstract protected function getSlugSourceField();

	/**
	 * Attribute to store the slug in slug.
	 *
	 * @return string
	 */
	protected function getSlugField()
	{
		return 'slug';
	}

	/**
	 * Register the model's creating event
	 */
	public static function bootDynamicSlugTrait()
	{
		static::creating([new static, 'generateSlug']);
	}

	/**
	 * Set the $instance's slug field attribute to a generated slug based on the
	 * model's source slug field.
	 *
	 * @param  mixed  $instance
	 * @return void
	 */
	public function generateSlug($instance)
	{
		$sourceField = $instance->getSlugSourceField();
		$slugField = $this->getSlugField();

		$source = $instance->$sourceField;

		if ( ! empty($source) && empty($instance->$slugField)) {
			$slug = str_slug($source);
			$instance->$slugField = $this->generateUniqueSlug($slug);
		}
	}

	/**
	 * If $slug already exists, increment a number until unique.
	 *
	 * @param  string  $slug
	 * @return string
	 */
	protected function generateUniqueSlug($slug)
	{
		$newSlug = $slug;

		$unique = false;
		$i = 0;

		while ( ! $unique) {
			$exists = static::where($this->getSlugField(), '=', $newSlug)->count();
			if ($exists > 0) {
				$i++;
				$newSlug = $slug . '-' . $i;
			} else {
				$unique = true;
			}
		}

		return $newSlug;
	}
}

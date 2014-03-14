<?php namespace Bozboz\Admin\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class FieldMapper extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'field.mapper'; }

}
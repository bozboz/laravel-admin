<?php namespace Bozboz\Admin\Models;

use Bozboz\Admin\Traits\SanitisesInputTrait;
use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model implements BaseInterface
{
	use SanitisesInputTrait;

	/**
	 * @var array
	 */
	protected $nullable = [];
}

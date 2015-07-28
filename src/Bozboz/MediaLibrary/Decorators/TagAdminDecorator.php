<?php

namespace Bozboz\MediaLibrary\Decorators;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\MediaLibrary\Models\Tag;

class TagAdminDecorator extends ModelAdminDecorator
{
	public function __construct(Tag $tag)
	{
		parent::__construct($tag);
	}

	public function getLabel($instance)
	{
		return $instance->name;
	}

	public function getFields($instance)
	{
		return [];
	}
}

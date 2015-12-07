<?php

namespace Bozboz\Admin\Media;

use Bozboz\Admin\Base\ModelAdminDecorator;

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

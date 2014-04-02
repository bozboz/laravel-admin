<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\Page;
use Illuminate\Support\Facades\HTML;

class PageAdminDecorator extends ModelAdminDecorator
{
	public function __construct(Page $page)
	{
		parent::__construct($page);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id,
			'Front End URL' => HTML::link($instance->slug)
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('title');
	}

	public function getFields()
	{
		return array(
			new \Bozboz\Admin\Fields\TextField(array('name' => 'title')),
			new \Bozboz\Admin\Fields\TextField(array('name' => 'slug')),
			new \Bozboz\Admin\Fields\TextareaField(array('name' => 'description'))
		);
	}

}

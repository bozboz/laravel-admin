<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\Page;
use ArrayAccess;

class PageAdminDecorator extends ModelAdminDecorator
{
	protected static $fields = array(
		'title' => array(
			'label' => 'Title',
			'type' => 'text',
		),
		'slug' => array(
			'label' => 'Slug',
			'type' => 'text',
		),
		'description' => array(
			'label' => 'Description',
			'type' => 'textarea'
		)
	);

	public function __construct(Page $page)
	{
		parent::__construct($page);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->id,
			'Front End URL' => link_to($instance->slug)
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('title');
	}
}

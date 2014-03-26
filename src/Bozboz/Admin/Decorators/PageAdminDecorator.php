<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\Page;
use Illuminate\Support\Facades\HTML;

class PageAdminDecorator extends ModelAdminDecorator
{
	protected static $fields = array(
		'title' => array(
			'label' => 'Title',
			'type' => 'TextField',
		),
		'slug' => array(
			'label' => 'Slug',
			'type' => 'TextField',
		),
		'description' => array(
			'label' => 'Description',
			'type' => 'TextareaField'
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
			'Front End URL' => HTML::link($instance->slug)
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('title');
	}

}

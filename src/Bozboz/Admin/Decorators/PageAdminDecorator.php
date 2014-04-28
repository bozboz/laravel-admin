<?php namespace Bozboz\Admin\Decorators;

use File;
use Bozboz\Admin\Models\Page;
use Illuminate\Support\Facades\HTML;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Fields\TextareaField;
use Bozboz\Admin\Fields\SelectField;

class PageAdminDecorator extends ModelAdminDecorator
{
	public function __construct(Page $page)
	{
		parent::__construct($page);
	}

	public function getColumns($instance)
	{
		return array(
			'Title' => $this->getLabel($instance),
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
			new TextField(array('name' => 'title')),
			new TextField(array('name' => 'slug')),
			new TextareaField(array('name' => 'description')),
			new SelectField(array('name' => 'template', 'options' => $this->getTemplateOptions()))
		);
	}

	private function getTemplateOptions()
	{
		$options = array('' => 'Default');

		$files = File::files(app_path() . '/views/pages');
		foreach($files as $file) {
			$key = str_replace(array('.blade', '.php'), '', basename($file));
			$options[$key] = basename($file);
		}

		return $options;
	}

	public function getListingModels()
	{
		return $this->model->orderBy($this->model->sortBy())->get();
	}

}

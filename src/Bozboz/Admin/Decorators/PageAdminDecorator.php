<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\Page;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Fields\HTMLEditorField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\CheckboxField;

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
			'Front End URL' => HTML::link($instance->slug, URL::route('page', array($instance->slug), false))
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('title');
	}

	public function getFields($instance)
	{
		return array(
			new TextField(array('name' => 'title')),
			new TextField(array('name' => 'html_title', 'label' => 'HTML Title')),
			new TextField(array('name' => 'meta_description', 'class' => 'form-control form-control-wide')),
			new TextField(array('name' => 'slug')),
			new TextField(array('name' => 'external_link')),
			new SelectField(array('name' => 'redirect_to_id', 'label' => 'Redirect To Page', 'options' => $this->getRedirectOptions())),
			new CheckboxField(array('name' => 'show_in_main_menu')),
			new CheckboxField(array('name' => 'show_in_footer_menu')),
			new CheckboxField(array('name' => 'status')),
			new HTMLEditorField(array('name' => 'description')),
			new SelectField(array('name' => 'template', 'options' => $this->getTemplateOptions()))
		);
	}

	protected function getRedirectOptions()
	{
		$options = array('' => 'No redirect');

		$listing = $this->getListingModels();
		foreach($listing as $page) {
			$options[$page->id] = $page->slug;
		}

		return $options;
	}

	protected function getTemplateOptions()
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

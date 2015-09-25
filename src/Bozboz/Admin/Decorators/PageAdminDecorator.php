<?php namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Models\Page;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Bozboz\Admin\Fields\CheckboxField;
use Bozboz\Admin\Fields\HTMLEditorField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Fields\URLField;
use Bozboz\MediaLibrary\Fields\MediaBrowser;

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
			'Front End URL' => link_to($instance->slug, route('page', array($instance->slug), false))
		);
	}

	public function getLabel($instance)
	{
		return $instance->getAttribute('title');
	}

	public function getFields($instance)
	{
		return [
			new TextField('title'),
			new TextField('meta_title'),
			new TextField([
				'name' => 'meta_description',
				'class' => 'form-control form-control-wide'
			]),
			new URLField('slug', [
				'route' => 'page'
			]),
			new TextField('external_link'),
			new SelectField([
				'name' => 'redirect_to_id',
				'label' => 'Redirect To Page',
				'options' => $this->getRedirectOptions()
			]),
			new CheckboxField('show_in_main_menu'),
			new CheckboxField('show_in_footer_menu'),
			new CheckboxField('status'),
			new HTMLEditorField('description'),
			new SelectField([
				'name' => 'template',
				'options' => $this->getTemplateOptions()
			]),
			new MediaBrowser($instance->media())
		];
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

		$files = File::files(app_path('views/pages'));

		foreach($files as $file) {
			$key = str_replace(array('.blade', '.php'), '', basename($file));
			$options[$key] = basename($file);
		}

		return $options;
	}

	public function getSyncRelations()
	{
		return ['media'];
	}

}

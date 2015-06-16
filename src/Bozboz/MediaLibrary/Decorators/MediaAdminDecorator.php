<?php namespace Bozboz\MediaLibrary\Decorators;

use Illuminate\Config\Repository;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\SearchListingFilter;

use Bozboz\MediaLibrary\Fields\MediaField;
use Bozboz\MediaLibrary\Models\Media;

class MediaAdminDecorator extends ModelAdminDecorator
{
	public function __construct(Media $media)
	{
		parent::__construct($media);
	}

	public function getColumns($instance)
	{
		if ($instance->type === 'image') {
			$src = $instance->getFilename('library');
		} else {
			$src = '/packages/bozboz/admin/images/document.png';
		}

		return array(
			'id' => $instance->getKey(),
			'image' => sprintf('<img src="%s" alt="%s" width="150">',
				$src,
				$this->getLabel($instance)
			),
			'caption' => $this->getLabel($instance)
		);
	}

	public function getLabel($instance)
	{
		return $instance->caption ? $instance->caption : $instance->filename;
	}

	public function getFields($instance)
	{
		return array(
			new TextField('caption'),
			new MediaField($instance, array(
				'name' => 'filename'
			))
		);
	}

	public function getHeading($plural = false)
	{
		return 'Media';
	}

	public function getListingFilters()
	{
		return [
			new SearchListingFilter('search', ['filename', 'caption'])
		];
	}
}

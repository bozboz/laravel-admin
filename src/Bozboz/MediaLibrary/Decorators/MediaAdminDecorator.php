<?php namespace Bozboz\MediaLibrary\Decorators;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
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
		return array(
			'id' => $instance->getKey(),
			'image' => sprintf('<img src="%s" alt="%s" width="150">',
				$instance->getPreviewImageUrl(),
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
			new ArrayListingFilter('access', $this->getAccessOptions(), function($builder, $value) {
				switch ($value) {
					case Media::ACCESS_PUBLIC:
						$builder->where('private', 0);
					break;
					case Media::ACCESS_PRIVATE:
						$builder->where('private', 1);
					break;
				}
			}, Media::ACCESS_BOTH),
			new ArrayListingFilter('type', $this->getTypeOptions(), function($builder, $value) {
				if ($value) {
					$builder->where('type', $value);
				}
			}),
			new SearchListingFilter('search', ['filename', 'caption']),
		];
	}

	protected function getAccessOptions()
	{
		return [
			Media::ACCESS_BOTH => 'All',
			Media::ACCESS_PUBLIC => 'Public only',
			Media::ACCESS_PRIVATE => 'Private only',
		];
	}

	private function getTypeOptions()
	{
		return ['All'] + array_map('ucwords', Media::groupBy('type')->lists('type', 'type'));
	}
}

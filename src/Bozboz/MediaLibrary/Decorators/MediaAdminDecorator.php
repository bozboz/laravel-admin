<?php namespace Bozboz\MediaLibrary\Decorators;

use Input;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Builder;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Admin\Reports\Filters\SearchListingFilter;

use Bozboz\MediaLibrary\Fields\MediaField;
use Bozboz\MediaLibrary\Models\Media;

class MediaAdminDecorator extends ModelAdminDecorator
{
	const ACCESS_BOTH = 0;
	const ACCESS_PUBLIC = 1;
	const ACCESS_PRIVATE = 2;
	
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
			new ArrayListingFilter('access', [
					self::ACCESS_BOTH => 'All',
					self::ACCESS_PUBLIC => 'Public Only',
					self::ACCESS_PRIVATE => 'Private Only',
				], function($builder, $value) {
				switch ($value) {
					case self::ACCESS_PUBLIC:
						$builder->where('private', 0);
					break;
					case self::ACCESS_PRIVATE:
						$builder->where('private', 1);
					break;
				}
			}, self::ACCESS_BOTH),
			new ArrayListingFilter('type', $this->getTypeOptions(), function($builder, $value) {
				if ($value) {
					$builder->where('type', $value);
				}
			}),
			new SearchListingFilter('search', ['filename', 'caption']),
		];
	}
	
	protected function filterListingQuery(Builder $query)
	{
		parent::filterListingQuery($query);
		
		if (Input::get('public')) {
			$query->where('private', 0);
		}
		
		if (Input::get('private')) {
			$query->where('private', 1);
		}
	}
	
	private function getTypeOptions()
	{
		return ['All'] + array_map('ucwords', Media::groupBy('type')->lists('type', 'type'));
	}
}

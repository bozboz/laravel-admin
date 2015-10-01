<?php

namespace Bozboz\Admin\Decorators;

use Bozboz\Admin\Fields\BelongsToManyField;
use Bozboz\Admin\Fields\ListField;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\Admin\Reports\Filters\ArrayListingFilter;
use Bozboz\Admin\Reports\Filters\MultiOptionListingFilter;
use Bozboz\Admin\Reports\Filters\SearchListingFilter;
use Bozboz\Admin\Fields\MediaField;
use Bozboz\Admin\Models\Media;

class MediaAdminDecorator extends ModelAdminDecorator
{
	protected $tags;

	public function __construct(Media $media, TagAdminDecorator $tags)
	{
		$this->tags = $tags;

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
			)),
			new BelongsToManyField($this->tags, $instance->tags(), [
				'key' => 'name',
				'data-tags' => 'true'
			])
		);
	}

	public function getHeading($plural = false)
	{
		return 'Media';
	}

	public function getListRelations()
	{
		return [
			'tags' => 'name'
		];
	}

	public function getListingFilters()
	{
		return [
			new ArrayListingFilter('access', $this->getAccessOptions(), function($builder, $value) {
				$builder->scope($value);
			}, Media::ACCESS_BOTH),
			new ArrayListingFilter('type', $this->getTypeOptions(), function($builder, $value) {
				if ($value) {
					$builder->where('type', $value);
				}
			}),
			new SearchListingFilter('search', ['filename', 'caption']),
			new MultiOptionListingFilter('tags', $this->model->tags()->getModel()->lists('name', 'id')->all()),
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
		return ['All'] + array_map('ucwords', $this->model->groupBy('type')->lists('type', 'type')->all());
	}
}

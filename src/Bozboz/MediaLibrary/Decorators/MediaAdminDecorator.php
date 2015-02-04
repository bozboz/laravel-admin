<?php namespace Bozboz\MediaLibrary\Decorators;

use Bozboz\Admin\Decorators\ModelAdminDecorator;
use Bozboz\MediaLibrary\Models\Media;
use Illuminate\Config\Repository;
use Bozboz\Admin\Fields\SelectField;
use Bozboz\Admin\Fields\TextField;
use Bozboz\MediaLibrary\Fields\FileField;

class MediaAdminDecorator extends ModelAdminDecorator
{
	private $config;

	public function __construct(Media $media, Repository $config)
	{
		$this->config = $config;
		parent::__construct($media);
	}

	public function getColumns($instance)
	{
		return array(
			'id' => $instance->getKey(),
			'image' => sprintf('<img src="%s" alt="%s" width="150">',
				$instance->getFilename('library'),
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
			new SelectField(array('name' => 'type', 'options' => $this->config->get('media-library::allowed_media_types', array(
				'image' => 'Image',
				'pdf' => 'PDF'
			)))),
			new TextField(array('name' => 'caption')),
			new FileField(array(
				'name' => 'filename',
				'model' => $instance
			))
		);
	}
}

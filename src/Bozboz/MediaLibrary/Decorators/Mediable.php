<?php namespace Bozboz\MediaLibrary\Decorators;

use Bozboz\MediaLibrary\Fields\MediaBrowser;
use Bozboz\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;

trait Mediable
{
	public function mediaBrowser(Model $instance, $params = [])
	{
		if (isset($params['foreign_key'])) {
			$media = Media::forModel($instance, $params['foreign_key']);
		} else {
			$media = Media::forModel($instance);
		}
		return new MediaBrowser($media, new Media, $params);
	}
}
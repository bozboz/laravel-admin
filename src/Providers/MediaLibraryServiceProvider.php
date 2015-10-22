<?php

namespace Bozboz\Admin\Providers;

use Bozboz\Admin\Models\Media;
use Illuminate\Support\ServiceProvider;

class MediaLibraryServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerMediaHtmlMacro();

		$this->app['events']->listen('admin.renderMenu', function($menu)
		{
			$menu['Media Library'] = route('admin.media.index');
		});
	}

	private function registerMediaHtmlMacro()
	{
		$this->app['html']->macro('media', [$this, 'mediaMacro']);
	}

	/**
	 * Render an HTML image tag based on Media of the first result of provided
	 * query $subject object, or fall back to optional $default filename.
	 *
	 * Example usage:
	 *
	 * HTML::media(Media::forModel($item)->first(), 'thumb', '/images/default.png', $item->name);
	 *
	 * @param  mixed   $subject
	 * @param  string  $size
	 * @param  string  $default
	 * @param  string  $alt
	 * @param  array   $attributes
	 * @return string
	 */
	public function mediaMacro($subject, $size = null, $default = null, $alt = null, $attributes = [])
	{
		// If subject not null and not Media instance, get first instance
		if (! is_null($subject) && ! $subject instanceof Media) {
			$subject = $subject->first();
		}

		$filename = Media::getFilenameOrFallback($subject, $default, $size);

		if ($filename) {
			return $this->app['html']->image($filename, $alt, $attributes);
		}
	}
}

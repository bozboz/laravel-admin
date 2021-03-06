<?php

namespace Bozboz\Admin\Media;

use Bozboz\Admin\Base\Model;
use Bozboz\Admin\Services\Validators\MediaValidator;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Input, Config, Str;
use Whoops\Exception\ErrorException;

class Media extends Model
{
	const ACCESS_BOTH = 0;
	const ACCESS_PUBLIC = 1;
	const ACCESS_PRIVATE = 2;

	protected $table = 'media';
	protected $fillable = array('filename', 'caption', 'private', 'type', 'folder_id');

	public static function boot()
	{
		parent::boot();

		/**
		 * Remove file from disk after deleting
		 * @unlink to ignore errors on the off chance the file isn't there.
		 */
		static::deleted(function($instance) {
			@unlink(public_path($instance->getFilename()));
		});
	}

	public function mediable()
	{
		return $this->morphTo();
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'media_mm_tags');
	}

	public function getValidator()
	{
		return new MediaValidator;
	}

	/**
	 * Accessor method to retrieve all media on a model
	 *
	 * @param  Illuminate\Database\Eloquent\Model  $model
	 * @return Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public static function forModel(Eloquent $model, $foreignKey = null)
	{
		if ($foreignKey) return $model->belongsTo(get_class(), $foreignKey);

		return $model->morphToMany(get_class(), 'mediable')->orderBy('sorting');
	}

	/**
	 * Access method to retrieve all media belonging to a collection
	 *
	 * @param  Illuminate\Support\Collection  $collection
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public static function forCollection(Collection $collection)
	{
		return self::join('mediables', 'media.id', '=', 'mediables.media_id')
			->whereIn('mediable_id', $collection->lists('id')->all())
			->where('mediable_type', get_class($collection->first()))
			->select('media.*')
			->orderBy('sorting');
	}

	/**
	 * Get filename to either the original media file, or - if size argument is
	 * provided - the resized file URL
	 *
	 * @param  string  $size
	 * @return string
	 */
	public function getFilename($size = null)
	{
		if (!is_null($size)) {
			$prefix = '/images/' . $size;
		} else {
			$prefix = $this->getDirectory();
		}
		return $prefix . '/' . $this->filename;
	}

	/**
	 * Get the directory path to original media file
	 *
	 * @return string
	 */
	public function getDirectory()
	{
		return '/media/' . strtolower($this->type);
	}

	public function getFilepath($type, $size)
	{
		return strtolower(sprintf('/images/%s', $size));
	}

	public function getPreviewImageUrl()
	{
		if ($this->private) {
			$filename = asset('assets/images/admin/private-document.png');
		} elseif ($this->type === 'image') {
			$filename = file_exists(public_path($this->getFilename())) ? $this->getFilename('medium') : 'https://placehold.it/150x150?text=Image+missing';
		} else {
			$filename = asset('assets/images/admin/document.png');
		}
		return $filename;
	}

	public function scopeScope($builder, $value)
	{
		switch ($value) {
			case self::ACCESS_PUBLIC:
				$builder->where('private', 0);
			break;
			case self::ACCESS_PRIVATE:
				$builder->where('private', 1);
			break;
		}
	}

	/**
	 * If media instance passed in is not null, return the filename based on an
	 * optional size parameter. Otherwise, return a path to a fallback image.
	 *
	 * Example usage:
	 *
	 * Media::getFilenameOrFallback($item->detailImage, '/fallback-image.jpg', 'thumb')
	 *
	 * @param  Bozboz\Admin\Media\Media|null  $media
	 * @param  string  $fallback
	 * @param  string  $size
	 * @return string
	 */
	static public function getFilenameOrFallback($media, $fallback, $size = null)
	{
		return $media ? $media->getFilename($size) : $fallback;
	}
}

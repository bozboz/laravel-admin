<?php namespace Bozboz\MediaLibrary\Models;

use Input, Config, Str;
use Illuminate\Support\Collection;
use Bozboz\MediaLibrary\Validators\MediaValidator;
use Bozboz\Admin\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Media extends Base
{
	const ACCESS_BOTH = 0;
	const ACCESS_PUBLIC = 1;
	const ACCESS_PRIVATE = 2;

	protected $table = 'media';
	protected $fillable = array('filename', 'caption', 'private');

	public function mediable()
	{
		return $this->morphTo();
	}

	public function tags()
	{
		return $this->belongsToMany('Bozboz\MediaLibrary\Models\Tag', 'media_mm_tags');
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
	public static function forModel(Model $model, $foreignKey = null)
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
			->whereIn('mediable_id', $collection->lists('id'))
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
			$prefix = '';
		}
		return $prefix . '/' . $this->getDirectory() . '/' . $this->filename;
	}

	/**
	 * Get the directory path to original media file
	 *
	 * @return string
	 */
	public function getDirectory()
	{
		return 'media/' . strtolower($this->type);
	}

	public function getFilepath($type, $size)
	{
		return strtolower(sprintf('/images/%s/media/%s', $size, $type));
	}

	public function getPreviewImageUrl()
	{
		if ($this->private) {
			$filename = asset('packages/bozboz/admin/images/private-document.png');
		} elseif ($this->type === 'image') {
			$filename = $this->getFilename('thumb');
		} else {
			$filename = asset('packages/bozboz/admin/images/document.png');
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
}

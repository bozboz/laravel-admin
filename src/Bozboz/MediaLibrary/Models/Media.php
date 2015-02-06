<?php namespace Bozboz\MediaLibrary\Models;

use Input, Config, Str;
use Illuminate\Support\Collection;
use Bozboz\MediaLibrary\Validators\MediaValidator;
use Bozboz\MediaLibrary\Exceptions\InvalidConfigurationException;
use Bozboz\Admin\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Media extends Base
{
	protected $table = 'media';
	protected $fillable = array('filename', 'type', 'caption');
	private $dynamicRelations = array();

	public function mediable()
	{
		return $this->morphTo();
	}

	public function getValidator()
	{
		return new MediaValidator;
	}

	public function setFilenameAttribute($value)
	{
		if (Input::hasFile('filename')) {
			$file = Input::file('filename');
			$destinationPath = public_path() . '/media/' . strtolower($this->type) . '/';
			$filename = $this->cleanFilename($file->getClientOriginalName());
			$uploadSuccess = $file->move($destinationPath, $filename);
			$this->attributes['filename'] = $filename;
		} else {
			$this->attributes['filename'] = $value;
		}
	}

	private function cleanFilename($filename)
	{
		$filenameExploded = explode('.', $filename);
		$filename = $filenameExploded[0];
		$fileExtension = isset($filenameExploded[1]) ? $filenameExploded[1] : '';

		$cleanFilename = Str::slug($filename);
		$cleanFilename .= empty($fileExtension) ? '' : '.' . $fileExtension;

		return $cleanFilename;
	}

	/**
	 * Accessor method to retrieve all media on a model
	 *
	 * @param  Illuminate\Database\Eloquent\Model  $model
	 * @return Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public static function forModel(Model $model, $foreignKey = null)
	{
		if ($foreignKey) {
			return $model->belongsTo(get_class(), $foreignKey);
		}
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

	public function getFilename($size = null)
	{
		if (!is_null($size)) {
			$prefix = '/images/' . $size;
		} else {
			$prefix = '';
		}
		return $prefix . '/media/' . strtolower($this->type) . '/' . $this->filename;
	}

	public function getFilepath($type, $size)
	{
		return strtolower(sprintf('/images/%s/media/%s', $size, $type));
	}
}

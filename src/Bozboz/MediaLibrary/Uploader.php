<?php

namespace Bozboz\MediaLibrary;

use Bozboz\MediaLibrary\Exceptions\UploadException;
use Bozboz\MediaLibrary\Models\Media;
use Guzzle\Http\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
	protected $mimeTypeMapping = [
		'image/*' => 'image',
		'application/pdf' => 'pdf'
	];

	protected $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Tidy up filename, determine type and move uploaded file into correct
	 * location
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  Bozboz\MediaLibrary\Models\Media  $instance
	 * @throws Bozboz\MediaLibrary\Exceptions\UploadException
	 * @return Bozboz\MediaLibrary\Models\Media
	 */
	public function upload(UploadedFile $file, Media $instance)
	{
		DB::beginTransaction();

		$instance->save();

		$instance->type = $this->getTypeFromFile($file);
		$instance->filename = $this->generateUniqueFilenameFromFile($file, $instance->id);

		$destination = $this->getPathFromScope($instance);

		$uploadSuccess = $file->move($destination . '/' . $instance->getDirectory(), $instance->filename);

		if ( ! $uploadSuccess) {
			DB::rollback();
			throw new UploadException;
		}

		$instance->save();

		DB::commit();

		return $instance;
	}

	/**
	 * Download and save a local copy of the passed in URL and associate with
	 * the given media $instance
	 *
	 * @param  string  $url
	 * @param  Bozboz\MediaLibrary\Models\Media  $instance
	 * @return Bozboz\MediaLibrary\Models\Media
	 */
	public function fromUrl($url, Media $instance)
	{
		$instance->filename = basename($url);

		$destination = $this->getPathFromScope($instance) . '/' . $instance->getFilename();

		try {
			$this->client->get($url)->setResponseBody($destination)->send();
		} catch (Exception $e) {
			throw new UploadException($e->getMessage());
		}

		return $instance;
	}

	/**
	 * Generate a unique, clean filename from the uploaded file
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  string  $uniqueString
	 * @return string
	 */
	protected function generateUniqueFilenameFromFile(UploadedFile $file, $uniqueString)
	{
		$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();

		$filenameWithoutExtension = str_replace('.' . $extension, '', $filename);

		return Str::slug($filenameWithoutExtension) . '-' . $uniqueString . '.' . $extension;
	}

	/**
	 * Return the sub-directory to save the uploaded file, based on the file's
	 * mime type
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @return string
	 */
	protected function getTypeFromFile(UploadedFile $file)
	{
		$mimeType = $file->getMimeType();

		foreach($this->mimeTypeMapping as $regex => $directory) {
			if (preg_match("#{$regex}#", $mimeType)) {
				return $directory;
			}
		}

		return 'misc';
	}

	/**
	 * Get absolute path to the root of the directory, depending on if the file
	 * is publicly accessible or not.
	 *
	 * @param  Bozboz\MediaLibrary\Models\Media  $instance
	 * @return string
	 */
	protected function getPathFromScope(Media $instance)
	{
		if ($instance->private) {
			return storage_path();
		} else {
			return public_path();
		}
	}
}

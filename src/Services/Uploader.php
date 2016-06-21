<?php

namespace Bozboz\Admin\Services;

use Bozboz\Admin\Exceptions\UploadException;
use Bozboz\Admin\Media\Media;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
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
	 * Tidy-up and generate a unique filename for an uploaded file, determine
	 * type and move into correct location
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile  $uploadedFile
	 * @param  Bozboz\Admin\Media\Media  $instance
         * @throws Bozboz\Admin\Exceptions\UploadException
	 * @return void
	 */
	public function upload(UploadedFile $uploadedFile, Media $instance)
	{
		DB::beginTransaction();

		$instance->save();

		$instance->filename = $this->generateUniqueFilename(
			$uploadedFile->getClientOriginalName(),
			$uploadedFile->getClientOriginalExtension(),
			$instance->id
		);

		$this->saveFile($uploadedFile, $instance);

		DB::commit();
	}

	/**
	 * Download and save a local copy of the passed in URL and associate with
	 * the given media $instance
	 *
	 * @param  string  $url
	 * @param  Bozboz\MediaLibrary\Models\Media  $instance
	 * @throws Bozboz\MediaLibrary\Exceptions\UploadException
	 * @return void
	 */
	public function fromUrl($url, Media $instance)
	{
		DB::beginTransaction();

		$temporaryPath = public_path('.tmp/DOWNLOADED_FILE-' . time());

		try {
			$this->client->get($url)->setResponseBody($temporaryPath)->send();
		} catch (RequestException $e) {
			DB::rollback();
			throw new UploadException($e->getMessage());
		}

		$externalFile = new File($url, false);

		$instance->save();

		$instance->filename = $this->generateUniqueFilename(
			$externalFile->getBasename(),
			$externalFile->getExtension(),
			$instance->id
		);

		$tempFile = new File($temporaryPath);

		$this->saveFile($tempFile, $instance);

		DB::commit();
	}

	/**
	 * Generate a unique, clean filename from the uploaded file
	 *
	 * @param  string  $name
	 * @param  string  $extension
	 * @param  string  $uniqueString
	 * @return string
	 */
	protected function generateUniqueFilename($name, $extension, $uniqueString)
	{
		$filenameWithoutExtension = str_replace('.' . $extension, '', $name);

		return Str::slug($filenameWithoutExtension) . '-' . $uniqueString . '.' . $extension;
	}

	/**
	 * Generate a type on the media instance based on the passed $file, if it
	 * does not exist. Then move the file to the appropriate destination.
	 *
	 * @param  Symfony\Component\HttpFoundation\File\File   $file
	 * @param  Bozboz\MediaLibrary\Models\Media  $instance
	 * @throws Bozboz\MediaLibrary\Exceptions\UploadException
	 * @return void
	 */
	protected function saveFile(File $file, Media $instance)
	{
		if (empty($instance->type)) {
			$instance->type = $this->getTypeFromFile($file);
		}

		$destination = $this->getPathFromScope($instance) . '/' . $instance->getDirectory();

		try {
			$file->move($destination, $instance->filename);
		} catch (FileException $e) {
			throw new UploadException($e->getMessage());
		}

		$instance->save();
	}

	/**
	 * Return the sub-directory to save the file, based on the mime type
	 *
	 * @param  Symfony\Component\HttpFoundation\File\File  $file
	 * @return string
	 */
	protected function getTypeFromFile(File $file)
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

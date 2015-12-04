<?php

namespace Bozboz\Admin\Services;

use Bozboz\Admin\Exceptions\UploadException;
use Bozboz\Admin\Media\Media;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
	protected $mimeTypeMapping = [
		'image/*' => 'image',
		'application/pdf' => 'pdf'
	];

	/**
	 * Tidy up filename, determine type and move uploaded file into correct
	 * location
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  Bozboz\Admin\Models\Media  $instance
	 * @throws Bozboz\Admin\Exceptions\UploadException
	 * @return void
	 */
	public function upload(UploadedFile $file, Media $instance)
	{
		$instance->filename = $this->cleanFilename($file->getClientOriginalName());
		$instance->type = $this->getTypeFromFile($file);

		if ($instance->private) {
			$destination = storage_path();
		} else {
			$destination = public_path();
		}

		$uploadSuccess = $file->move($destination . '/' . $instance->getDirectory(), $instance->filename);

		if ( ! $uploadSuccess) throw new UploadException;

		$instance->save();

		return $instance;
	}

	/**
	 * Clean uploaded filename string
	 *
	 * @param  string  $filename
	 * @return string
	 */
	protected function cleanFilename($filename)
	{
		$filenameParts = explode('.', $filename);
		$filenameParts[0] = Str::slug($filenameParts[0]);

		return implode('.', $filenameParts);
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
}

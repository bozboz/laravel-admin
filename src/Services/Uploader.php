<?php

namespace Bozboz\Admin\Services;

use Bozboz\Admin\Exceptions\UploadException;
use Bozboz\Admin\Media\Media;
use Illuminate\Support\Facades\DB;
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
	 * @param  Bozboz\Admin\Media\Media  $instance
	 * @throws Bozboz\Admin\Exceptions\UploadException
	 * @return void
	 */
	public function upload(UploadedFile $file, Media $instance)
	{
		DB::beginTransaction();

		$instance->save();

		$instance->type = $this->getTypeFromFile($file);
		$instance->filename = $this->generateUniqueFilenameFromFile($file, $instance->id);

		if ($instance->private) {
			$destination = storage_path();
		} else {
			$destination = public_path();
		}

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
}

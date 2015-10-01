<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Reports\Report;
use Bozboz\Admin\Decorators\MediaAdminDecorator;
use Bozboz\Admin\Models\Media;
use View, Response, Request, Input;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaLibraryAdminController extends ModelAdminController
{
	protected $createView = 'admin::media.upload';
	protected $mimeTypeMapping = [
		'image/*' => 'image',
		'application/pdf' => 'pdf'
	];

	public function __construct(MediaAdminDecorator $media)
	{
		parent::__construct($media);
	}

	public function index()
	{
		if (Request::wantsJson()) {
			return $this->ajaxJSONData();
		}
		$report = new Report($this->decorator);
		$report->overrideView('admin::media.overview');
		return $report->render(array('controller' => get_class($this)));
	}

	public function show($id)
	{
		$media = $this->decorator->findInstanceOrFail($id);

		return Response::download(storage_path($media->getFileName()));
	}

	private function ajaxJSONData()
	{
		$data = array();

		$items = $this->decorator->getListingModels();

		foreach($items as $inst) {
			$data[] = array(
				'id' => $inst->id,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
				'private' => $inst->private,
				'type' => $inst->type
			);
		}

		return Response::json([
			'media' => $data,
			'mediaPath' => $this->decorator->getModel()->getFilepath('image', 'library'),
			'links' => (string)$items->links()
		]);
	}

	public function store()
	{
		$data = [];
		$captions = Input::get('caption', []);
		$is_private = Input::get('is_private', []);

		if (Input::hasFile('files')) {
			foreach(Input::file('files') as $index => $file) {
				$newMedia = $this->decorator->newModelInstance($file);

				$newMedia->filename = $this->cleanFilename($file->getClientOriginalName());
				$newMedia->type = $this->getTypeFromFile($file);

				if (array_key_exists($index, $is_private) && ! empty($is_private[$index])) {
					$uploadSuccess = $file->move(storage_path($newMedia->getDirectory()), $newMedia->filename);
					$newMedia->private = true;
				} else {
					$uploadSuccess = $file->move(public_path($newMedia->getDirectory()), $newMedia->filename);
				}

				if (array_key_exists($index, $captions)) {
					$newMedia->caption = $captions[$index];
				}

				if ($uploadSuccess) {
					$newMedia->save();
					$data[] = [
						'url' => action($this->getActionName('edit'), $newMedia->id),
						'fullsizeUrl' => asset($newMedia->getFilename()),
						'thumbnailUrl' => asset($newMedia->getFilename('library')),
						'name' => $newMedia->caption ?: $newMedia->filename,
						'deleteUrl' => action($this->getActionName('destroy'), $newMedia->id),
						'deleteType' => 'DELETE',
						'id' => $newMedia->id,
						'filename' => $newMedia->filename,
						'type' => $newMedia->type,
						'private' => $newMedia->private
					];
				}
			}
		}

		return Response::json(['files' => $data]);
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
	 * Clean uploaded filename string
	 *
	 * @param  string  $filename
	 * @return string
	 */
	private function cleanFilename($filename)
	{
		$filenameParts = explode('.', $filename);
		$filenameParts[0] = str_slug($filenameParts[0]);

		return implode('.', $filenameParts);
	}

	public function destroy($id)
	{
		$media = $this->decorator->findInstanceOrFail($id);
		$data = [ 'files' => [
			$media->getFilename() => true
		]];

		$media->delete();

		if (Request::ajax()) {
			return Response::json($data);
		} else {
			return $this->getSuccessResponse($media);
		}
	}

}

<?php namespace Bozboz\MediaLibrary\Controllers;

use Bozboz\Admin\Controllers\ModelAdminController;
use Bozboz\Admin\Reports\Report;
use Bozboz\MediaLibrary\Decorators\MediaAdminDecorator;
use View, Response, Request, Input, Redirect, Str;

class MediaLibraryAdminController extends ModelAdminController
{
	protected $createView = 'admin::media.upload';

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

	private function ajaxJSONData()
	{
		$data = array();

		$items = $this->decorator->getListingModels();

		foreach($items as $inst) {
			$data[] = array(
				'id' => $inst->id,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
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

		if (Input::hasFile('files')) {
			foreach(Input::file('files') as $index => $file) {
				$newMedia = $this->decorator->newModelInstance($file);
				$type = explode('/', $file->getMimeType())[0];
				$filename = $this->cleanFilename($file->getClientOriginalName());
				$uploadSuccess = $file->move(public_path('media/' . $type), $filename);

				if (array_key_exists($index, $captions)) {
					$newMedia->caption = $captions[$index];
				}

				$newMedia->filename = $filename;
				$newMedia->type = $type;
				$newMedia->save();

				$data[] = [
					'url' => action(__CLASS__ . '@edit', $newMedia->id),
					'thumbnailUrl' => asset($newMedia->getFilename('library')),
					'name' => $newMedia->caption ?: $newMedia->filename,
					'deleteUrl' => action(__CLASS__ . '@destroy', $newMedia->id),
					'deleteType' => 'DELETE'
				];
			}
		}

		return Response::json(['files' => $data]);
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
		$filenameParts[0] = Str::slug($filenameParts[0]);

		return implode('.', $filenameParts);
	}

	public function destroy($id)
	{
		$media = $this->decorator->findInstance($id);
		$data = [ 'files' => [
			$media->getFilename() => true
		]];

		$media->delete();

		if (Request::ajax()) return Response::json($data);

		return $this->getSuccessResponse();
	}

}

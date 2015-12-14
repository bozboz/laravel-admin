<?php namespace Bozboz\MediaLibrary\Controllers;

use Bozboz\Admin\Controllers\ModelAdminController;
use Bozboz\Admin\Reports\Report;
use Bozboz\MediaLibrary\Decorators\MediaAdminDecorator;
use Bozboz\MediaLibrary\Models\Media;
use Bozboz\MediaLibrary\Uploader;
use View, Response, Request, Input, Redirect;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaLibraryAdminController extends ModelAdminController
{
	protected $createView = 'admin::media.upload';
	protected $uploader;

	public function __construct(MediaAdminDecorator $media, Uploader $uploader)
	{
		$this->uploader = $uploader;

		parent::__construct($media);
	}

	public function index()
	{
		if (Request::wantsJson()) {
			return $this->ajaxJSONData();
		}

		return parent::index();
	}

	protected function getListingReport()
	{
		return new Report($this->decorator, 'admin::media.overview');
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
				$instance = $this->decorator->newModelInstance($file);
				$instance->caption = array_key_exists($index, $captions) ? $captions[$index] : null;
				$instance->private = array_key_exists($index, $is_private) && ! empty($is_private[$index]);
				$this->uploader->upload($file, $instance);
				$data[] = [
					'url' => action(__CLASS__ . '@edit', $instance->id),
					'fullsizeUrl' => asset($instance->getFilename()),
					'thumbnailUrl' => asset($instance->getFilename('library')),
					'name' => $instance->caption ?: $instance->filename,
					'deleteUrl' => action(__CLASS__ . '@destroy', $instance->id),
					'deleteType' => 'DELETE',
					'id' => $instance->id,
					'filename' => $instance->filename,
					'type' => $instance->type,
					'private' => $instance->private,
				];
			}
		}

		return Response::json(['files' => $data]);
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

	public function viewPermissions($stack)
	{
		$stack->add('view_media');
	}

	public function createPermissions($stack, $instance)
	{
		$stack->add('create_media', $instance);
	}

	public function editPermissions($stack, $instance)
	{
		$stack->add('edit_media', $instance);
	}

	public function deletePermissions($stack, $instance)
	{
		$stack->add('delete_media', $instance);
	}
}

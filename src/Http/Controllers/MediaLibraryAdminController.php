<?php namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Media\Media;
use Bozboz\Admin\Media\MediaAdminDecorator;
use Bozboz\Admin\Reports\Actions\Permissions\IsValid;
use Bozboz\Admin\Reports\Actions\Presenters\Link;
use Bozboz\Admin\Reports\Actions\Presenters\Urls\Url;
use Bozboz\Admin\Reports\Report;
use Bozboz\Admin\Services\Uploader;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaLibraryAdminController extends ModelAdminController
{
	protected $useActions = true;
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
		$media = $this->decorator->findInstance($id);

		return Response::download(storage_path($media->getFileName()));
	}

	private function ajaxJSONData()
	{
		$items = $this->decorator->getListingModelsPaginated();

		$data = $items->map(function($inst) {
			return [
				'id' => $inst->id,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
				'private' => $inst->private,
				'type' => $inst->type
			];
		});

		return Response::json([
			'media' => $data,
			'mediaPath' => $this->decorator->getLibraryFilePath(),
			'links' => (string)$items->render()
		]);
	}

	public function store()
	{
		$data = [];
		if (Input::hasFile('files')) {
			foreach(Input::file('files') as $index => $file) {
				$instance = $this->decorator->newModelInstance($file);
				$instance->caption = Input::get('caption', '');
				$instance->private = (boolean)Input::get('is_private');
				$this->uploader->upload($file, $instance);
				$data[] = [
					'url' => action($this->getActionName('edit'), $instance->id),
					'fullsizeUrl' => asset($instance->getFilename()),
					'thumbnailUrl' => asset($instance->getFilename('library')),
					'name' => $instance->caption ?: $instance->filename,
					'deleteUrl' => action($this->getActionName('destroy'), $instance->id),
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
		$media = $this->decorator->findInstance($id);
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

	protected function getFormActions($instance)
	{
		if ($instance->exists) {
			return parent::getFormActions($instance);
		}

		return [
			$this->actions->custom(
				new Link(new Url($this->getListingUrl($instance)), 'Back to listing', 'fa fa-list-alt', [
					'class' => 'btn-default pull-right',
				]),
				new IsValid([$this, 'canView'])
			),
		];
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

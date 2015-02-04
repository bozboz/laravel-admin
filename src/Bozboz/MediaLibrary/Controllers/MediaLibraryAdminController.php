<?php namespace Bozboz\MediaLibrary\Controllers;

use Bozboz\Admin\Controllers\ModelAdminController;
use Bozboz\Admin\Reports\Report;
use Bozboz\MediaLibrary\Decorators\MediaAdminDecorator;
use View, Response, Request;

class MediaLibraryAdminController extends ModelAdminController
{
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
		$report->overrideView('media-library::overview');
		return $report->render(array('controller' => get_class($this)));
	}

	private function ajaxJSONData()
	{
		$data = array();
		foreach($this->decorator->getListingModels() as $inst) {
			$data[] = array(
				'id' => $inst->id,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
				'selected' => false,
				'type' => $inst->type,
				'alias' => null
			);
		}
		return Response::json(['media' => $data, 'mediaPath' => $this->decorator->getModel()->getFilepath('image', 'library')]);
	}
}

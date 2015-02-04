<?php namespace Bozboz\MediaLibrary\Fields;

use Bozboz\Admin\Fields\Field;
use Bozboz\MediaLibrary\Models\Media;
use Eloquent, Form, View;

class MediaBrowser extends Field
{
	private $modelFactory;
	private $mediaFactory;

	public function __construct(Eloquent $modelFactory, Media $mediaFactory, $params = array())
	{
		$this->modelFactory = $modelFactory;
		$this->mediaFactory = $mediaFactory;
		parent::__construct($params);
	}

	public function getInput()
	{
		$currentValues = $this->getCurrentValues();
		$items = array();

		foreach($currentValues as $inst) {
			$items[] = array(
				'id' => $inst->id,
				'type' => $inst,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
				'selected' => true,
				'alias' => $inst->pivot->alias
			);
		}

		$data = json_encode(array(
			'media' => $items,
			'aliases' => $this->get('aliases', []),
			'mediaPath' => $this->mediaFactory->getFilePath('image', 'thumb'),
		));

		return View::make('media-library::fields.media-browser')->with('data', $data);
	}

	/**
	 * Get the IDs of Media this model is related to.
	 *
	 * @return array Media IDs related to the model being created/edited
	 */
	public function getCurrentValues()
	{
		$id = Form::getValueAttribute('id');
		$instance = $this->modelFactory->find($id);

		if (empty($instance)) { //new model
			return array();
		} else {
			return $this->mediaFactory->forModel($instance)->get();
		}
	}
}

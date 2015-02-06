<?php namespace Bozboz\MediaLibrary\Fields;

use Bozboz\Admin\Fields\Field;
use Bozboz\MediaLibrary\Models\Media;
use Eloquent, Form, View;
use Illuminate\Database\Eloquent\Relations\Relation;

class MediaBrowser extends Field
{
	private $relation;
	private $mediaFactory;

	public function __construct(Relation $relation, Media $mediaFactory, $params = array())
	{
		$this->relation = $relation;
		$this->mediaFactory = $mediaFactory;

		parent::__construct($params);
	}

	protected function isManyRelation()
	{
		return strpos(get_class($this->relation), 'Many');
	}

	public function getInput()
	{
		return View::make('admin::fields.media-browser')->with([
			'id' => $this->name,
			'name' => $this->isManyRelation() ? $this->name . '_relationship[]' : $this->name
		]);
	}

	public function getJavascript()
	{
		$currentValues = $this->relation->get();
		$items = array();

		foreach($currentValues as $inst) {
			$items[] = array(
				'id' => $inst->id,
				'type' => $inst,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename,
				'selected' => true
			);
		}

		$data = json_encode(array(
			'media' => $items,
			'mediaPath' => $this->mediaFactory->getFilePath('image', 'thumb')
		));

		return View::make('admin::fields.partials.media-js')->withData($data)->withId($this->name);
	}
}

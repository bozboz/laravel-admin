<?php namespace Bozboz\MediaLibrary\Fields;

use Bozboz\Admin\Fields\Field;
use Bozboz\MediaLibrary\Models\Media;
use Form, View;
use Illuminate\Database\Eloquent\Relations\Relation;

class MediaBrowser extends Field
{
	private $relation;

	public function __construct(Relation $relation, $attributes = array())
	{
		$this->relation = $relation;

		$attributes['name'] = $this->calculateName();

		parent::__construct($attributes);
	}

	/**
	 * Calculate name of inputs, based on type of relation
	 *
	 * @return string
	 */
	protected function calculateName()
	{
		return $this->isManyRelation() ? 'media_relationship' : $this->relation->getForeignKey();
	}

	/**
	 * Determine if relation passed is a "many" relation
	 *
	 * @return boolean
	 */
	protected function isManyRelation()
	{
		return strpos(get_class($this->relation), 'Many') !== false;
	}

	/**
	 * Render media browser, with "current" data (either from model or session)
	 *
	 * @return Illuminate\View\View
	 */
	public function getInput()
	{
		$values = Form::getValueAttribute($this->name);
		$mediaFactory = $this->relation->getRelated();

		$items = $values ? $mediaFactory->whereIn('id', (array)$values)->get()->map(function($inst) {
			return [
				'id' => $inst->id,
				'type' => $inst,
				'caption' => $inst->caption ? $inst->caption : $inst->filename,
				'filename' => $inst->filename
			];
		}) : [];

		$data = [
			'media' => $items,
			'mediaPath' => $mediaFactory->getFilePath('image', 'thumb')
		];

		return View::make('admin::fields.media-browser')->with([
			'id' => $this->name,
			'name' => $this->isManyRelation() ? $this->name . '[]' : $this->name,
			'data' => json_encode($data)
		]);
	}

	/**
	 * Render HTML label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		$replacements = [
			'_relationship' => '',
			'_id' => '',
			'_' => ' '
		];

		$label = ucwords(str_replace(array_keys($replacements), array_values($replacements), $this->name));

		return Form::label($this->name, $label);
	}

	/**
	 * Render Javascript required to initialise media library
	 *
	 * @return Illuminate\View\View
	 */
	public function getJavascript()
	{
		return View::make('admin::fields.partials.media-js')->withId($this->name);
	}
}

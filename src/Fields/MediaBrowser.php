<?php namespace Bozboz\Admin\Fields;

use Bozboz\Admin\Media\Media;
use Collective\Html\FormFacade as Form;
use View;
use Illuminate\Database\Eloquent\Relations\Relation;

class MediaBrowser extends Field
{
	protected $enableVue = true;

	private $relation;
	private $mediaAccess;

	public function __construct(Relation $relation, $attributes = array(), $mediaAccess = Media::ACCESS_PUBLIC)
	{
		switch ($mediaAccess) {
			case Media::ACCESS_PUBLIC:
			case Media::ACCESS_PRIVATE:
				// all good
			break;

			default:
				throw new Exception('Unsupported media browser access type');
			break;
		}

		$this->relation = $relation;
		$this->mediaAccess = $mediaAccess;

		if ( ! array_key_exists('name', $attributes)) {
			$attributes['name'] = $this->calculateName();
		}

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

		$media = $values ? $mediaFactory->find((array)$values)->load('tags')->keyBy('id') : collect();

		$items = collect($values)->filter()->map(function($id) use ($media) {
			return $media->get($id);
		})->map(function($inst) {
			if ( ! $inst) {
				return null;
			}

			return [
				'id' => $inst->id,
				'type' => $inst->type,
				'caption' => $inst->caption,
				'tags' => $inst->tags,
				'folder_id' => $inst->folder_id,
				'private' => $inst->private,
				'filename' => $inst->filename
			];
		})->filter();

		$data = [
			'media' => $items->all(),
			'mediaPath' => $mediaFactory->getFilePath('image', 'small'),
			'mediaAccess' => $this->mediaAccess,
		];

		return View::make('admin::fields.media-browser')->with([
			'id' => $this->sanitiseName($this->name),
			'name' => $this->isManyRelation() ? $this->name . '[]' : $this->name,
			'data' => json_encode($data, JSON_HEX_APOS), // force escaping of single quotes in json (https://stackoverflow.com/questions/17926354/php-json-encode-jquery-parsejson-single-quote-issue)
			'isManyRelation' => $this->isManyRelation(),
		]);
	}

	/**
	 * Render HTML label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		if (isset($this->attributes['label'])) {
			$label = $this->attributes['label'];
		} else {
			$replacements = [
				'_relationship' => '',
				'_id' => '',
				'_' => ' '
			];

			$label = ucwords(str_replace(array_keys($replacements), array_values($replacements), $this->name));
		}

		return Form::label($this->name, $label);
	}

	/**
	 * Render Javascript required to initialise media library
	 *
	 * @return Illuminate\View\View
	 */
	public function getJavascript()
	{
		// return View::make('admin::fields.partials.media-js')->with([
		// 	'id' => $this->sanitiseName($this->name),
		// 	'access_public' => Media::ACCESS_PUBLIC,
		// 	'access_private' => Media::ACCESS_PRIVATE,
		// ])->render();
	}

	/**
	 * Produce a valid HTML class name
	 *
	 * @param $name string
	 * @return string
	 */
	protected function sanitiseName($name)
	{
		return str_replace(['[', ']'], '', $name);
	}

}

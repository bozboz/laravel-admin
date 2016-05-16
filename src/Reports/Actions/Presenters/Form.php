<?php

namespace Bozboz\Admin\Reports\Actions\Presenters;

class Form extends Presenter
{
	private $url;
	private $button;

	public function __construct($url, $label, $icon = null, $attributes = [], $formAttributes = [])
	{
		$this->url = ($url instanceof Urls\Contract) ? $url : new Urls\Action($url);

		$this->button = new Button($label, $icon, $attributes);

		parent::__construct($formAttributes);
	}

	public function getView()
	{
		return 'admin::report-actions.form';
	}

	public function getViewData()
	{
		return [
			'attributes' => $this->compileFormAttributes(),
			'button' => $this->button,
		];
	}

	protected function compileFormAttributes()
	{
		$attributes = $this->attributes;

		$attributes['class'] = trim($attributes['class'] . ' inline-form');
		$attributes['url'] = $this->url->compile($this->instance);

		return $attributes;
	}
}

<?php namespace Bozboz\Admin\Fields;

class URLField extends TextField
{
	protected $baseUrl;

	public function __construct($name, $routeOrAttributes, $attributes = [])
	{
		if (is_array($routeOrAttributes)) {
			$attributes = $routeOrAttributes;
			$route = $attributes['route'];
		} else {
			$route = $routeOrAttributes;
		}
		$this->baseUrl = route($route, '');

		parent::__construct($name, $attributes);
	}

	protected function defaultAttributes()
	{
		return [
			'label' => 'URL'
		];
	}

	public function getInput()
	{
		if ($this->baseUrl) {
			$input = parent::getInput();
			return <<<HTML
				<div class="input-group">
					<div class="input-group-addon">{$this->baseUrl}/</div>
					{$input}
				</div>
				<p class="help-block"><strong>Note:</strong> once published, this URL should not change</p>
HTML;
		}

		return parent::getInput();
	}
}

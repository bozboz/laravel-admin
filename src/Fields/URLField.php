<?php namespace Bozboz\Admin\Fields;

class URLField extends TextField
{
	protected $baseUrl;

	public function __construct($nameOrAttributes, $routeOrAttributes = null, $attributes = [])
	{
		if (is_array($nameOrAttributes)) {
			$attributes = $nameOrAttributes;
			$route = $attributes['route'];
		} elseif (is_array($routeOrAttributes)) {
			$attributes = $routeOrAttributes;
			$route = $attributes['route'];
		} else {
			$route = $routeOrAttributes;
		}

		$this->baseUrl = route($route, '');

		parent::__construct($nameOrAttributes, $attributes);
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

	public function getJavascript()
	{
		return <<<JAVASCRIPT
			jQuery(function($) {
				$('[data-auto-slug-from]').each(function() {
					var slugField = $(this);
					var sourceField = $('[name="'+slugField.data('auto-slug-from')+'"]');

					sourceField.change(function() {
						if (slugField.val() === '') {
							var slug = sourceField.val().toLowerCase()
								.replace('&', 'and')
								.replace(/[^\w]+/g, '-')
								.replace(/^[-]+|[-]+$/g, '');
							slugField.val(slug);
						}
					});
				});
			});
JAVASCRIPT;
	}
}

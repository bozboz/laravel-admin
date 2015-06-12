<?php namespace Bozboz\Admin\Fields;

class URLField extends TextField
{
	protected function defaultAttributes()
	{
		return [
			'label' => 'URL'
		];
	}

	public function getInput()
	{
		if ($this->route) {
			$baseUrl = route($this->route, '');
			$input = parent::getInput();
			return <<<HTML
				<div class="input-group">
					<div class="input-group-addon">{$baseUrl}/</div>
					{$input}
				</div>
				<p class="help-block"><strong>Note:</strong> once published, this URL should not change</p>
HTML;
		}

		return parent::getInput();
	}
}

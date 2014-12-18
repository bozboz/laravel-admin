<?php namespace Bozboz\Admin\Fields;

class DateTimeField extends Field
{
	private $altName;

	public function __construct($attributes)
	{
		parent::__construct($attributes);
		$this->altName = $this->name . '_alt';
	}

	public function getInput()
	{
		return <<<HTML
			<input type="text" name="$this->name" id="$this->name">
			<input type="hidden" name="$this->altName" id="$this->altName">
HTML;
	}

	public function getJavascript()
	{
		return <<<JAVASCRIPT
			jQuery(function($) {
				$('#$this->name').datetimepicker({
					altField: '#$this->altName',
					altFieldTimeOnly: false,
					showSecond: false,
					stepMinute: 5,
					altFormat: "yy-mm-dd",
					altTimeFormat: "HH:mm:ss",
				});
			});
JAVASCRIPT;
	}
}

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
			<input type="text" id="$this->altName">
			<input type="hidden" name="$this->name" id="$this->name">
HTML;
	}

	public function getJavascript()
	{
		return <<<JAVASCRIPT
			jQuery(function($) {
				$('#$this->altName').datetimepicker({
					showSecond: false,
					stepMinute: 5,
					dateFormat: 'dd/mm/yy',
					minDate: new Date(),
					altField: '#$this->name',
					altFieldTimeOnly: false,
					altFormat: 'yy-mm-dd',
					altTimeFormat: 'HH:mm:ss',
				});
			});
JAVASCRIPT;
	}
}

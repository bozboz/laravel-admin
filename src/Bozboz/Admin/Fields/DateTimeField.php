<?php namespace Bozboz\Admin\Fields;

use Form;

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
		return sprintf('<input type="text" id="%s">%s', $this->altName, Form::hidden($this->name));
	}

	public function getJavascript()
	{
		return <<<JAVASCRIPT
			jQuery(function($) {
				$('#$this->altName').datetimepicker({
					showSecond: false,
					second: 0,
					stepMinute: 5,
					dateFormat: 'dd/mm/yy',
					minDate: new Date(),
					altField: '#$this->name',
					altFieldTimeOnly: false,
					altFormat: 'yy-mm-dd',
					altTimeFormat: 'HH:mm:ss',
				});

				if ($('#$this->name').val() !== '') {
					var dateTime = $('#$this->name').val().split(' ');
					var dateInfo = dateTime[0].split('-');
					var timeInfo = dateTime[1].split(':');

					$('#$this->altName').datetimepicker('setDate', new Date(dateInfo[0], dateInfo[1], dateInfo[2], timeInfo[0], timeInfo[1], '0'));
				}
			});
JAVASCRIPT;
	}
}

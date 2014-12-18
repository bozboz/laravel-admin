<?php namespace Bozboz\Admin\Fields;

class DateTimeField extends Field
{
	/* public function getInput() */
	/* { */
	/* 	return '<input type="text" name="datetime_picker" id="datetime_picker">'; */
	/* } */

	public function getInput()
	{
		return <<<HTML
			<input type="text" name="datetime_picker" id="datetime_picker">
			<input type="hidden" name="datetime_picker_alt" id="datetime_picker_alt">
HTML;
	}

	public function getJavascript()
	{
		return <<<JAVASCRIPT
			jQuery(function($) {
				$('#datetime_picker').datetimepicker({
					altField: '#datetime_picker_alt',
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

<?php namespace Bozboz\Admin\Fields;

use Form;

class DateTimeField extends DateField
{
	protected function getJsClass()
	{
		return 'js-datetimepicker';
	}
}

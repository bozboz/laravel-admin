<?php namespace Bozboz\Admin\Fields;

class DateTimeField extends DateField
{
	protected function getJsClass()
	{
		return 'js-datetimepicker';
	}
}

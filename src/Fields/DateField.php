<?php namespace Bozboz\Admin\Fields;

use Form;

class DateField extends Field
{
	private $altName;

	protected $configOptions;

	public function __construct($attributes)
	{
		parent::__construct($attributes);

		$this->altName = $this->name . '_alt';
		$this->configOptions = empty($this->options) ? [] : $this->options;
	}

	public function getInput()
	{
		return sprintf('<input type="text" id="%s">%s', $this->altName, Form::hidden($this->name));
	}

	public function getJavascript()
	{
		$jsonConfig = json_encode($this->configOptions);

		return <<<JAVASCRIPT
			(function() {
				var config = $jsonConfig;

				var datePickerDefaults = {
					showSecond: false,
					dateFormat: 'dd/mm/yy',
					altField: '#$this->name',
					altFieldTimeOnly: false,
					altFormat: 'yy-mm-dd',
				};

				//Convert a MySQL DateTime formatted string into a JS Date object
				var stringToDate = function(dateString) {
					var dateInfo = dateString.split('-');

					return new Date(dateInfo[0], dateInfo[1] - 1, dateInfo[2]);
				};

				var date = $('#$this->name').val() === '' ? null : stringToDate($('#$this->name').val())

				//Parse config and convert applicable strings to Date objects
				var dateFields = ['defaultDate'];
				for (var i = 0; i < dateFields.length; i++) {
					var j = dateFields[i];
					if (config[j] !== undefined && config[j] !== null) {
						config[j] = new Date(config[j] * 1000);
					}
				}

				//Revert to some default if applicable
				if (date === null && config.defaultDate !== undefined) {
					date = config.defaultDate;
				}

				//Initialise datepicker
				$('#$this->altName').datepicker($.extend(datePickerDefaults, config));
				if (date !== null) {
					$('#$this->altName').datepicker('setDate', date);
				}
			})();
JAVASCRIPT;
	}
}

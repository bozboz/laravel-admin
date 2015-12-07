<?php namespace Bozboz\Admin\Fields;

use Form;
use Illuminate\Support\Str;

class DateTimeField extends Field
{
	private $altName, $sanitisedName;

	protected $configOptions;

	public function __construct($attributes)
	{
		parent::__construct($attributes);

		$this->sanitisedName = Str::slug($this->name);
		$this->altName = $this->sanitisedName . '_alt';
		$this->configOptions = empty($this->options) ? [] : $this->options;
	}

	public function getInput()
	{
		return sprintf('<input type="text" id="%s">%s', $this->altName, Form::hidden($this->name, null, ['id' => $this->sanitisedName]));
	}

	public function getJavascript()
	{
		$jsonConfig = json_encode($this->configOptions);

		return <<<JAVASCRIPT
			(function() {
				var config = $jsonConfig;

				var dateTimePickerDefaults = {
					showSecond: false,
					dateFormat: 'dd/mm/yy',
					altField: '#$this->sanitisedName',
					altFieldTimeOnly: false,
					altFormat: 'yy-mm-dd',
					altTimeFormat: 'HH:mm:ss',
				};

				//Convert a MySQL DateTime formatted string into a JS Date object
				var stringToDate = function(dateTimeString) {
					var dateTimeExploded = dateTimeString.split(' ');
					var dateInfo = dateTimeExploded[0].split('-');
					var timeInfo = dateTimeExploded[1].split(':');

					return new Date(dateInfo[0], dateInfo[1] - 1, dateInfo[2], timeInfo[0], timeInfo[1], timeInfo[2]);
				};

				var dateTime = $('#$this->sanitisedName').val() === '' ? null : stringToDate($('#$this->sanitisedName').val())

				//Parse config and convert applicable strings to Date objects
				var dateTimeFields = ['minDateTime', 'maxDateTime', 'defaultDateTime'];
				for (var i = 0; i < dateTimeFields.length; i++) {
					var j = dateTimeFields[i];
					if (config[j] !== undefined && config[j] !== null) {
						config[j] = new Date(config[j] * 1000);
					}
				}

				//Revert to some default if applicable
				if (dateTime === null && config.defaultDateTime !== undefined) {
					dateTime = config.defaultDateTime;
				}

				//Initialise datetimepicker
				$('#$this->altName').datetimepicker($.extend(dateTimePickerDefaults, config));
				if (dateTime !== null) {
					$('#$this->altName').datetimepicker('setDate', dateTime);
				}
			})();
JAVASCRIPT;
	}
}

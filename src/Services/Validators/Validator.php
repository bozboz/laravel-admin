<?php namespace Bozboz\Admin\Services\Validators;

use Illuminate\Support\Arr;

abstract class Validator
{
	protected $errors;
	protected $rules = array();
	protected $storeRules = array();
	protected $updateRules = array();
	protected $messages = array();

	public function passesStore($attributes)
	{
		return $this->passes($attributes, $this->getStoreRules());
	}

	public function passesUpdate($attributes)
	{
		return $this->passes($attributes, $this->getUpdateRules());
	}

	protected function getStoreRules()
	{
		return array_merge($this->rules, $this->storeRules);
	}

	protected function getUpdateRules()
	{
		return array_merge($this->rules, $this->updateRules);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	protected function passes($attributes, $rules)
	{
		$rules = $this->replacePlaceholders($attributes, $rules);

		$validation = \Validator::make($attributes, $rules, $this->messages);

		$this->validating($validation);

		$this->errors = $validation->messages();

		return $validation->passes();
	}

	protected function replacePlaceholders($attributes, $rules)
	{
		$find = [];

		foreach(Arr::dot($attributes) as $key => $val) {
			$find['{' . $key . '}'] = $val;
		}

		foreach($rules as $name => $rule) {
			$rules[$name] = str_replace(array_keys($find), array_values($find), $rule);
		}

		return $rules;
	}

	protected function validating($validator)
	{
		// override if conditional formatting is required
	}
}

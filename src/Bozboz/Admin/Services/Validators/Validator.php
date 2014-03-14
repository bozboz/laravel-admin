<?php namespace Bozboz\Admin\Services\Validators;

abstract class Validator
{
	protected $errors = array();
	protected $rules = array();
	protected $storeRules = array();
	protected $editRules = array();

	public function passesStore($attributes)
	{
		return $this->passes($attributes, array_merge($this->rules, $this->storeRules));
	}

	public function passesEdit($attributes)
	{
		return $this->passes($attributes, array_merge($this->rules, $this->editRules));
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function updateUniques($id)
	{
		foreach (array_merge($this->rules, $this->editRules) as $attribute => $validationRules) {
			if (strpos($validationRules, 'unique') !== false) {
				$regexReplacement = 'unique:$1,' . $attribute . ',' . $id;
				$this->editRules[$attribute] = preg_replace('/unique:(.*)/', $regexReplacement, $validationRules);
			}
		}
	}

	protected function passes($attributes, $rules)
	{
		$validation = \Validator::make($attributes, $rules);
		$isValid = true;

		if (!$validation->passes()) {
			$isValid = false;
			$this->errors = $validation->messages();
		}

		return $isValid;
	}
}

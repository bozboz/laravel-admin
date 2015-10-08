<?php namespace Bozboz\Admin\Services\Validators;

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

	/**
	 * Transform "unique:{table}" into "unique:{table},{attribute},{id}"
	 */
	public function updateUniques($id)
	{
		foreach ($this->getUpdateRules() as $attribute => $validationRules) {
			if (strpos($validationRules, 'unique') !== false) {
				$regexReplacement = 'unique:$1,' . $attribute . ',' . $id;
				$this->updateRules[$attribute] = preg_replace('/unique:(\w++)(?=\b)/', $regexReplacement, $validationRules);
			}
		}
	}

	protected function passes($attributes, $rules)
	{
		$validation = \Validator::make($attributes, $rules, $this->messages);
		$this->errors = $validation->messages();

		return $validation->passes();
	}
}

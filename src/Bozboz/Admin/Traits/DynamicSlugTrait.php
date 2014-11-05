<?php namespace Bozboz\Admin\Traits;

use Str;
use Exception;

trait DynamicSlugTrait
{
	/**
	 * @return string Attribute from which to derive the slug.
	 */
	abstract protected function getSlugSourceField();

	/**
	 * @return string Attribute being used as the slug.
	 */
	protected function getSlugField()
	{
		return 'slug';
	}

	/**
	 * Dynamically set the value of the model's slug.
	 */
	public function save(array $options = [])
	{
		$id = $this->{$this->primaryKey};
		$slugField = $this->getSlugField();
		$modelSlugValue = $this->getOriginal($slugField);
		$submittedSlugValue = $this->getAttribute($slugField);
		$isUpdatedSlugValue = !empty($id) && ($modelSlugValue !== $submittedSlugValue);

		if (empty($id) || $isUpdatedSlugValue) {
			if (empty($submittedSlugValue)) {
				$source = $this->{$this->getSlugSourceField()};
			} else {
				$source = $submittedSlugValue;
			}

			$this->generateSlug($source);
		}


		parent::save($options);
	}

	private function generateSlug($source)
	{
		$slugField = $this->getSlugField();
		$this->$slugField = Str::slug($source);

		if (empty($this->$slugField)) { //Homepage edgecase
			$this->$slugField = '/';
		}

		$unique = false;
		while (!$unique) {
			try {
				$builder = self::where($slugField, '=', $this->$slugField);
				$id = $this->{$this->primaryKey};
				if (!empty($id)) {
					$builder->where($this->primaryKey, '!=', $id);
				}
				$model = $builder->firstOrFail();
				$this->$slugField = $this->incrementId($this->$slugField);
			} catch (Exception $e) {
				$unique = true;
			}
		}
	}

	/**
	 * Given a string, append a number. If a number is already appended,
	 * increment it.
	 */
	private function incrementId($string)
	{
		$lastChar = substr($string, -1);
		if (is_numeric($lastChar)) {
			$foundAll = false;
			$i = strlen($string) - 1;
			while (!$foundAll) {
				if (is_numeric($string[$i])) {
					$i--;
				} else {
					$foundAll = true;
				}
			}
			$number = intval(substr($string, $i + 1, strlen($string)));
			$number++;
			$string = substr($string, 0, $i + 1);
			$string .= $number;
		} else {
			$string .= '-1';
		}

		return $string;
	}
}

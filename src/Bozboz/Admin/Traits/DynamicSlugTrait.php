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
		if (is_null($this->created_at)) {
			$this->generateSlug();
		}

		parent::save($options);
	}

	private function generateSlug()
	{
		$slugField = $this->getSlugField();
		$slugSourceField = $this->getSlugSourceField();
		$this->$slugField = Str::slug($this->$slugSourceField);

		$unique = false;
		while (!$unique) {
			try {
				$model = self::where($slugField, '=', $this->$slugField)->firstOrFail();
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

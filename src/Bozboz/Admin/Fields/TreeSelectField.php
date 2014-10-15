<?php namespace Bozboz\Admin\Fields;

use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class TreeSelectField extends SelectField
{
	private $all = [];

	/**
	 * @param  Illuminate\Database\Eloquent\Collection  $array
	 * @param  array  $attributes
	 */
	public function __construct(Collection $array, $attributes = array())
	{
		parent::__construct($attributes);

		$this->all = $this->prepTreeStructure($array);
	}

	/**
	 * Set tree to the options on the underlying SelectField
	 *
	 * @return string
	 */
	public function getInput()
	{
		$this->options = $this->getOptions();
		return parent::getInput();
	}

	/**
	 * @return array
	 */
	private function getOptions()
	{
		return $this->renderLevel(0);
	}

	/**
	 * @param  array  $input
	 * @return array
	 */
	private function prepTreeStructure($input)
	{
		$tree = [];
		$parentField = $this->get('parent_field', 'parent_id');

		foreach($input as $cat) {
			$key = $cat->$parentField;
			if (is_null($key)) {
				throw new InvalidArgumentException('"' . $parentField . '" field is not set on this instance');
			}
			$tree[$key][] = $cat;
		}

		return $tree;
	}

	/**
	 * @param  int  $level
	 * @param  int  $depth
	 * @param  array
	 */
	private function renderLevel($level, $depth = 0)
	{
		foreach($this->all[$level] as $item) {
			$tree[$item->id] = str_repeat(str_repeat($this->get('indent_character', '&nbsp;'), 4), $depth) . $item->name;
			if (array_key_exists($item->id, $this->all)) {
				$tree += $this->renderLevel($item->id, $depth + 1);
			}
		}
		return $tree;
	}
}

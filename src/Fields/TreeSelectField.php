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
	 * Return options for field
	 *
	 * @return array
	 */
	private function getOptions()
	{
		if ( ! $this->all) {
			$this->disabled = true;
			$options = [];
		} else {
			$options = array_merge([null => '- Please Select -'], $this->renderLevel(0));
		}

		if ($this->get('allow_none', true)) {
			$options = ['' => '-'] + $options;
		}

		return $options;
	}

	/**
	 * Turn a flat collection into a parent-keyed multi-dimensional array
	 *
	 * @param  Illuminate\Database\Eloquent\Collection  $input
	 * @return array
	 */
	private function prepTreeStructure($input)
	{
		$tree = [];
		$parentField = $this->get('parent_field', 'parent_id');

		foreach($input as $cat) {
			$key = $cat->$parentField ?: 0;
			$tree[$key][] = $cat;
		}

		return $tree;
	}

	/**
	 * Turn multi-dimensional array into flat "indented" array
	 * representing tree hiercharcy
	 *
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

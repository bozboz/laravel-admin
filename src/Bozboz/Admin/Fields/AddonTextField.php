<?php namespace Bozboz\Fields;

use Bozboz\Admin\Fields\TextField;

class AddonTextField extends TextField
{
	public function getInput()
	{
		$addon = '<span class="input-group-addon">' . $this->get('data-addonText') . '</span>';
		
		$return = '<div class="input-group">';
		
		if (empty($this->get('data-addonPosition')) || $this->get('data-addonPosition')=='before')
			$return .= $addon;
		
		$return .= parent::getInput();
		
		if ($this->get('data-addonPosition')=='after')
			$return .= $addon;
		
		return $return . '</div>';
	}
}

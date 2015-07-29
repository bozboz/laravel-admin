<?php

namespace Bozboz\Admin\Fields;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ListField extends SelectField
{
	public function __construct(BelongsToMany $relation, $attribute = 'name')
	{
		$name = $relation->getRelationName() . '_list[]';
		$options = $relation->getModel()->lists($attribute, $attribute);
		$label = ucwords(str_replace('_', ' ', $relation->getRelationName()));

		parent::__construct($name, [
			'options' => $options,
			'label' => $label,
			'class' => 'form-control select2',
			'multiple'
		]);
	}
}

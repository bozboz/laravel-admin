<?php namespace Bozboz\Admin\FieldMapping;

class Mapper
{
	public function getFields(Array $fields)
	{
		$fieldInstances = array();
		foreach($fields as $attr => $params) {
			$params['name'] = $attr;
			$fieldInstances[] = new Field($params);
		}

		return $fieldInstances;
	}
}

<?php namespace Bozboz\Admin\FieldMapping;

class Mapper
{
	public function getFields(Array $fields)
	{
		$fieldInstances = array();
		foreach($fields as $params) {
			$params['name'] = $attr;
			$fieldInstances[] = new Field(array(
				'method' => array_shift($params),
				'args' => $params
			));
		}

		return $fieldInstances;
	}
}

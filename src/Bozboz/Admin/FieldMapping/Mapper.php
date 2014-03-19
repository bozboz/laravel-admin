<?php namespace Bozboz\Admin\FieldMapping;

class Mapper
{
	public function getFields(Array $fields)
	{
		$fieldInstances = array();
		foreach($fields as $attr => $params) {
			$params['name'] = $attr;
			$className = __NAMESPACE__ . '\\Fields\\' . $params['type'];
			unset($params['type']);
			$fieldInstances[] = new $className($params);
		}

		return $fieldInstances;
	}
}

<?php namespace Bozboz\MediaLibrary\Fields;

use Bozboz\Admin\Fields\Field;
use Illuminate\Support\Facades\Form;
use InvalidArgumentException;

class FileField extends Field
{
	public function getInput()
	{
		$model = $this->get('model');

		if (empty($model)) {
			throw new InvalidArgumentException('Please pass in the model instance');
		}

		if (!empty($model->filename)) {
			$html = Form::hidden($this->get('name'));

			if ($model->type === 'image') {
				$filename = $model->getFilename('thumb');
			} else {
				$filename = asset('/packages/bozboz/media-library/images/document.png');
			}
			$html .= sprintf('<img src="%s" style="margin-bottom: 5px; display: block">', $filename);

			$html .= '<p>' . $model->filename . '</p>';
		} else {
			$html = Form::file($this->get('name'), $this->attributes);
		}

		return $html;
	}
}

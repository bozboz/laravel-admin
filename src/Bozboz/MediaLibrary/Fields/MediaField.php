<?php namespace Bozboz\MediaLibrary\Fields;

use Bozboz\Admin\Fields\Field;
use Bozboz\MediaLibrary\Models\Media;

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\HTML;

class MediaField extends Field
{
	protected $media;

	public function __construct(Media $media, $attributes)
	{
		$this->media = $media;

		parent::__construct($attributes);
	}

	public function getInput()
	{
		if (empty($this->media->filename)) return Form::file($this->get('name'), $this->attributes);

		return $this->mediaPreview();
	}

	protected function mediaPreview()
	{
		$html = Form::hidden($this->get('name'));

		$alt = $this->media->filename ?: $this->media->caption;

		$html .= HTML::image($this->media->getPreviewImageUrl(), $alt, ['style' => 'margin-bottom: 5px; display: block']);
		$html .= '<p>' . HTML::link($this->getMediaPreviewURL(), $this->media->filename, ['target' => '_blank']) . '</p>';

		return $html;
	}
	
	public function getMediaPreviewURL()
	{
		if ($this->media->private) {
			return route('admin.media.view-private', [$this->media->id]);
		} else {
			return $this->media->getFilename();
		}
	}
}

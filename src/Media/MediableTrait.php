<?php

namespace Bozboz\Admin\Media;

trait MediableTrait
{
	public function media()
	{
		return Media::forModel($this);
	}
}

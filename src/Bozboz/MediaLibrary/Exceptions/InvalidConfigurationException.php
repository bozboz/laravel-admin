<?php namespace Bozboz\MediaLibrary\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
	public function __construct($message = "", $code = 0, Exception $previous = null)
	{
		if (empty($message)) {
			$message = 'Invalid "media-library" configuration';
		}

		parent::__construct($message, $code, $previous);
	}
}

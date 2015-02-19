<?php namespace Bozboz\Admin\Meta;

interface MetaInterface
{
	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @return string
	 */
	public function getDescription();
}

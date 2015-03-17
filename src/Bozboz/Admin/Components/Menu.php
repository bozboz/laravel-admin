<?php namespace Bozboz\Admin\Components;

use Illuminate\Support\Fluent;
use Illuminate\Http\Request;

class Menu extends Fluent
{
	protected $request;

	/**
	 * Set the request object, for retrieval of current path and root
	 *
	 * @param  Illuminate\Http\Request  $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function getTopLevelItems()
	{
		$topLevelItems = array();

		foreach($this->getAttributes() as $item => $link) {
			if (is_array($link)) {
				$topLevelItems[$item] = $link;
			} else {
				$topLevelItems['Content'][$item] = $link;
			}
		}

		return $topLevelItems;
	}

	/**
	 * Get the active class or an empty string, dependent on $isActive
	 *
	 * @param  boolean  $isActive
	 * @return string
	 */
	protected function getActiveClass($isActive)
	{
		return $isActive ? 'active' : '';
	}

	/**
	 * Determine active state of section containing a $urls array
	 *
	 * @param  array  $urls
	 * @return string|null
	 */
	public function activeClassForUrls(array $urls)
	{
		foreach($urls as $url) {
			if ($this->activeClassForPartialUrl($url)) {
				return $this->getActiveClass(true);
			}
		}
	}

	/**
	 * Determine if $url is an exact match of the current request path
	 *
	 * @param  string  $url
	 * @return string
	 */
	public function activeClassForUrl($url)
	{
		$strippedUrl = str_replace($this->request->root() . '/', '', $url);

		return $this->getActiveClass($this->request->is($strippedUrl));
	}

	/**
	 * Determine if $url is a partial match of the current request path
	 *
	 * @param  string  $url
	 * @return string
	 */
	public function activeClassForPartialUrl($url)
	{
		$strippedUrl = str_replace($this->request->root() . '/', '', $url);

		return $this->getActiveClass($this->request->is($strippedUrl, $strippedUrl . '/*'));
	}
}

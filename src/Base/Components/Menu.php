<?php

namespace Bozboz\Admin\Base\Components;

use Bozboz\Permissions\RuleStack;
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
				$topLevelItems[$item] = isset($topLevelItems[$item]) ? $link+$topLevelItems[$item] : $link;
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
		$matched = $this->matchUrlPattern($url, '/^%s$/');

		return $this->getActiveClass($matched);
	}

	/**
	 * Determine if $url is a partial match of the current request path
	 *
	 * @param  string  $url
	 * @return string
	 */
	public function activeClassForPartialUrl($url)
	{
		$matched = $this->matchUrlPattern($url, '/^%s(\/.+)?$/');

		return $this->getActiveClass($matched);
	}

	/**
	 * Match URL to pattern
	 *
	 * @param  string  $url
	 * @param  string  $pattern
	 * @return boolean
	 */
	protected function matchUrlPattern($url, $pattern)
	{
		$pattern = sprintf($pattern, preg_quote($url, '/'));

		return (bool) preg_match($pattern, $this->request->fullUrl());
	}

	/**
	 * Check permissions for a particular rule, or fallback to catchall
	 * "view_anything" rule
	 *
	 * @param  string  $rule
	 * @return boolean
	 */
	public function gate($rule, $param = null)
	{
		return RuleStack::with($rule, $param)->then('view_anything')->isAllowed();
	}
}
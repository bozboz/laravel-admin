<?php

namespace Bozboz\Admin\Base\Components;

use Bozboz\Permissions\RuleStack;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class Menu extends Fluent
{
	protected $request;
	protected $url;

	/**
	 * Set the request object, for retrieval of current path and root
	 *
	 * @param  Illuminate\Http\Request  $request
	 */
	public function __construct(Request $request, UrlGenerator $url)
	{
		$this->request = $request;
		$this->url = $url;
	}

	/**
	 * Allows setting menu like an array, e.g.:
	 *
	 * $menu['Top Level Item'] = '/your/url';
	 *
	 * $menu['Top Level Group'] = [
	 *     'Sub Item' => '/a/url',
	 *     'Another Sub Item' => '/another/url',
	 * ];
	 *
	 * @param  string  $offset
	 * @param  string|array  $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (is_array($value)) {
			foreach($value as $subItem => $url) {
				$this->setMenuItem($offset, is_int($subItem) ? $offset : $subItem, $url);
			}
		} else {
			$this->setMenuItem('Content', $offset, $value);
		}
	}

	/**
	 * Add an item to generic 'Content' parent item via a label and a route
	 *
	 * @param  string  $label
	 * @param  string  $route
	 */
	public function addItem($label, $route)
	{
		$this->setMenuItem('Content', $label, $this->url->route($route));
	}

	/**
	 * Add either a single or array of items to a new top level menu item
	 *
	 * @param  string  $item
	 * @param  string|array  $routeOrArray
	 */
	public function addTopLevelItem($item, $routeOrArray)
	{
		$this->attributes[$item] = [];

		$subItems = is_array($routeOrArray) ? $routeOrArray : [$item => $routeOrArray];

		$this->appendToItem($item, $subItems);
	}

	/**
	 * Append to an existing top level item, or create a new set if it doesn't
	 * exist
	 *
	 * @param  string  $item
	 * @param  array  $appends
	 * @return void
	 */
	public function appendToItem($item, array $appends)
	{
		foreach($appends as $label => $route) {
			$this->setMenuItem($item, $label, $this->url->route($route));
		}
	}

	/**
	 * Set menu item to a parent group
	 *
	 * @param  string  $parent
	 * @param  string  $label
	 * @param  string  $url
	 */
	protected function setMenuItem($parent, $label, $url)
	{
		$this->attributes[$parent][$label] = $url;
	}

	/**
	 * Retrieve array of top level items
	 *
	 * @return array
	 */
	public function getTopLevelItems()
	{
		$topLevelItems = [];

		foreach($this->getAttributes() as $item => $subItems) {
			$key = $this->shouldInheritLabelFromSubItem($item, $subItems) ? key($subItems) : $item;
			$topLevelItems[$key] = $subItems;
		}

		return $topLevelItems;
	}

	/**
	 * Determine if current menu item should use its own label or the label of
	 * its sub item
	 *
	 * @param  string  $item
	 * @param  array  $subItems
	 * @return boolean
	 */
	protected function shouldInheritLabelFromSubItem($item, $subItems)
	{
		return count($subItems) === 1 && $item === 'Content';
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

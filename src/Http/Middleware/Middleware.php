<?php

namespace Bozboz\Admin\Http\Middleware;

use Closure;

abstract class Middleware
{
    protected $whitelist = [
        'admin',
    ];

    abstract protected function shouldRedirect($request);

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->pageIsInWhiteList($request->path())) {
            $redirect = $this->shouldRedirect($request);

            if ($redirect) {
                return $redirect;
            }
        }

        return $next($request);
    }

    /**
     * Determine if given URL is in defined whitelist
     *
     * @param  string  $page
     * @return boolean
     */
    protected function pageIsInWhiteList($page)
    {
        return array_first($this->whitelist, function($key, $url) use ($page) {
            return strpos($page, $url) === 0;
        });
    }
}

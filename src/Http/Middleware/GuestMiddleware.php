<?php

namespace Bozboz\Admin\Http\Middleware;

use Auth;
use Closure;
use Redirect;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) return Redirect::to('admin');
    }
}

<?php

namespace Bozboz\Admin\Http\Middleware;

use Auth;
use Closure;
use Redirect;
use Bozboz\Permissions\Facades\Gate;

class AuthMiddleware
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
		if (Auth::check() && ! Gate::allows('admin_login')) {
			Auth::logout();
		}

		if (Auth::guest()) {
			return Redirect::guest('admin/login');
		}

		return $next($request);
    }
}

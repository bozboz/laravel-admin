<?php

namespace Bozboz\Admin\Http\Middleware;

use Auth;
use Redirect;
use Bozboz\Permissions\Facades\Gate;

class AuthMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function shouldRedirect($request)
    {
        if (! Gate::allows('admin_login')) {
            Auth::logout();
        }

        if (Auth::guest()) {
            return Redirect::guest('admin/login');
        }
    }
}

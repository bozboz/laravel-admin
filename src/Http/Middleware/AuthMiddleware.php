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
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Not authorized.'], 403);
            }
            return Redirect::guest('admin/login');
        }
    }
}

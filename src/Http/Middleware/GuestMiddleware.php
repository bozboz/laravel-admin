<?php

namespace Bozboz\Admin\Http\Middleware;

use Auth;
use Redirect;

class GuestMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function shouldRedirect($request)
    {
        if (Auth::check()) {
            return Redirect::to('admin');
        }
    }
}

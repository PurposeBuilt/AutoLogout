<?php

namespace PBS\Logout\Drivers\Frontend;

use App;
use Closure;
use Illuminate\Http\Request;
use Rainlab\User\Facades\Auth;
use October\Rain\Support\Facades\Config;

class Middleware
{
    /**
     * Update the last activity for the frontend users.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!App::runningInBackend() && Auth::check() && $request->header('x-october-request-handler') != 'onLogout') {
            Auth::user()->updateActivity();
        }

        return $response;
    }
}

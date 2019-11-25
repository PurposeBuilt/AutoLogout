<?php

namespace PBS\Logout\Drivers\Backend;

use Closure;
use Illuminate\Http\Request;
use Backend\Facades\BackendAuth;
use October\Rain\Support\Facades\Config;

class Middleware
{
    /**
     * Update the last activity for the backend users.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->isBackendRequest($request) && BackendAuth::check() && $request->header('x-october-request-handler') != 'onLogout') {
            BackendAuth::user()->updateActivity();
        }

        return $response;
    }

    /**
     * Check if the requested path starts with
     * the configured backend uri.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function isBackendRequest(Request $request)
    {
        return starts_with(
            trim($request->getPathInfo(), '/'),
            trim(Config::get('cms.backendUri', 'backend'), '/')
        );
    }
}

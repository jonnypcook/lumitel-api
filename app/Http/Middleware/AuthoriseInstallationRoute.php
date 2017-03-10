<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiValidation;
use App\Http\Middleware\Authorise\Installation;
use Closure;

class AuthoriseInstallationRoute
{
    use RouteModifier, ApiValidation, Installation;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check parameters
        $this->runValidator(['installationId' => 'bail|numeric|min:1'], $request->route()->parameters());

        // authorise device
        $installation = $this->authoriseInstallation($request->route()->parameter('installationId'));

        // change route data
        $this->replaceRouteIdWithModel($request->route(), 'installationId', $installation);

        return $next($request);
    }
}

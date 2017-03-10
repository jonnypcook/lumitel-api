<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiValidation;
use App\Http\Middleware\Authorise\Space;
use Closure;

class AuthoriseSpaceRoute
{
    use RouteModifier, ApiValidation, Space;

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
        $this->runValidator(['spaceId' => 'bail|numeric|min:1'], $request->route()->parameters());

        // authorise device
        $space = $this->authoriseSpace($request->route()->parameter('spaceId'));

        // change route data
        $this->replaceRouteIdWithModel($request->route(), 'spaceId', $space);

        return $next($request);
    }
}

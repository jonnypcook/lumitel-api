<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiValidation;
use App\Http\Middleware\Authorise\Device;
use Closure;

class AuthoriseDeviceRoute
{
    use RouteModifier, ApiValidation, Device;

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
        $this->runValidator(['deviceId' => 'required|bail|numeric|min:1'], $request->route()->parameters());

        // authorise device
        $device = $this->authoriseDevice($request->route()->parameter('deviceId'));

        // change route data
        $this->replaceRouteIdWithModel($request->route(), 'deviceId', $device);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class HasRole
{
    /**
     * @param $request
     * @param Closure $next
     * @param array ...$roles
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();
        foreach ($roles as $role) {
            if (!$user->hasRole($role)) {
                throw new AuthorizationException('Missing role requirement for user');
            }
        }

        return $next($request);
    }
}

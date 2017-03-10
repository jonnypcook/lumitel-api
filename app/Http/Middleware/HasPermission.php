<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class HasPermission
{
    /**
     * @param $request
     * @param Closure $next
     * @param array ...$permissions
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                throw new AuthorizationException('Missing permission requirement for user');
            }
        }

        return $next($request);
    }
}

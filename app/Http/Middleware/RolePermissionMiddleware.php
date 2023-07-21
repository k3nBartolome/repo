<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RolePermissionMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forRoles($roles);
    }
}

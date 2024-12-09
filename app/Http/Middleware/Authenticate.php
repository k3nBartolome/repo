<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  mixed  $request
     * @param  \Closure  $next
     * @param  array  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Log user info for debugging
        \Log::info('Authenticated User in Middleware:', ['user' => auth()->user()]);

        // Call parent to apply default behavior
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, array $guards)
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}

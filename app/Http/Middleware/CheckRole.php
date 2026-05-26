<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($user && $user->role === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}

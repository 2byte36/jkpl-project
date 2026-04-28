<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = $request->user;

        if (!$user) {
            return response()->json(['message' => 'Unauthorized. Please login first.'], 401);
        }

        if (!$user->is_admin) {
            return response()->json(['message' => 'Forbidden. Admins only.'], 403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Exception;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = null;
        $authHeader = $request->header('Authorization');

        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Cari user berdasarkan sub (id user)
            $user = User::find($decoded->sub);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Set ke request
            $request->auth = $decoded;
            $request->user = $user;
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        return $next($request);
    }
}

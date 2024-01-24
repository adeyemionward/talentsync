<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the request is for the 'index' method
        if ($request->route()->getActionMethod() == 'index') {
            // Exempt the 'index' method from authentication
            return $next($request);
        }

        try {
            // Authenticate the user using JWT
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // Return JSON response for authentication failure
            return response()->json(
                [
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. Please Login',

                ],401);
        }

        return $next($request);
    }
}

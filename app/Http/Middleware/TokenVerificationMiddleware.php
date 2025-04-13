<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::verifyToken($token);
        if ($result == 'unauthorized') {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized from middleware'
            ], 401);

        } else {
            // Merge custom values into the request
            $request->merge([
                'email' => $result->userEmail,
                'userId' => $result->userId
            ]);

            return $next($request);
        }
    }


}

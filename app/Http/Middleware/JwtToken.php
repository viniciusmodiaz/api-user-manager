<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class JwtToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token vazio'], 401);
        }

        try {

            $secret = env('SECRET_KEY');

            $decodedToken = JWT::decode($token, $secret, ['HS256']);

            dd($decodedToken);

        } catch (\Exception $e) {
            
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}
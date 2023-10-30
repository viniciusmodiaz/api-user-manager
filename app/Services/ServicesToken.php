<?php

namespace App\Services;
use Firebase\JWT\JWT;

class ServicesToken
{
    public function generateToken($params)
    {
        $secret = env('SECRET_KEY');

        $tokenPayload = [
            'id' =>     $params['id'],
            'iat' =>    time(),
            'exp' =>    time() + 3600, // Token válido por 1 hora
        ];

        $token = JWT::encode($tokenPayload, $secret, 'HS256');

        return $token; 

    }
}
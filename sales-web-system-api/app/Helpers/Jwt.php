<?php

namespace App\Helpers;

use App\Models\User;
use Firebase\JWT\JWT as FirebaseJWT;

class Jwt
{
    public static function createToken(User $user): string
    {
        $key = config('jwt.secretKey');

        $payload = [
            'exp' => time() + 3600,
            'iat' => time(),
            'data' => $user,
        ];

        return FirebaseJWT::encode($payload, $key, 'HS256');
    }
}

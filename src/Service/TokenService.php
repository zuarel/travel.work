<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{

    const exp_seconds = 60 * 15;

    public function getToken($payload, $options = [])
    {

        $exp = time() + self::exp_seconds;

        if (!empty($options['exp'])) {
            $exp = $options['exp'];
        }

        $payload['exp'] = $exp;

        return JWT::encode($payload, $_ENV['JWT_SECRET_KEY']);
    }

    public function verifyToken($token)
    {
        return JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
    }
}
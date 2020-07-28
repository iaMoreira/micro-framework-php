<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Resources\UserResource;
use Exception;
use Firebase\JWT\JWT;

class AuthService
{
    private static $key = "secret_key";

    public static function attempt(string $email, string $password): array
    {
        if (!$user = self::emailExists($email)) {
            throw new Exception("email not found", 400);
        }

        if (password_verify($password, $user->password)) {
            throw new Exception("password not matching", 400);
        }

        $base = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "data" => $user->toArray()
        );

        $resource = new UserResource($user);
        // generate jwt
        $token = JWT::encode($base, self::$key);
        $data['user'] = $resource->toArray();
        $data['expires_in'] = 1233;
        $data['token'] = $token;
        $data['token_type'] = 'bearer';
        return $data;
    }

    public static function user(): ?User
    {
        $authorization = request()->getHeader('Authorization');
        $sepators = explode(' ', $authorization);
        if (count($sepators) < 2) {
            return null;
            // throw new Exception('token not send', 400);
        }

        $token = $sepators[1];

        try {
            // decode jwt
            $decoded = JWT::decode($token, self::$key, array('HS256'));
            $user = new User();
            $user->fromArray(array($decoded->data));
            return $user;
        } // if decode fails, it means jwt is invalid
        catch (Exception $e) {
            return null;
            // throw new Exception('access denied', 401, $e);
        }
    }

    private static function emailExists(string $email)
    {
        $repository = new UserRepository();
        return $repository->findByEmail($email);
    }
}

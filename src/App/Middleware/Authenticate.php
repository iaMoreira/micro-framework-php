<?php

namespace App\Middleware;

use App\Services\AuthService;
use Framework\IMiddleware;
use Framework\Response;

class Authenticate implements IMiddleware
{

    public function handle(): ?Response
    {
        if (is_null(AuthService::user())) {
            return response()->setStatus(401)->json([
                "status"    => "error",
                "code"      => 401,
                "data"      => null,
                "message"   => 'access denied',
            ]);
        }
        return null;
    }
}

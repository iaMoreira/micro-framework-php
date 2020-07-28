<?php

namespace App\Middleware;

use App\Services\AuthService;
use Framework\IMiddleware;
use Framework\Response;

class ResourceOwner implements IMiddleware
{
    public function handle(): ?Response
    {
        $uri = request()->uri();
        $currentUser = AuthService::user(); 
        if (!in_array($currentUser->id, $uri)) {
            return response()->setStatus(401)->json([
                "status"    => "error",
                "code"      => 401,
                "data"      => null,
                "message"   => 'without permission to access',
            ]);
        }
        return null;
    }
}

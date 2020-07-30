<?php

namespace App\Controllers;

use App\Resources\UserResource;
use App\Services\Contracts\IUserService;
use Framework\AbstractController;

class UserController extends AbstractController
{
    public function __construct(IUserService $service, UserResource $resource)
    {
        $this->service = $service;
        $this->resource = $resource;
    }
}

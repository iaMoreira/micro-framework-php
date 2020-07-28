<?php

namespace App\Controllers;

use App\Resources\UserResource;
use App\Services\UserService;
use Framework\AbstractController;

class UserController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(new UserService());
        $this->resource = new UserResource();
    }
}

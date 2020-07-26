<?php

namespace App\Controllers;

use App\Services\UserService;
use Framework\AbstractController;

class UserController extends AbstractController
{

    public function __construct()
    {
        $this->service = new UserService();
        $this->request = request();
    }
}
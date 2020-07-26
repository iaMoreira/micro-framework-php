<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRespotory;
use App\Services\Contracts\IUserService;
use Framework\AbstractService;

class UserService extends AbstractService implements IUserService
{
    public function __construct()
    {
        $this->model = new User();
        $this->repository = new UserRepository();
    }
}

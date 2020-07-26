<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Framework\AbstractRepository;

class UserRepository extends AbstractRepository implements IUserRepository
{
    public function __construct()
    {
        $this->model = new User();  
    }
}

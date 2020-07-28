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

    public function findByEmail(string $email): ?User
    {
        $users = $this->all("email = '$email'", 1);
        if(count($users) > 0){
            return $users[0];
        }else {
            return null;
        }
    }
}

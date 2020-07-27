<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Contracts\IUserService;
use Framework\AbstractModel;
use Framework\AbstractService;

class UserService extends AbstractService implements IUserService
{
    public function __construct()
    {
        $this->model = new User();
        $this->repository = new UserRepository();
    }

    public function store(array $data): AbstractModel
    {
        $data['password'] = bcrypt($data['password']);
        return parent::store($data);
    }
}

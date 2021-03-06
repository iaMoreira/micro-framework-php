<?php

namespace App\Services;

use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;
use Framework\AbstractModel;
use Framework\AbstractService;

class UserService extends AbstractService implements IUserService
{
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data): AbstractModel
    {
        $data['password'] = bcrypt($data['password']);
        return parent::store($data);
    }

    public function update(int $id, array $data): AbstractModel
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return parent::update($id, $data);
    }
}

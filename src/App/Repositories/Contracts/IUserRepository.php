<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Framework\IAbstractRepository;

interface IUserRepository extends IAbstractRepository
{
    public function findByEmail(string $email): ?User;
}

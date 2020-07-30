<?php

namespace App\Services\Contracts;

use Framework\AbstractModel;
use Framework\IAbstractService;

interface IUserService extends IAbstractService
{
    public function store(array $data): AbstractModel;

    public function update(int $id, array $data): AbstractModel;
}
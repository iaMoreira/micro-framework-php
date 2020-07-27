<?php

namespace App\Services;

use App\Repositories\DrinkRepository;
use Framework\AbstractModel;
use Framework\AbstractService;

class DrinkService extends AbstractService
{
    public function __construct()
    {
        $this->repository = new DrinkRepository();
    }

    public function customStore(array $data, int $userId): AbstractModel
    {
        $data['user_id'] = $userId;
        return $this->store($data);
    }
}

<?php

namespace App\Services\Contracts;

use Framework\AbstractModel;
use Framework\IAbstractService;

interface IDrinkService extends IAbstractService
{
    public function rankingToday(): array;

    public function index(int $userId): array;

    public function customStore(array $data, int $userId): AbstractModel;
}

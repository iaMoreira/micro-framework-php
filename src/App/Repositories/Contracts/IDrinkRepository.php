<?php

namespace App\Repositories\Contracts;

use Framework\IAbstractRepository;

interface IDrinkRepository extends IAbstractRepository
{
    public function findAllByUserId(int $userId): array;

    public function countByUserId(int $userId): int;

    public function rankingByDay(string $day): array;
}

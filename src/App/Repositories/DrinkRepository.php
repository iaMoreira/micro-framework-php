<?php

namespace App\Repositories;

use App\Models\Drink;
use Framework\AbstractRepository;

class DrinkRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new Drink();
    }
}

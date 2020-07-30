<?php

namespace App\Repositories;

use App\Models\Drink;
use App\Repositories\Contracts\IDrinkRepository;
use Framework\AbstractRepository;

class DrinkRepository extends AbstractRepository implements IDrinkRepository
{
    public function __construct()
    {
        $this->model = new Drink();
    }

    public function findAllByUserId(int $userId): array
    {
        return $this->where('user_id', $userId)->get();
    }

    public function countByUserId(int $userId): int
    {
        return $this->count('*', "user_id = $userId");
    }

    public function rankingByDay(string $day): array
    {
        //SELECT `users`.`id`, `users`.`name`,SUM(`drinks`.`drink_ml`) AS `total` FROM `drinks` LEFT JOIN `users` ON `drinks`.`user_id` = `users`.`id` WHERE DATE_FORMAT(`drinks`.`created_at`,'%Y-%m-%d') = '2020-07-28' GROUP BY `users`.`id` ORDER BY `total` DESC
        return $this->query()->select(["users.id", "users.name", "SUM(drinks.drink_ml) AS total"])
            ->setBuilt("LEFT JOIN users ON drinks.user_id = users.id WHERE DATE_FORMAT(drinks.created_at,'%Y-%m-%d') = '$day' GROUP BY users.id ORDER BY total DESC")
            ->get();
    }
}

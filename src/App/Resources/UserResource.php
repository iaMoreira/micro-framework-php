<?php

namespace App\Resources;

use App\Models\Drink;
use App\Models\User;
use App\Repositories\DrinkRepository;
use Framework\BaseResource;

class UserResource extends BaseResource
{
    /**
     * The repository instance that
     *
     * @var DrinkRepository $drinkRepository
     */
    protected $drinkRepository;

    public function __construct(User $model = null)
    {
        parent::__construct($model);
        $this->drinkRepository = new DrinkRepository(new Drink);
        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "drink_counter" => $this->drinkRepository->countByUserId($this->id)
        ];
    }
}

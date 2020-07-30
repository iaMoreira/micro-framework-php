<?php

namespace App\Services;

use App\Repositories\Contracts\IDrinkRepository;
use App\Services\Contracts\IDrinkService;
use Framework\AbstractModel;
use Framework\AbstractService;

class DrinkService extends AbstractService implements IDrinkService
{
    /**
     * Instance that 
     *
     * @var IDrinkRepository $repository
     */
    protected $repository;

    public function __construct(IDrinkRepository $repository)
    {
        $this->repository = $repository;
    }
    public function rankingToday(): array
    {
        $today = date('Y-m-d');
        return $this->repository->rankingByDay($today);
    }

    public function index(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }

    public function customStore(array $data, int $userId): AbstractModel
    {
        $data['user_id'] = $userId;
        return $this->store($data);
    }
}

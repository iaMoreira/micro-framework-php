<?php

namespace App\Controllers;

use App\Services\DrinkService;
use Exception;
use Framework\AbstractController;
use Framework\Database;
use Framework\ResponseTrait;

class DrinkController
{
    use ResponseTrait;


    /**
     * Instance that 
     *
     * @var AbstractService $service
     */
    protected $service;

    public function __construct()
    {
        $this->service = new DrinkService();
    }

    public function index(int $userId)
    {
        $models = $this->service->index($userId);
        return $this->responseWithArray($models);
    }

    public function store(int $userId)
    {
        $data = request()->all();

        if (empty($data)) {
            return $this->responseEmpty();
        }

        $validatorResponse = $this->validateRequest();

        if (!empty($validatorResponse)) {
            return $this->responseValidation($validatorResponse);
        }

        Database::beginTransaction();
        try {
            $model = $this->service->customStore($data, $userId);
            Database::commit();
            return $this->setStatusCode(201)->respondWithObject($model);
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);
        }
    }

    public function rankingToday()
    {
        $models = $this->service->rankingToday();
        return $this->responseWithArray($models);
    }

    private function validateRequest(int $id = null)
    {
        $validator = request()->validate($this->service->getRules($id));

        return $validator->fails();
    }
}

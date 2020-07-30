<?php

namespace App\Controllers;

use App\Services\DrinkService;
use Exception;
use Framework\Database;
use Framework\Response;
use Framework\ResponseTrait;
use Framework\ValidationRequestTrait;

class DrinkController
{
    use ResponseTrait, ValidationRequestTrait;


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

    public function index(int $userId): Response
    {
        $models = $this->service->index($userId);
        return $this->responseWithArray($models);
    }

    public function store(int $userId): Response
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

    public function rankingToday(): Response
    {
        $models = $this->service->rankingToday();
        return $this->responseWithArray($models);
    }
}

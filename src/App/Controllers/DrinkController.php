<?php

namespace App\Controllers;

use App\Services\DrinkService;
use Exception;
use Framework\AbstractController;
use Framework\Database;

class DrinkController extends AbstractController
{
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

    public function customStore(int $userId)
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
}

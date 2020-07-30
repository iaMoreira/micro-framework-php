<?php

namespace Framework;

use Exception;

abstract class AbstractController
{
    use ResponseTrait, ValidationRequestTrait;

    /**
     * Instance that 
     *
     * @var AbstractService $service
     */
    protected  $service;

    /**
     * Instance that 
     *
     * @var BaseResource $resource
     */
    protected  $resource;

    public function __construct(AbstractService $service = null)
    {
        $this->service = $service;
        $this->resource = new BaseResource();
    }

    public function index(): Response
    {
        $models = $this->service->findAll();
        return $this->respondWithCollection($models, $this->resource);
    }

    public function store(): Response
    {
        $data = request()->all();

        // Send failed response if empty request
        if (empty($data)) {
            return $this->responseEmpty();
        }

        // Validation
        $validatorResponse = $this->validateRequest();

        // Send failed response if validation fails and return array of errors
        if (!empty($validatorResponse)) {
            return $this->responseValidation($validatorResponse);
        }

        Database::beginTransaction();
        try {
            $model = $this->service->store($data);
            Database::commit();
            return $this->setStatusCode(201)->respondWithObject($model, $this->resource);
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);
        }
    }

    public function show(int $id): Response
    {
        $model = $this->service->findOne($id);
        if (is_null($model)) {
            return $this->responseNotFound();
        }

        return $this->respondWithObject($model, $this->resource);
    }

    public function update(int $id): Response
    {
        $data = request()->all();

        // Send failed response if empty request
        if (empty($data)) {
            return $this->responseEmpty();
        }

        // Validation
        $validatorResponse = $this->validateRequest($id);

        // Send failed response if validation fails and return array of errors
        if (!empty($validatorResponse)) {
            return $this->responseValidation($validatorResponse);
        }

        Database::beginTransaction();
        try {
            $model = $this->service->update($id, $data);
            Database::commit();
            return $this->respondWithObject($model, $this->resource);
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);
        }
    }

    public function destroy(int $id): Response
    {
        Database::beginTransaction();
        try {
            $this->service->delete($id);
            Database::commit();
            return $this->responseDeleted();
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);
        }
    }
}

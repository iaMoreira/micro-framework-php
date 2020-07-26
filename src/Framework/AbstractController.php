<?php

namespace Framework;

use Exception;

abstract class AbstractController
{
    use ResponseTrait;

    /**
     * Instance that 
     *
     * @var AbstractService $service
     */
    protected  $service;

    public function __construct(AbstractService $service = null)
    {
        $this->service = $service;
    }

    public function index(): Response
    {
        $models = $this->service->findAll();
        return $this->responseWithArray($models);
    }

    public function store(): Response
    {
        $data = request()->all();
        
        // Send failed response if empty request
        if (empty($data)) {
            return $this->responseEmpty();
        }

        Database::beginTransaction();
        try {
            $model = $this->service->store($data);
            Database::commit();
            return $this->setStatusCode(201)->respondWithObject($model);
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);
        }
    }

    public function show(int $id): Response
    {
        $model = $this->service->findOneOrFail($id);
        return $this->respondWithObject($model);
    }

    public function update(int $id): Response
    {
        $data = request()->all();
        
        // Send failed response if empty request
        if (empty($data)) {
            return $this->responseEmpty();
        }

        Database::beginTransaction();
        try {
            $model = $this->service->update($id, $data);
            Database::commit();
            return $this->respondWithObject($model);
        } catch (Exception $ex) {
            Database::rollback();
            throw new Exception($ex);        }
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

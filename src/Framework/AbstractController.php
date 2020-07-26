<?php

namespace Framework;

use Exception;

abstract class AbstractController
{
    /**
     * Instance that 
     *
     * @var Request $request
     */
    protected  $request;
    /**
     * Instance that 
     *
     * @var AbstractService $service
     */
    protected  $service;

    public function __construct(AbstractService $service = null)
    {
        $this->service = $service;
        $this->request = request();
    }


    public function index()
    {
        $models = $this->service->findAll();
        return response()->json($models)->send();
    }

    public function store()
    {
        $data = $this->request->all();
        // TODO validation empty
        
        Database::beginTransaction();
        try {
            $model = $this->service->store($data);
            Database::commit();
            return response()->json($model)->send();
        } catch(\Exception $ex) {
            Database::rollback();
            throw new Exception("store error", $ex);
        }

    }

    public function show(int $id)
    {
        $model = $this->service->findOneOrFail($id);
        return response()->json($model)->send();
    }

    public function update(int $id) 
    {
        $data = $this->request->all();
        $model = $this->service->update($id, $data);
        return response()->json($model)->send();
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->setStatus(204)->send();
    }
}

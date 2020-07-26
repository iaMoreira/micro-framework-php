<?php

namespace Framework;

abstract class AbstractController
{
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
        echo json_encode($this->service->findAll(), JSON_NUMERIC_CHECK);
    }

    public function store()
    {
        $data = $this->request->all();
        $user = $this->service->store($data);
        return response()->json($user)->send();
    }

    public function show(int $id)
    {
        // $model = $this->service->
    }

}


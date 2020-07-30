<?php

namespace Framework;

trait ValidationRequestTrait
{
    protected function validateRequest(int $id = null): ?array
    {
        $validator = request()->validate($this->service->getRules($id));
        return $validator->fails();
    } 
}
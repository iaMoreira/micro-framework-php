<?php

namespace App\Controllers;

use App\Services\AuthService;
use Exception;
use Framework\ResponseTrait;

class LoginController
{
    use ResponseTrait;

    private $service;

    public function __construct()
    {
        $this->service = new AuthService;
    }

    public function login()
    {
        $validatorResponse = $this->validateRequest();

        if (!empty($validatorResponse)) {
            return $this->responseValidation($validatorResponse);
        }

        $credentials = request()->only('email', 'password');

        try {
            $data = $this->service->attempt($credentials->email, $credentials->password);
            return $this->responseWithToken($data);
        } catch (\Exception $e) {
            throw new Exception($e);
        }
    }

    protected function validateRequest()
    {
        $validator = request()->validate([
            'email' => 'required|min:3|max:100',
            'password' => 'required|min:5|max:20',
        ]);

        return $validator->fails();
    }
}

<?php

namespace App\Controllers;

use App\Services\Contracts\IAuthService;
use Exception;
use Framework\Response;
use Framework\ResponseTrait;

class LoginController
{
    use ResponseTrait;


    /**
     * Instance that 
     *
     * @var IAuthService $service
     */
    private $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }

    public function login(): Response
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

    protected function validateRequest(): ?array
    {
        $validator = request()->validate([
            'email' => 'required|min:3|max:100',
            'password' => 'required|min:5|max:20',
        ]);

        return $validator->fails();
    }
}

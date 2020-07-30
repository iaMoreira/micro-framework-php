<?php

namespace App\Services\Contracts;

use App\Models\User;

interface IAuthService
{
    public static function attempt(string $email, string $password): array;

    public static function user(): ?User;
}

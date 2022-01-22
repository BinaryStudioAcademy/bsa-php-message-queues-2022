<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\User;
use App\Values\Auth\UserData;

interface AuthService
{
    public function register(UserData $userData): User;

    public function auth(string $token): User;

    public function getUser(): User;
}

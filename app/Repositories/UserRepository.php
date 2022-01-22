<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepository as IUserRepository;

class UserRepository implements IUserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function store(User $user): User
    {
        $user->save();

        return $user;
    }
}

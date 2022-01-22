<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use App\Services\Contracts\AuthService as IAuthService;
use App\Values\Auth\Token;
use App\Values\Auth\UserData;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

class AuthService implements IAuthService
{
    private UserRepository $userRepository;
    private Guard|StatefulGuard $auth;

    /**
     * @param UserRepository $userRepository
     * @param Auth $auth
     */
    public function __construct(UserRepository $userRepository, Auth $auth)
    {
        $this->userRepository = $userRepository;
        $this->auth = $auth->guard();
    }

    /**
     * @param string $token
     * @return User
     * @throws \LogicException
     */
    public function auth(string $token): User
    {
        $userData = Token::parse($token);

        $user = $this->userRepository->findByEmail($userData->email);

        if ($user === null) {
            throw new \LogicException('User does not exist');
        }

        if ($userData->checkPassword($user->password) === false) {
            throw new \LogicException('Password is incorrect');
        }

        $this->auth->setUser($user);        

        return $user;
    }

    /**
     * @param UserData $userData
     * @return User
     */
    public function register(UserData $userData): User
    {
        $user = new User([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => $userData->password,
        ]);

        $user = $this->userRepository->store($user);

        return $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->auth->user();
    }
}

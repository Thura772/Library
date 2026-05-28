<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Factory\UserFactory;
use App\Mapper\UserMapper;
use App\Http\Request\RegisterRequest;
use App\Http\Request\LoginRequest;

class AuthService
{
    public function __construct(
        private UserRepository $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    public function register(RegisterRequest $request): array
    {
        if ($this->repo->findByEmail($request->data()['email'])) {
            return [
                'success' => false,
                'errors' => [
                    'email' => 'This email is already registered.'
                ],
                'user' => null
            ];
        }

        $data = $request->data();

        $user = UserFactory::register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        $this->repo->create($user);

        return [
            'success' => true,
            'errors' => [],
            'user' => UserMapper::toDTO($user) // ✅ DTO preserved
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(LoginRequest $request): array
    {
        $data = $request->data();

        $user = $this->repo->findByEmail($data['email']);

        if (!$user || !$user->verifyPassword($data['password'])) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Invalid email or password.'
                ],
                'user' => null
            ];
        }

        return [
            'success' => true,
            'errors' => [],
            'user' => UserMapper::toDTO($user) // ✅ DTO preserved
        ];
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }
}
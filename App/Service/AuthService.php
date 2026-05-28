<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Factory\UserFactory;
use App\Mapper\UserMapper;

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
    public function register(array $data): array // CHANGED: RegisterRequest → array
    {
        if ($this->repo->findByEmail($data['email'])) { // CHANGED: $request->data() removed
            return [
                'success' => false,
                'errors' => [
                    'email' => 'This email is already registered.'
                ],
                'user' => null
            ];
        }

        $user = UserFactory::register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        $this->repo->create($user);

        return [
            'success' => true,
            'errors' => [],
            'user' => UserMapper::toDTO($user)
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(array $data): array // CHANGED: LoginRequest → array
    {
        $user = $this->repo->findByEmail($data['email']); // CHANGED

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
            'user' => UserMapper::toDTO($user)
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }
}
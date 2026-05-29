<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Factory\UserFactory;
use App\Mapper\UserMapper;
use App\Response\ServiceResponse;

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
    public function register(
        array $data
    ): ServiceResponse {

        if (
            $this->repo->findByEmail($data['email'])
        ) {

            return ServiceResponse::error([
                'email' =>
                    'This email is already registered.'
            ]);
        }

        $user = UserFactory::register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        $this->repo->create($user);

        return ServiceResponse::success(
            UserMapper::toDTO($user)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(
        array $data
    ): ServiceResponse {

        $user = $this->repo->findByEmail(
            $data['email']
        );

        if (
            !$user ||
            !$user->verifyPassword($data['password'])
        ) {

            return ServiceResponse::error([
                'general' =>
                    'Invalid email or password.'
            ]);
        }

        return ServiceResponse::success(
            UserMapper::toDTO($user)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        if (
            session_status() === PHP_SESSION_NONE
        ) {
            session_start();
        }

        $_SESSION = [];

        session_destroy();
    }
}
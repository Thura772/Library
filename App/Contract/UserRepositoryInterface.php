<?php

namespace App\Contract;

use App\Model\User;

interface UserRepositoryInterface
extends BaseRepositoryInterface
{
    /*
    |--------------------------------------------------------------------------
    | FIND USER BY EMAIL
    |--------------------------------------------------------------------------
    */

    public function findByEmail(
        string $email
    ): ?User;

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user
    ): bool;

    /*
    |--------------------------------------------------------------------------
    | GET USER BY ID
    |--------------------------------------------------------------------------
    */

    public function getById(
        int $id
    ): ?User;
}
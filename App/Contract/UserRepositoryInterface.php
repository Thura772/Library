<?php

namespace App\Contract;

use App\Model\User;

interface UserRepositoryInterface
{
    /*
    | FIND USER BY EMAIL
    */
    public function findByEmail(
        string $email
    ): ?User;

    /*
    | CREATE USER
    */
    public function create(
        User $user
    ): bool;
}
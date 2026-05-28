<?php

namespace App\Factory;

use App\Model\User;

class UserFactory
{
    public static function register(
        string $name,
        string $email,
        string $plainPassword
    ): User {

        // ❌ NO VALIDATION HERE ANYMORE

        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);

        return User::fromDatabase([
            'id' => null,
            'name' => $name,
            'email' => $email,
            'password' => $hash,
            'role' => 'user'
        ]);
    }
}
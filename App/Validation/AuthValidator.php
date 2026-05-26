<?php

namespace App\Validation;

class AuthValidator
{
    public static function registerRules(): array
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed']
        ];
    }

    public static function loginRules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    }
}
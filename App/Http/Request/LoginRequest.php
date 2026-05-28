<?php

namespace App\Http\Request;

class LoginRequest extends FormRequest
{
    public static function rules(): array
    {
        return [
            'email' => [
                'required' => true,
                'email' => true
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ];
    }
}
<?php

namespace App\Http\Request;

class RegisterRequest extends FormRequest
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 50
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 100
            ],
            'password' => [
                'required' => true,
                'min' => 8
            ],
            'confirm_password' => [
                'required' => true,
                'match' => 'password'
            ]
        ];
    }
}
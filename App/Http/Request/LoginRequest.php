<?php

namespace App\Http\Request;

class LoginRequest extends FormRequest
{
    public static function rules(): array
    {
        return [
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 100
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ];
    }

    public function credentials(): array
    {
        return [
            'email' => strtolower(trim($this->data['email'] ?? '')),
            'password' => $this->data['password'] ?? ''
        ];
    }
}

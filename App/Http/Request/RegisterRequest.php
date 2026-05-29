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

    public function toArray(): array
    {
        return [
            'name' => trim($this->data()['name'] ?? ''),
            'email' => strtolower(trim($this->data()['email'] ?? '')),
            'password' => $this->data()['password'] ?? ''
        ];
    }

    public function isStrongPassword(): bool
    {
        $password = $this->data()['password'] ?? '';

        return preg_match('/[A-Z]/', $password)
            && preg_match('/[0-9]/', $password);
    }
}
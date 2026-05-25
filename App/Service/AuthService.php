<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;



class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register user
     * @return array errors (empty if success)
     */
    public function register(array $data): array
    {
        $errors = [];

        // 1. Name validation
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required.';
        }

        // 2. Email validation
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email is required.';
        }

        // 3. Password validation
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }

        // 4. Confirm password (safe check)
        if (empty($data['confirm_password']) || $data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        // 5. Check duplicate email
        if ($this->userRepository->findByEmail($data['email'])) {
            return ['email' => 'This email is already registered.'];
        }

        // 6. Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // 7. Save user
        $this->userRepository->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $hashedPassword
        ]);

        return [];
    }
}
